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
		$Number = count($this->GetPosts($RoomId));
		$Id = "{$RoomId}-{$Number}";
		echo $Id;
		$stmt = $this->Conn->prepare("INSERT INTO posts (id, owner, content) VALUES (:Id,:Owner,:Content);");
		$stmt->bindParam(":Id", $Id);
		$stmt->bindParam(":Owner", $_SESSION['Id']);
		$stmt->bindParam(":Content", $Content);
		$stmt->execute();
	}
	public function DelPost($Id){
	}
	public function MakeReport($PostId, $RoomId){
	}
	public function DelReport($Id){
	}
	public function GetRooms(){
		$stmt = $this->Conn->Prepare("Select * From rooms Order By id;");
		$stmt->execute();
		return $stmt->fetchAll();
	}
	public function GetRoom($Id){
		$Room = $this->Qurry("SELECT * FROM rooms WHERE id=?", $Id);
		return $Room;
	}
	public function GetPosts($RoomId){
		$stmt = $this->Conn->prepare("SELECT * FROM posts WHERE id REGEXP '^{$RoomId}-.+$' ORDER BY id DESC;");
		//$stmt->bindParam(':RoomId', $RoomId);
		$stmt->execute();
		return $stmt->fetchAll();
	}
}?>