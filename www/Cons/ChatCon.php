<?php
class ConClass{
	private $Mod;
	public $ApiCall = false;
	public function __construct(){
		$GLOBALS['VD'] = array();
		require_once("../Models/ChatMod.php");
		$this->Mod = new ModClass();
	}
	public function Home(){
		$Title = "Butcher Chat";
	}
	public function GetRooms(){
		$this->ApiCall = true;
		if(isset($_POST['Search']) && $_POST['Search'] != ""){
			if(isset($_POST['Count']) && isset($_POST['CountIndex'])){
				$GLOBALS['VD']['Rooms'] = $this->Mod->GetRoomsSearch($_POST['Search'], $_POST['Count'], max($_POST['CountIndex'], 0));
			} else {
				$GLOBALS['VD']['Rooms'] = $this->Mod->GetRoomsSearch($_POST['Search'], 10,0);
			}
		} else {
			if(isset($_POST['Count']) && isset($_POST['CountIndex'])){
				$GLOBALS['VD']['Rooms'] = $this->Mod->GetRoomsSearch('',$_POST['Count'], max($_POST['CountIndex'],0));
			} else {
				$GLOBALS['VD']['Rooms'] = $this->Mod->GetRoomsSearch('',10, 0);
			}
		}
	}
	public function GetPosts(){
		$this->ApiCall = true;
		if(isset($_POST['Count']) && isset($_POST['CountIndex'])){
			$GLOBALS['VD']['Posts'] = $this->Mod->GetPosts($_POST['Room'], $_POST['Count'], max($_POST['CountIndex'], 0));
		} else {
			$GLOBALS['VD']['Posts'] = $this->Mod->GetPosts($_POST['Room'], 10,0);
		}
	}
	public function MakeRoom(){
		if(array_key_exists("Id", $_SESSION)){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$this->Mod->MakeRoom(htmlspecialchars($_POST["Name"]),htmlspecialchars($_POST["Info"]));
				header('Location: /Chat/Home/',301);
				die();
			}
		} else {
			header('Location: /Users/Login/',301);
			die();
		}
	}
	public function Room(){
		if(isset($_GET['Id'])){
			$GLOBALS['VD']['Room'] = $this->Mod->GetRoom($_GET['Id']);
			if($GLOBALS['VD']['Room']){
				if($_SERVER['REQUEST_METHOD'] == "POST" ){
					if(array_key_exists("Id", $_SESSION)){
						if(isset($_POST["Content"])){
							$this->Mod->MakePost(htmlspecialchars($_GET['Id']),$_POST["Content"]);
						} else if(isset($_POST["DeletePost"]) && ($GLOBALS['Secrets']['PostDelPower'] <= $_SESSION["Power"] || $GLOBALS['VD']['Room']['owner'] == $_SESSION['Id'] || $this->Mod->GetPost($_POST["DeletePost"])["owner"] == $_SESSION['Id'])) {
							$this->Mod->DelPost($_POST["DeletePost"]);
						} else if (isset($_POST["DeleteRoom"])){
							$GLOBALS['VD']['Room'] = $this->Mod->GetRoom($_POST["DeleteRoom"]);
							if($GLOBALS['Secrets']['RoomDelPower'] <= $_SESSION["Power"] || $GLOBALS['VD']['Room']['owner'] == $_SESSION['Id']){
								$this->Mod->DelRoom($GLOBALS['VD']['Room']['id']);
								header('Location: /Chat/Home/',301);
								die();
							}
						}
					}else {
						header('Location: /Users/Login/',301);
						die();
					} 
				}
			}
		}
	}
}
?>
	