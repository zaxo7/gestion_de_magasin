<?php if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');?>
<div>
	<form action="action.php?add_chef" method="post">
		<br>
		<label>Nom<input type="text" name="nom" required></label>
		<label>Prenom<input type="text" name="prenom" required></label>
		<input type="submit" value="ajouter">
	</form>
	<br><br>
	<a href="index.php?list_chef"><button>Retour</button></a>
</div>