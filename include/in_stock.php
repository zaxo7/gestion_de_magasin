<?php
if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1; 
?>
<div>
	<form action="action.php?in_stock=<?php echo $_GET['in_stock']; ?>" method="POST">
		<p>article :<select name="art">
			<?php 
				//le flag c pour éviter la boucle infinie de redirection
				//si le flag != 0 alors la les variables ne sont pas chargées encore
				if($_SESSION['flag'] != 0)
				{
					//pour ne pas rentre la prochaine fois
					$_SESSION['flag'] = 0;
					//referer c'est pour conserver le parametres + fichier
					$_SESSION['referer'] = 'index.php?in_stock=' . $_GET['in_stock'] . '&mag=' . $_GET['mag'];
					header('location:action.php?list_art_four');
				}
				//parcour de la table chargée dans action.php
				else
				{
					$_SESSION['flag'] = 1;

					foreach ($_SESSION['art'] as $art) {
						if($art[0] != '')
							echo "<option value='$art[0]'>$art[2]</option>";
					}
				}
			?>
		</select></p>
		<p>fournisseur:<select name="four">
			<?php 
				//parcour de la table chargée dans action.php
				foreach ($_SESSION['four'] as $raw) {
					if($raw[0] != '')
						echo "<option value='$raw[0]'>$raw[1] $raw[2]</option>";
				}
				//initialiser pour la prochaine fois
				unset($_SESSION['art']);
				unset($_SESSION['four']);

			?>
		</select></p>
		<p>quantité :<input type="number" name="qte"></p>
		<p>Prix U :<input type="number" name="pu" step="0.01"></p>
		<input type="hidden" name="mag" value="<?php echo $_GET['in_stock']; ?>">
		<br>
		<input type="submit" value="ajouter">
		<br>
	</form>
		<a href="index.php?<?php echo 'list_stock=' . $_GET['in_stock'] . '&mag=' . $_GET['mag'];?>"><button>Retour</button></a>
</div>