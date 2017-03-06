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
	public function DelRoom($Id){
		$stmt = $this->Conn->prepare("Delete From rooms Where id = '{$Id}';");
		$stmt->execute();
		$stmt = $this->Conn->prepare("Delete From posts Where id REGEXP '^{$Id}-.+$';");
		$stmt->execute();
	}
	public function MakeReport($PostId, $RoomId){
	}
	public function DelReport($Id){
	}
	public function GetRoom($Id){
		$Room = $this->Qurry("SELECT rooms.*, users.username FROM rooms LEFT JOIN users ON rooms.owner = users.id WHERE rooms.id=?;", $Id);
		return $Room;
	}
	public function GetPost($Id){
		$Post = $this->Qurry("SELECT * FROM posts WHERE id=?;", $Id);
		return $Post;
	}
	public function GetPosts($RoomId, $Count, $CountIndex){
		$RoomId = '^'.$RoomId.'-.+$';
		$stmt = $this->Conn->prepare("SELECT COUNT(*) AS Total FROM posts LEFT JOIN users ON posts.owner = users.id WHERE posts.id REGEXP :Room;");
		$stmt->bindParam(':Room', $RoomId, PDO::PARAM_STR);
		$stmt->execute();
		$GLOBALS['VD']['Total'] = $stmt->fetch()['Total'];
		$stmt = $this->Conn->prepare("SELECT posts.*, users.username FROM posts LEFT JOIN users ON posts.owner = users.id WHERE posts.id REGEXP :Room ORDER BY posts.ts ASC LIMIT " . intval($Count) . " OFFSET " . intval($CountIndex * $Count) . ";");
		$stmt->bindParam(':Room', $RoomId, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	public function GetRoomsSearch($Search, $Count, $CountIndex){
		$stmt = $this->Conn->Prepare("SELECT COUNT(*) AS Total FROM rooms LEFT JOIN users ON rooms.owner = users.id WHERE rooms.name LIKE :Search;");
		$Search = '%'.$Search.'%';
		$stmt->bindParam(':Search', $Search, PDO::PARAM_STR);
		$stmt->execute();
		$GLOBALS['VD']['Total'] = $stmt->fetch()['Total'];
		$stmt = $this->Conn->Prepare("SELECT rooms.*, users.username FROM rooms LEFT JOIN users ON rooms.owner = users.id WHERE rooms.name LIKE :Search ORDER BY rooms.id ASC LIMIT " . intval($Count) . " OFFSET " . intval($CountIndex * $Count) . ";");
		$stmt->bindParam(':Search', $Search, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll();
	}
}?>