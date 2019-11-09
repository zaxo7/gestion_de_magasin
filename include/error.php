<?php 
print_r($_SESSION['get_params']);
if(isset($_GET['ok']) || isset($_SESSION['get_params']['ok']))
{
	echo '<script>alert("succes")</script>';
	// unset($_SESSION['get_params']);
}
else if(isset($_GET['exists']) || isset($_SESSION['get_params']['exists']))
{
	echo '<script>alert("le code existe déja")</script>';
	// unset($_SESSION['get_params']);
}
else if(isset($_GET['error']) || isset($_SESSION['get_params']['error']))
{
	echo '<script>alert("erreur ';
	if(isset($_GET['error']))
		echo $_GET['error'] . '")</script>';
	else
		echo $_SESSION['get_params']['error'] . '")</script>';

	//unset($_SESSION['get_params']);
}

?>