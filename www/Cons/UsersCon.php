<?php class ConClass{
	private $Mod;
	public $ApiCall = false;
	public function __construct(){
		//Initalize variables that might be used
		require_once("../Models/UsersMod.php");
		$this->Mod = new ModClass();
	}
	public function Login(){
		//Initalizeing variables that might be used in view
		$GLOBALS['VD'] = array("Sucess" => "", "Username" => "");
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
		$GLOBALS['VD'] = array("Sucess" => "", "Username" => "", "EUsername" => "","EPassword" => "","EEmail" => "","Cap" => "","Email" => "","Name" => "");
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$Username = htmlspecialchars($_POST["Username"]);
			$Password = htmlspecialchars($_POST["Password"]);
			$Email = htmlspecialchars($_POST["Email"]);
			$Name = htmlspecialchars($_POST["Name"]);
			$Good = true;
			//Validating input
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
			//Checking captcha useing google recaptcha
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
			//Creating new user
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
		$GLOBALS['VD'] = array("ENewPassword" => "", "EUsername" => "", "EPassword" => "", "EName" => "", "EEmail" => "");
		//If not loged on then redirect to the home page, else take to page to change account info
		if($_SESSION['Username'] == ""){
			header('Location: /',301);
			die();
		} else {
			//If post then change account info
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				//Reauthencate user useing renetered password
				if($this->Mod->Login($_SESSION['Username'],htmlspecialchars($_POST["Password"]))){
					$Username = htmlspecialchars($_POST["Username"]);
					$Password = htmlspecialchars($_POST["NewPassword"]);
					$Email = htmlspecialchars($_POST["Email"]);
					$Name = htmlspecialchars($_POST["Name"]);
					//Verify input and edit user
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
					//Relogin user
					$this->Mod->Login($Username,$Password);
				} else {
					$GLOBALS['VD']['EPassword'] = "* Incorrect Password";
				}
			}
		}
	}
	public function Logout(){
		//Remove remember cookie and clear session then redirect to login page
		setcookie("Remember", "", time() - 3600, "/");
		session_unset();
		header('Location: /Users/Login/',301);
		die();
	}
	//Admin view list of users
	public function UserManagement(){
		$GLOBALS['VD'] = array("Users" => "");
		$this->Mod->ReloadLogin($_SESSION['Id']);
		$Filter = "";
		//Make sure user has administator power and is loged in
		if($_SESSION["Power"] >= $GLOBALS['Secrets']['ManagePower']){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(isset($_POST['Filter'])){
					//TODO make users searchable
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
	//Admin editing user
	public function AccountAdmin(){
		$GLOBALS['VD'] = array("Username" => "", "Name" => "", "Email" => "", "Power" => "", "Id" => "", "ENewPassword" => "", "EUsername" => "");
		$this->Mod->ReloadLogin($_SESSION['Id']);
		if($_SESSION["Power"] >= $GLOBALS['Secrets']['ManagePower']){
			//Make sure id is valid and not for main admin account
			if(isset($_GET['Id']) && $_GET['Id'] != 1){
				//Make sure user exist
				$User = $this->Mod->GetInfo($_GET['Id']);
				if($User != false){
					$GLOBALS['VD']['Username'] = $User['username'];
					$GLOBALS['VD']['Name'] = $User['name'];
					$GLOBALS['VD']['Email'] = $User['email'];
					$GLOBALS['VD']['Power'] = $User['power'];
					$GLOBALS['VD']['Id'] = $User['id'];
					//If post then edit info
					if($_SERVER['REQUEST_METHOD'] == "POST"){
						$Username = htmlspecialchars($_POST["Username"]);
						$Password = htmlspecialchars($_POST["NewPassword"]);
						$Email = htmlspecialchars($_POST["Email"]);
						$Name = htmlspecialchars($_POST["Name"]);
						$Power = intval($_POST["Power"]);
						//Validate input and edit info
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
						if($Power != "" && $Power < $_SESSION["Power"] && $User['power'] < $_SESSION["Power"]){
							$this->Mod->EditPower($User['id'],$Power);
							$GLOBALS['VD']['Power'] = $Power;
						}
					} 
				}else {
				header('Location: /Users/UserManagement/',301);
				die();
				}
			} else {
				header('Location: /Users/UserManagement/',301);
				die();
			}
		}
	}
}
?>