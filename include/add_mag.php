<?php if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1;?>
<div id="add-mag-div">
	<form action="action.php?add_mag" method="post">
		<label>Affaire<input type="text" name="aff" autofocus></label>
		<label>lieu<input type="text" name="lieu"></label>
		<br>
		<b>Chef Du Projet</b>
		<label>Nom<input type="text" name="Nom"></label>
		<label>Prenom<input type="text" name="Prenom"></label>

		<input type="submit" value="ajouter">
	</form>
	<br><br>
	<a href="index.php?list_mag"><button>Retour</button></a>
</div>