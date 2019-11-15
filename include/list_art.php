<?php
	if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php'); 
	if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1;
?>
<div>
	<table id="table-list" align="center">
		<tr>
			<td>COD</td>
			<td>Désignation</td>
			<td>Famille</td>
			<td>Sous Famille</td>
			<td><a href='index.php?add_art'><button>ajouter</button></a></td>
		</tr>
			<?php
			//le flag c pour éviter la boucle infinie de redirection
			//si le flag != 0 alors la les articles ne sont pas chargées encore
			if($_SESSION['flag'] != 0)
			{
				//pour ne pas rentre la prochaine fois
				$_SESSION['flag'] = 0;
				//referer c'est pour conserver le parametres + fichier
				$_SESSION['referer'] = 'index.php?list_art';
				$_SESSION['get_params'] = $_GET;
				header('location:action.php?list_art');
			}
			else
			{
				//parcour de la table chargée dans action.php
				foreach ($_SESSION['art'] as $raw) {
					if($raw[0] != '')
						echo "<tr><td>$raw[0]</td><td>$raw[1]</td><td>$raw[3]</td><td>$raw[2]</td></td><td><a href='action.php?del_art=$raw[0]'><button onclick='return confirm(\"Êtes-vous sûr ?\")'>supprimer</button></a></td>";		
				}

				$_SESSION['flag'] = 1;
				//supprimer les données chargée
				unset($_SESSION['art']);

				include('include/error.php');
			}
			?>
	</table>
	<br><br>
	<a href="index.php?list_mag"><button>Retour</button></a>
</div>