<?php 
if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1; ?>
<div>
	<table id="table-list" align="center">
		<tr>
			<td><h2>Nom</h2></td>	
			<td><h2>Prenom</h2></td>
			<td><h2>Adresse</h2></td>
			<td><a href="index.php?add_four"><button>Ajouter un Fournisseur</button></a>
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
					$_SESSION['referer'] = 'index.php?list_four';
					header('location:action.php?list_four');
				}
				//parcour de la table chargée dans action.php
				else
				{
					foreach ($_SESSION['four'] as $raw) {
						if($raw[0] != '')
							echo "<tr> <td><h2>$raw[1]</h2></td> <td><h2>$raw[2]</h2></td>  <td> <h2>$raw[3]</h2></td> <td> <a href='action.php?del_four=$raw[0]'><button onclick='return confirm(\"Êtes-vous sûr ?\")'>supprimer</button></a></td></tr>";
					}
					//initialiser pour la prochaine fois
					$_SESSION['flag'] = 1;
					//supprimer les données chargée
					unset($_SESSION['four']);


					include('include/error.php');

				}

			?>
	</table>
	<br><br>
	<a href="index.php?list_mag"><button>Retour</button></a>
</div>