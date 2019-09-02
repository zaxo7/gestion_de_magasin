<?php 
if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1; 
?>
<div>
	<?php
		//le flag c pour éviter la boucle infinie de redirection
		//si le flag != 0 alors la les variables ne sont pas chargées encore
		if($_SESSION['flag'] != 0)
		{
			//pour ne pas rentre la prochaine fois
			$_SESSION['flag'] = 0;
			//referer c'est pour conserver le parametres + fichier
			if(!isset($_GET['historique']))
			$_SESSION['referer'] = 'index.php?list_stock=' . $_GET['list_stock'] . '&mag=' . $_GET['mag'];
			if(isset($_GET[historique])) 
			{	
				$_SESSION['referer'] .= '&historique';
				header('location:action.php?list_stock=' . $_GET['list_stock'] . '&historique');
			}
			else
				header('location:action.php?list_stock=' . $_GET['list_stock']);
		}
		//parcour de la table chargée dans action.php
		else
		{
	?>
	<h1>Fiche du stock de <?php echo $_GET['mag']; ?></h1>
	<table id="table-list">
		<tr>
		<?php if(isset($_GET['historique'])) { ?>
		<td><h1>action</h1></td>	
		<td><h1>fourniseur</h1></td>	
		<?php }else {?>
		<td><h1>Nom</h1></td>
		<?php } ?>	
		<td><h1>Code</h1></td>
		<td><h1>Date</h1></td>
		<td><h1>Quantité</h1></td>
		<td><h1>Prix <?php if(!isset($_GET['historique'])) echo ' moy'; echo '</h1></td>';
		if(!isset($_GET['historique']))
		{
		?>
		<td><a href="index.php?in_stock=<?php echo $_GET['list_stock']; ?>"><button>Acheter</button><a href="index.php?list_stock=<?php echo $_GET['list_stock'] . '&mag=' . $_GET['mag']; ?>&historique"><button>Historique</button></td>
		<?php } ?>
		</tr>
		<?php
			if(!isset($_GET['historique']))
			{
				foreach ($_SESSION['stock'] as $raw) {
					if($raw[0] != '')
						echo "<tr> <td><h1>$raw[6]</h1></td> <td><h1>$raw[7]</h1></td>	<td><h1>$raw[3]</h1></td> <td><h1>$raw[4]</h1></td> <td><h1>$raw[5]</h1></td> <td style='width:150px;'>
					<form id='inline-form' action='action.php?out_stock=" . $_GET['list_stock'] . "&mag=" . $_GET['mag'] ."' method='post'><input type='submit' value='vendre'><input type='number' name='qte' id='vendre_inp' required> unitées <input type='hidden' name='mag' value='$raw[1]'><input type='hidden' name='art' value='$raw[2]'>  </form></td>";
				}
			}
			else
			{
				foreach ($_SESSION['stock'] as $raw) {
					if($raw[0] != '')
					{
						if($raw['cod_four'] != 'x')
						{
							echo '<tr> <td><h1> achat </h1></td><td><h1>';
							foreach ($_SESSION['four'] as $four) {
								if($raw['cod_four'] == $four['Cod_four'])
								{
									echo $four['Nom'] . " " . $four['Prenom'] . '</h1></td>';
									break;
								}
							}
						}
						else	
							echo '<tr> <td><h1> vente </h1></td><td>X</td>';

						echo '<td><h1>' . $raw['Cod_art'] . '</h1></td> <td><h1>' . $raw['date_e'] .'</h1></td>	<td><h1>' . $raw['qte'] . ' </h1></td> <td><h1>' . $raw['pu'] . '</tr>';
					}
				}
			}

			//supprimer les données chargée
			unset($_SESSION['stock']);
			//initialiser pour la prochaine fois
			$_SESSION['flag'] = 1;
		}

		?>
	</table>
	<br><br>
	<a href="index.php?<?php if(isset($_GET['historique'])) echo 'list_stock=' . $_GET['list_stock'] . '&mag=' . $_GET['mag']; else echo 'list_mag';?>"><button>Retour</button></a>
</div>