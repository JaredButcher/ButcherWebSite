<?php
class ConClass{
	private $Mod;
	
	public function __construct(){
		$GLOBALS['VD'] = array();
		require_once("../Models/ChatMod.php");
		$this->Mod = new ModClass();
	}
	public function Home(){
		$Title = "Butcher Chat";
		$GLOBALS['VD']['Rooms'] = $this->Mod->GetRooms();
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
						} else if(isset($_POST["DeletePost"]) && ($GLOBALS['Secrets']['PostDelPower'] <= $_SESSION["Power"] || $GLOBALS['VD']['Room']['Owner'] == $_SESSION['Id'] || $this->Mod->GetPost($_POST["DeletePost"])["owner"] == $_SESSION['Id'])) {
							$this->Mod->DelPost($_POST["DeletePost"]);
						}
					}else {
						header('Location: /Users/Login/',301);
						die();
					} 
				}
				$GLOBALS['VD']['Posts'] = $this->Mod->GetPosts($_GET['Id']);
			}
		}
	}
}
?>
	