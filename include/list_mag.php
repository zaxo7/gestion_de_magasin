<?php 
	if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
	if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1; 
?>
			<br><br>
		<h2>Magasins</h2>
		<a href="index.php?add_mag"><button>Ajouter une affaire</button></a>
<table id="table-list" align="center">
	<tr>
		<td>
			<h2>Num</h2>
		</td>
		<td>
			<h2>Désignation</h2>
		</td>
		<td>
			<h2>Lieu</h2>
		</td>
		<td>
			<h2>Chef de projet</h2>
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
				/*foreach ($_GET as $key => $value) {
					$_SESSION['get_params'][$key] = $value;
				}*/
				$_SESSION['get_params'] = $_GET;


				unset($_SESSION['Mag']);

				//redirect
				header('location:action.php?list_mag');
			}
			else
			{
				//parcour des magasins
				foreach ($_SESSION['Mag'] as $raw) {
					if($raw[0] != '')
						echo "<tr> <td><h2>$raw[0]</h2></td> <td><h2>$raw[1]</h2></td> <td><h2>$raw[2]</h2></td> <td><h2>$raw[7] $raw[8]</h2></td> <td> <a href='index.php?list_stock=$raw[0]&mag=$raw[1]'><button>fiche stock</button></a><a href='action.php?del_mag=$raw[0]'><button onclick='return confirm(\"Êtes-vous sûr ?\")'>supprimer</button></a></td>";
				}

				$_SESSION['flag'] = 1;
			}

		?>
</table>