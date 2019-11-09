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
			

			
			if(isset($_GET[historique])) 
			{	
				$_SESSION['referer'] .= '&historique';
				$_SESSION['get_params'] = $_GET;

				header('location:action.php?list_stock=' . $_GET['list_stock'] . '&historique');
			}
			else
			{
				$_SESSION['referer'] = 'index.php?list_stock=' . $_GET['list_stock'] . '&mag=' . $_GET['mag'];
				$_SESSION['get_params'] = $_GET;
				header('location:action.php?list_stock=' . $_GET['list_stock']);
			}
				
		}
		//parcour de la table chargée dans action.php
		else
		{
	?>
		<h2>Fiche du stock de <?php echo $_GET['mag'] . '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp' . $_SESSION['Mag'][0][5] . '</h2>'; 
		echo "<form id='list_stock_form' action='action.php?trans_stock=" . $_GET['list_stock'] . "&mag=" . $_GET['mag'] . "' method='post'>";
		?>
		<table id="table-list">
		<tr>
		<?php  if(isset($_GET['historique'])) { ?>
		<td><h2>action</h2></td>
		<td><h2>Code</h2></td>	
		<td><h2>fournisseur</h2></td>	
		<input type="" name="" placeholder="">
		<td><h2>Date</h2></td>
		<?php }else {?>
		<td><h2>Code</h2></td>
		<td><h2>Désignation</h2></td>
		<td><h2>Famille</h2></td>
		<td><h2>Sous Famille</h2></td>
		<?php } ?>	
		<td><h2>Quantité</h2></td>
		<td><h2>Prix 
		<?php if(!isset($_GET['historique'])) echo ' moy'; echo '</h2></td><td><h2>Prix totale</h2></td>';
		if(!isset($_GET['historique']))
		{
		?>
		<td><a href="index.php?in_stock=<?php echo $_GET['list_stock']. '&mag=' . $_GET['mag']; ?>" ><button type="button" >Acheter</button><a href="index.php?list_stock=<?php echo $_GET['list_stock'] . '&mag=' . $_GET['mag']; ?>&historique"><button type="button">Historique</button></a><hr style="border-style: inset;border-width: 2px;width: 100%;padding: 0;" > <h2>Unitées à vendre</h2></td>
		<?php } ?>
		</tr>
		<?php
			if(!isset($_GET['historique']))
			{
				foreach ($_SESSION['stock'] as $raw) {
					if($raw[0] != '')
						echo "<tr> <td><h2>$raw[2]</h2></td> <td><h2>$raw[7]</h2></td>	<td><h2>$raw[8]</h2></td> <td><h2>$raw[9]</h2></td> <td><h2>$raw[4]</h2></td> <td><h2>$raw[5]</h2></td> <td><h2>$raw[6]</h2></td> <td style='width:150px;'> 
							<input type='number' name='qte_$raw[7]' value = '0' id='vendre_inp' required> <input type='hidden' name='id_art_$raw[7]' value='$raw[2]'></td>";
				}
				echo "</table>";
				if($_GET['list_stock'] != 1)
				{
					echo "<input id='entrer_btn' type='submit' name='entrer' value='entrer(affaire -> magasin centrale)' onclick='return hide_trans()' ><br>";
				}
				echo "<input id='transfert_btn'  type='submit' name='transfert' value='transfert(cet affaire->affaire)' onclick='return check_val(this)'><br><br>
				<input type='text' name='mag_dest' placeholder='Magasin destination' oninput='hide_entrer(this)'><br>
				<label id='imprim'><p>imprimer ?</p><input type='checkbox' name='imp'/></label>
				</form>";
			}
			else
			{
				foreach ($_SESSION['stock'] as $raw) {
					if($raw[0] != '')
					{
						echo '<tr>';
						if($raw['cod_four'] != 'x')
						{
							echo '<td><h2> achat </h2></td><td><h2>' . $raw['Cod_art'] . '</h2></td><td><h2>';
							foreach ($_SESSION['four'] as $four) {
								if($raw['cod_four'] == $four['Cod_four'])
								{
									echo $four['Nom'] . " " . $four['Prenom'] . '</h2></td>';
									break;
								}
							}
						}
						else	
							echo '<td><h2> vente </h2></td><td><h2>' . $raw['Cod_art'] . '</h2></td><td>X</td>';

						echo ' <td><h2>' . $raw['date_e'] .'</h2></td>	<td><h2>' . $raw['qte'] . ' </h2></td> <td><h2>' . $raw['pu'] . '<td><h2>' . $raw['pt'] . '</tr>';
					}
				}
				echo "</table>";
			}

			//supprimer les données chargée
			unset($_SESSION['stock']);
			//initialiser pour la prochaine fois
			$_SESSION['flag'] = 1;
		}

		?>
	
	
	<br><br>
	<a href="index.php?<?php if(isset($_GET['historique'])) echo 'list_stock=' . $_GET['list_stock'] . '&mag=' . $_GET['mag']; else echo 'list_mag';?>"><button>Retour</button></a>
</div>