<?php 
$s = false;
if(isset($_GET['ok']) || isset($_SESSION['get_params']['ok']))
{
	echo '<script>alert("succes")</script>';
	$s = true;
	//unset($_SESSION['get_params']);
}
else if(isset($_GET['error']) || isset($_SESSION['get_params']['error']))
{
	echo '<script>alert("erreur :';
	if(isset($_GET['error']))
		echo $_GET['error'] . '")</script>';
	else
		echo $_SESSION['get_params']['error'] . '")</script>';

	$s = true;
	//unset($_SESSION['get_params']);
}
if($s)
{
	echo "deleted";
	unset($_SESSION['get_params']);
}
//print_r($_SESSION['get_params']);	
//echo "nothing";
?>