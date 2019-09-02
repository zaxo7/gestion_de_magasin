<?php 
if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
if(isset($_GET['ok']) || isset($_SESSION['get_params']['ok']))
{
	echo '<script>alert("succes")</script>';
	unset($_SESSION['get_params']);
}
else if(isset($_GET['exists']) || isset($_SESSION['get_params']['exists']))
{
	echo '<script>alert("le code existe déja")</script>';
	unset($_SESSION['get_params']);
}
else if(isset($_GET['error']) || isset($_SESSION['get_params']['error']))
{
	echo '<script>alert("erreur XD")</script>';
	unset($_SESSION['get_params']);
}

?>