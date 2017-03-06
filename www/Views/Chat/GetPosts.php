<table>
<?php
	foreach($GLOBALS['VD']['Posts'] as $Post){
	    echo "<tr><td>{$Post["username"]}</td>";
	    echo "<td>{$Post["content"]}</td>";
	    echo "<td class = \"ts\">{$Post["ts"]}</td>";
        if( isset($_SESSION['Id']) && ($GLOBALS['Secrets']['PostDelPower'] <= $_SESSION["Power"] || $GLOBALS['VD']['Room']['owner'] == $_SESSION['Id'] || $Post['owner'] == $_SESSION['Id'])){
            echo "<td class = \"ts\"><button type=\"submit\" name=\"DeletePost\" value=\"{$Post['id']}\" onclick=\"submit();\">Delete</button></td></tr>";
        }
    }
?>
</table>
<div>
<?php
	$Count = $_POST['Count'];
	$Index = $_POST['CountIndex'];
	if($Index > 0){
		echo '<input type="button" name="CountDown" value="<" onclick="CountIndex-=1; GetPosts();">';
	}
	echo '<input style="width:40px;text-align:center;" value='.($Index + 1)."/".(ceil($GLOBALS['VD']['Total'] / $Count)).'>';
	if($GLOBALS['VD']['Total'] > $Count * ($Index + 1)){
		echo '<input type="button" name="CountUp" value=">" onclick="CountIndex+=1; GetPosts();">';
	}
?>
</div>