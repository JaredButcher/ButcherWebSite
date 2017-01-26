<?php
require_once("../Models/Connection.php");
class ModClass extends Connection{
	public function MakeRoom($RoomName, $RoomDesc){
		$stmt = $this->Conn->prepare("INSERT INTO rooms (owner, name, info) VALUES (:Owner,:Name,:Info);");
		$stmt->bindParam(":Owner", $_SESSION['Id']);
		$stmt->bindParam(":Name", $RoomName);
		$stmt->bindParam(":Info", $RoomDesc);
		$stmt->execute();
	}
	public function DelRoom($Id){
	}
	public function MakePost($RoomId, $Content){
		$Posts = $this->GetPosts($RoomId);
		$Number = 0;
		foreach ($Posts as $Post){
			$Temp = intval(substr(strstr($Post['id'], '-'),1));
			if($Temp >= $Number){
				$Number = $Temp + 1;
			}
		}
		$Id = "{$RoomId}-{$Number}";
		$stmt = $this->Conn->prepare("INSERT INTO posts (id, owner, content) VALUES (:Id,:Owner,:Content);");
		$stmt->bindParam(":Id", $Id);
		$stmt->bindParam(":Owner", $_SESSION['Id']);
		$stmt->bindParam(":Content", $Content);
		$stmt->execute();
	}
	public function DelPost($Id){
		$Sql = "";
		$Sql = "Delete From posts Where id = '{$Id}';";
		$stmt = $this->Conn->prepare($Sql);
		$stmt->execute();
	}
	public function MakeReport($PostId, $RoomId){
	}
	public function DelReport($Id){
	}
	public function GetRooms(){
		$stmt = $this->Conn->Prepare("Select rooms.*, users.username From rooms LEFT JOIN users ON rooms.owner = users.id Order By id;");
		$stmt->execute();
		return $stmt->fetchAll();
	}
	public function GetRoom($Id){
		$Room = $this->Qurry("SELECT rooms.*, users.username FROM rooms LEFT JOIN users ON rooms.owner = users.id WHERE rooms.id=?;", $Id);
		return $Room;
	}
	public function GetPost($Id){
		$Post = $this->Qurry("SELECT * FROM posts WHERE id=?;", $Id);
		return $Post;
	}
	public function GetPosts($RoomId){
		$stmt = $this->Conn->prepare("SELECT posts.*, users.username FROM posts LEFT JOIN users ON posts.owner = users.id WHERE posts.id REGEXP '^{$RoomId}-.+$' ORDER BY posts.ts DESC;");
		$stmt->execute();
		return $stmt->fetchAll();
	}
}?>