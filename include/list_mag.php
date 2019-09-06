<?php 
	if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
	if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1; 
?>
<table id="table-list" align="center">
	<tr>
		<td>
			<h1>Lien</h1>
		</td>
		<td>
			<a href="index.php?add_mag"><button>Ajouter un Magasin</button></a>
			<a href='index.php?list_four'><button>fournisseurs</button><br>
			<a href='index.php?list_art'><button>articles</button></a>
			<a href='index.php?list_FSF'><button>familles</button></a>
		</td>
	</tr>
		<?php 
			//le flag c pour éviter la boucle infinie de redirection
			//si le flag != 0 alors la les variables ne sont pas chargées encore
			if($_SESSION['flag'])
			{
				//pour ne pas rentre la prochaine fois
				$_SESSION['flag'] = 0;
				//get_params pour conserver les paramétres get
				$_SESSION['get_params'] = $_GET;
				//redirect
				header('location:action.php?list_mag');
			}
			else
			{
				//parcour des magasins
				foreach ($_SESSION['Mag'] as $raw) {
					if($raw[0] != '')
						echo "<tr> <td><h1>$raw[1]</h1></td> <td> <a href='index.php?list_stock=$raw[0]&mag=$raw[1]'><button>fiche stock</button></a><a href='action.php?del_mag=$raw[0]'><button onclick='return confirm(\"Êtes-vous sûr ?\")'>supprimer</button></a></td>";
				}

				$_SESSION['flag'] = 1;
				unset($_SESSION['Mag']);
			}

		?>
</table>