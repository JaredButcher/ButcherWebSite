<?php
function call($Con, $Action){
	require_once("../Cons/" . $Con . "Con.php");
	$GLOBALS['Page'] = '../Views/'.$Con.'/'.$Action.'.php';
	$ConC = new ConClass();
	$ConC->{ $Action }();
	//If the call is for API then don't use the layout
	if(!$ConC->ApiCall){
		require_once($GLOBALS['Layout']);
	} else {
		require_once($GLOBALS['Page']);
	}
}
//List of possable pages
$Cons = array("Home" => ["Index", "Error", "About"], "Chat" => ["Home"], "Users" => ["Login","Register","Account","Logout","UserManagement","AccountAdmin"], "Chat" => ["Home", "MakeRoom", "Room", "GetRooms", "GetPosts"]);
if(array_key_exists($Con, $Cons)){
	if(in_array($Action, $Cons[$Con])){
		call($Con, $Action);
	} else {
		call("Home", "Error");
	}
} else {
	call("Home", "Error");
}
?>