<?php class ConClass{
	private $Mod;
	
	public function __construct(){
		$GLOBALS['VD'] = array('Username' => "", 'EPassword' => "", 'Email' => "", 'Name' => "",'EUsername' => "", 'EEmail' => "", 'EName' => "", 'Cap' => "", 'ENewPassword' => "", 'Users' => array());
		require_once("../Models/UsersMod.php");
		$this->Mod = new ModClass();
	}
	public function Login(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$Username = htmlspecialchars($_POST["Username"]);
			$Password = htmlspecialchars($_POST["Password"]);
			if($this->Mod->Login($Username,$Password)){
				if(isset($_POST["Remember"])){
					setcookie("Remember", $this->Mod->AddRemember($_SESSION['Id']), time() + (86400 * 30), "/");
				}
				header('Location: /',301);
				die();
			} else {
				$GLOBALS['VD']['Sucess'] = "Incorrect username and password combination.";
				$GLOBALS['VD']['Username'] = $Username;
			}
		}
	}
	public function Register(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$Username = htmlspecialchars($_POST["Username"]);
			$Password = htmlspecialchars($_POST["Password"]);
			$Email = htmlspecialchars($_POST["Email"]);
			$Name = htmlspecialchars($_POST["Name"]);
			$Good = true;
			if(strlen($Username) < 1){
				$GLOBALS['VD']['EUsername'] = "* Username is Required";
				$Good = false;
			} elseif(strlen($Username) > 128){
				$Good = false;
				$GLOBALS['VD']['EUsername'] = "* Username is too long";
			} elseif($this->Mod->CheckUsername($Username)){
				$Good = false;
				$GLOBALS['VD']['EUsername'] = "* Username aready taken";
			} 
			if(strlen($Password) < 8) {
				$GLOBALS['VD']['EPassword'] = "* Password Must be at Least 8 Chartors";
				$Good = false;
			}elseif(strlen($Password) > 512) {
				$GLOBALS['VD']['EPassword'] = "* Password is too long";
				$Good = false;
			}
			if(strlen($Email) < 5){
				$GLOBALS['VD']['EEmail'] = "* Email is too short";
				$Good = false;
			}elseif(strlen($Email) > 256) {
				$GLOBALS['VD']['EEmail'] = "* Email is too long";
				$Good = false;
			}
			if(strlen($Name) < 1){
				$GLOBALS['VD']['EName'] = "* Name is Required";
				$Good = false;
			}elseif(strlen($Name) > 128) {
				$GLOBALS['VD']['EName'] = "* Name is too long";
				$Good = false;
			}
			$response = $_POST['g-recaptcha-response'];
			try{
				$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$GLOBALS['Secrets']['Cap']}&response={$response}");
				$success = json_decode($verify);
			} catch (Exception $e){
				$Good = false;
				$GLOBALS['VD']['Cap'] = "Failed to connected to recaptcha servers, try again later";
			}
			if($success->success === false){
				$Good = false;
				$GLOBALS['VD']['Cap'] = "Recaptcha failed";
			}
			if($Good){
				$this->Mod->AddUser($Username,$Password,$Name,$Email);
				$this->Mod->Login($Username,$Password);
				header('Location: /',301);
				die();
			} else {
				$GLOBALS['VD']['Sucess'] = "Not Successful";
			}
			$GLOBALS['VD']['Username'] = $Username;
			$GLOBALS['VD']['Email'] = $Email;
			$GLOBALS['VD']['Name'] = $Name;
		}
	}
	public function Account(){
		if($_SESSION['Username'] == ""){
			header('Location: /',301);
			die();
		} else {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if($this->Mod->Login($_SESSION['Username'],htmlspecialchars($_POST["Password"]))){
					$Username = htmlspecialchars($_POST["Username"]);
					$Password = htmlspecialchars($_POST["NewPassword"]);
					$Email = htmlspecialchars($_POST["Email"]);
					$Name = htmlspecialchars($_POST["Name"]);
					if($Password != ""){
						if(strlen($Password) < 8){
							$GLOBALS['VD']['ENewPassword'] = "* Password Must be at Least 8 Chartors";
							$Good = false;
						} else {
							$this->Mod->EditPassword($_SESSION['Id'],$Password);
						}
					} else {
						$Password = htmlspecialchars($_POST["Password"]);
					}
					if($Username != $_SESSION['Username'] && $Username != ""){
						if($this->Mod->CheckUsername($Username)){
							$Good = false;
							$GLOBALS['VD']['EUsername'] = "* Username aready taken";
						} else {
							$this->Mod->EditUsername($_SESSION['Id'],$Username);
						}
					}
					if($Email != "" && $Email != $_SESSION['Email']){
						$this->Mod->EditEmail($_SESSION['Id'],$Email);
					}
					if($Name != "" && $Name != $_SESSION['Name']){
						$this->Mod->EditName($_SESSION['Id'],$Name);
					}
					$this->Mod->Login($Username,$Password);
				} else {
					$GLOBALS['VD']['EPassword'] = "* Incorrect Password";
				}
			}
		}
	}
	public function Logout(){
		setcookie("Remember", "", time() - 3600, "/");
		session_unset();
		header('Location: /Users/Login/',301);
		die();
	}
	public function UserManagement(){
		$this->Mod->ReloadLogin($_SESSION['Id']);
		$Filter = "";
		if($_SESSION["Power"] >= $GLOBALS['Secrets']['ManagePower']){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(isset($_POST['Filter'])){
					
				} else {
					$this->Mod->DelUsers(array_keys($_POST));
				}
			}
			$GLOBALS['VD']['Users'] = $this->Mod->GetUsers($Filter);
		} else {
			header('Location: /',301);
			die();
		}
	}
	public function AccountAdmin(){
		$this->Mod->ReloadLogin($_SESSION['Id']);
		if($_SESSION["Power"] >= $GLOBALS['Secrets']['ManagePower']){
			if(isset($_GET['Id'])){
				if($_GET['Id'] != 1){
					$User = $this->Mod->GetInfo($_GET['Id']);
					if($User != false){
						$GLOBALS['VD']['Username'] = $User['username'];
						$GLOBALS['VD']['Name'] = $User['name'];
						$GLOBALS['VD']['Email'] = $User['email'];
						$GLOBALS['VD']['Power'] = $User['power'];
						$GLOBALS['VD']['Id'] = $User['id'];
						if($_SERVER['REQUEST_METHOD'] == "POST"){
							$Username = htmlspecialchars($_POST["Username"]);
							$Password = htmlspecialchars($_POST["NewPassword"]);
							$Email = htmlspecialchars($_POST["Email"]);
							$Name = htmlspecialchars($_POST["Name"]);
							$Power = intval($_POST["Power"]);
							if($Password != ""){
								if(strlen($Password) < 8){
									$GLOBALS['VD']['ENewPassword'] = "* Password Must be at Least 8 Chartors";
								} else {
									$this->Mod->EditPassword($User['id'],$Password);
								}
							}
							if($Username != $User['username'] && $Username != ""){
								if($this->Mod->CheckUsername($Username)){
									$GLOBALS['VD']['EUsername'] = "* Username aready taken";
								} else {
									$this->Mod->EditUsername($User['id'],$Username);
									$GLOBALS['VD']['Username'] = $Username;
								}
							}
							if($Email != "" && $Email != $User['email']){
								$this->Mod->EditEmail($User['id'],$Email);
								$GLOBALS['VD']['Email'] = $Email;
							}
							if($Name != "" && $Name != $User['name']){
								$this->Mod->EditName($User['id'],$Name);
								$GLOBALS['VD']['Name'] = $Name;
							}
							if($Power != "" && $Power != $User['power']){
								$this->Mod->EditPower($User['id'],$Power);
								$GLOBALS['VD']['Power'] = $Power;
							}
						} 
					} else {
						header('Location: /Users/UserManagement/',301);
						die();
					}
				}
			} else {
				header('Location: /Users/UserManagement/',301);
				die();
			}
		}
	}
}
?>