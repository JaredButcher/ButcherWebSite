<?php
require_once("../Models/Connection.php");
class ModClass extends Connection{
	public function AddUser($Username, $Password, $Name, $Email){
		$Salt = random_bytes(64);
		$Salt = hash("sha512", $Salt );
		$Hash = hash("sha512", $Password.$Salt);
		$stmt =  $this->Conn->prepare('Insert into users (username, hash, salt, name, email) values (:Username,:Hash,:Salt,:Name,:Email);');
		$stmt->bindParam(':Username', $Username, PDO::PARAM_STR);
		$stmt->bindParam(':Hash', $Hash, PDO::PARAM_STR);
		$stmt->bindParam(':Salt', $Salt, PDO::PARAM_STR);
		$stmt->bindParam(':Name', $Name, PDO::PARAM_STR);
		$stmt->bindParam(':Email', $Email, PDO::PARAM_STR);
		$stmt->execute();
	}
	public function Login($Username, $Password){
		$User = $this->Qurry("Select * from users where username=?;",$Username);
		if($User['id'] != null && hash("sha512", $Password . $User['salt']) == $User['hash']){
			$_SESSION['Username'] = $User['username'];
			$_SESSION['Name'] = $User['name'];
			$_SESSION['Email'] = $User['email'];
			$_SESSION['Id'] = $User['id'];
			$_SESSION['Power'] = $User['power'];
			return True;
		} else {
			return False;
		}
	}
	public function GetInfo($Id){
		$User = $this->Qurry("Select * from users where id=?;",$Id);
		return $User;
	}
	public function ReloadLogin($Id){
		$User = $this->GetInfo($Id);
		$_SESSION['Username'] = $User['username'];
		$_SESSION['Name'] = $User['name'];
		$_SESSION['Email'] = $User['email'];
		$_SESSION['Id'] = $User['id'];
		$_SESSION['Power'] = $User['power'];
	}
	public function EditUsername($Id, $Username){
		$stmt = $this->Conn->prepare("Update users Set username=:Username Where id={$Id};");
		$stmt->bindParam(':Username', $Username, PDO::PARAM_STR);
		$stmt->execute();
	}
	public function EditPassword($Id, $Password){
		$User = $this->Qurry("Select salt from users where id=?;",$Id);
		$Hash = hash("sha512", $Password . $User['salt']);
		$stmt = $this->Conn->prepare("Update users Set hash=:Hash Where id={$Id};");
		$stmt->bindParam(':Hash', $Hash);
		$stmt->execute();
	}
	public function EditName($Id, $Name){
		$stmt = $this->Conn->prepare("Update users Set name=:Name Where id={$Id};");
		$stmt->bindParam(':Name', $Name, PDO::PARAM_STR);
		$stmt->execute();
	}
	public function EditEmail($Id, $Email){
		$stmt = $this->Conn->prepare("Update users Set email=:Email Where id={$Id};");
		$stmt->bindParam(':Email', $Email, PDO::PARAM_STR);
		$stmt->execute();
	}
	public function EditPower($Id, $Power){
		$stmt = $this->Conn->prepare("Update users Set power=:Power Where id={$Id};");
		$stmt->bindParam(':Power', $Power, PDO::PARAM_INT);
		$stmt->execute();
	}
	public function CheckUsername($Username){
		$Id = $this->Qurry("Select id from users where username=?",$Username);
		if($Id == null){
			return false;
		} else {
			return true;
		}
	}
	public function GetUsers($Filter){
		if($Filter == ""){
			$stmt = $this->Conn->prepare("Select id,username,name,email,power from users Order By id Limit 50;");
		} else {
			$stmt = $this->Conn->prepare("Select id,username,name,email,power From users Where Contains(username,:Filter) OR Contains(name,:Filter) OR Contains(email,:Filter) OR Contains(id,:Filter) Order By id Limit 50;");
			$stmt->bindParam(':Filter', $Filter, PDO::PARAM_STR);
		}
		$stmt->execute();
		return $stmt->fetchAll();
	}
	public function DelUsers($Ids){
		$Sql = "";
		foreach($Ids as $Id){
			$Sql = $Sql.$Id.",";
		}
		$Sql = rtrim($Sql, ",");
		$Sql = "Delete From users Where id In ({$Sql});";
		$stmt = $this->Conn->prepare($Sql);
		$stmt->execute();
	}
	public function AddRemember($Id){
		$Key = random_bytes(64);
		$Hash = hash("sha512", $Key);
		$stmt = $this->Conn->prepare("Insert Into remember (user,hash) Values (:Id,:Hash);");
		$stmt->bindParam(":Id",$Id);
		$stmt->bindParam(":Hash",$Hash);
		$stmt->execute();
		$this->RemoveOldRemembers($Id);
		return $Key;
	}
	public function CheckRemember($Key){
		$Hash = hash("sha512", $Key);
		$stmt = $this->Conn->prepare("Select * From remember Where hash=:Hash;");
		$stmt->bindParam(":Hash",$Hash);
		$stmt->execute();
		$User = $stmt->fetch(PDO::FETCH_ASSOC);
		if($User){
			$this->ReloadLogin($User['user']);
		} else {
			return false;
		}
	}
	public function RemoveOldRemembers($Id){
		$stmt = $this->Conn->prepare("Delete From remember Where user = :Id And id Not In (Select id From (Select id From remember Where user = :Id Order By ts Desc Limit 1) temp_tab);");
		$stmt->bindParam(":Id",$Id);
		$stmt->execute();
	}
}
?>