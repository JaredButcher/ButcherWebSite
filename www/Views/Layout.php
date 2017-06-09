<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo($GLOBALS["Title"]);?></title>
	<link rel="stylesheet" 
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>
<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
					<span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand">Butcher</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/Home/About/">About</a></li>
					<li><a href="/Chat/Home/">Chat</a></li>
					<li><a href="http://butch.sytes.net:4243">Node</a></li>
                </ul>
				
				<ul class="nav navbar-nav navbar-right">
				<?php
				if(array_key_exists('Username', $_SESSION)){
					echo'<li class="dropdown">';
					echo 	"<a class=\"dropdown-toggle\" data-toggle=\"dropdown\">{$_SESSION['Username']}<b class=\"carret\"></b></a>";
					echo	'<ul class="dropdown-menu">';
					echo		'<li><a href="/Users/Account/">Account</a></li>';
					if($_SESSION['Power'] >= $GLOBALS['Secrets']['ManagePower']){
						echo 	'<li><a href="/Users/UserManagement/">Manage Users</a></li>';
					}
					echo		'<li><a href="/Users/Logout/">Logout</a></li>';
					echo	'</ul>';
					echo'</li>';
				} else {
					echo '<li><a href="/Users/Login/">Login</a></li>';
					echo '<li><a href="/Users/Register/">Register</a></li>';
				}
				?>
				</ul>
            </div>
        </div>
	</div>
	
	<div class="container body-content" style="margin-top:8%">
		<?php require_once($GLOBALS['Page']); ?>
	</div>


	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" 
	crossorigin="anonymous"></script>
</body>
</html>





