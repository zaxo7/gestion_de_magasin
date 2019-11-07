<?php 
if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1; ?>
<div>
	<table id="table-list" align="center">
		<tr>
			<td><h1>Nom</h1></td>	
			<td><h1>Prenom</h1></td>
			<td><a href="index.php?add_chef"><button>Ajouter un chef de projet</button></a>
		</tr>
			<?php 
				//le flag c pour éviter la boucle infinie de redirection
				//si le flag != 0 alors la les variables ne sont pas chargées encore
				if($_SESSION['flag'] != 0)
				{
					//pour ne pas rentre la prochaine fois
					$_SESSION['flag'] = 0;
					//get_params pour conserver les paramétres get
					$_SESSION['get_params'] = $_GET;
					//referer c'est pour conserver le parametres + fichier
					$_SESSION['referer'] = 'index.php?list_chef';
					header('location:action.php?list_chef');
				}
				//parcour de la table chargée dans action.php
				else
				{
					foreach ($_SESSION['chef'] as $raw) {
						if($raw[0] != '')
							echo "<tr> <td><h1>$raw[1]</h1></td> <td><h1>$raw[2]</h1></td> <td> <a href='action.php?del_chef=$raw[0]'><button onclick='return confirm(\"Êtes-vous sûr ?\")'>supprimer</button></a></td></tr>";
					}
					//initialiser pour la prochaine fois
					$_SESSION['flag'] = 1;
					//supprimer les données chargée
					unset($_SESSION['chef']);
				}

			?>
	</table>
	<br><br>
	<a href="index.php?list_mag"><button>Retour</button></a>
</div>