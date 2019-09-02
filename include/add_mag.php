<?php if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');?>
<div id="add-mag-div">
	<form action="action.php?add_mag" method="post">
		<p>Lien du magasain : <input id="inp-mag" type="text" name="lien"></p>
		<input type="submit" value="ajouter">
	</form>
	<br><br>
	<a href="index.php?list_mag"><button>Retour</button></a>
</div>