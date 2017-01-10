<?php
require_once("../Secrets.php");
session_start();
$VD = array();
$Title = "Butcher";
$Layout = "../Views/Layout.php";
$Page = '../Views/Home/Index.php';

if(isset($_GET["Con"]) && isset($_GET["Action"])){
		$Con = $_GET["Con"];
		$Action = $_GET["Action"];
} else {
	$Con = "Home";
	$Action = "Index";
}
if(!isset($_SESSION['Id']) && isset($_COOKIE["Remember"])){
	require_once("../Models/UsersMod.php");
	$Mod = new ModClass();
	$Mod->CheckRemember($_COOKIE["Remember"]);
}

require_once("../Routes.php");
?>