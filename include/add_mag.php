<?php if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1;?>
<div id="add-mag-div">
	<form action="action.php?add_mag" method="post">
		<label>Affaire<input type="text" name="aff"></label>
		<label>lieu<input type="text" name="lieu"></label>
		<label>chef<select name="chef">
			<?php 

				if($_SESSION['flag'])
				{
					$_SESSION['flag'] = 0;

					$_SESSION['referer'] = 'index.php?add_mag';
					//redirection
					header('location:action.php?list_chef');
				}
				else
				{
					//restorer la valeur
					$_SESSION['flag'] = 1;
					//parcour les lignes de familles
					foreach ($_SESSION['chef'] as $chef_row) 
					{
						if($chef_row[0] != '')	
						{
							echo "<option value='$chef_row[0]'";
							echo ">$chef_row[1] $chef_row[2]</option>";
						}
					}
				}
			?>
		</select></label>
		<input type="submit" value="ajouter">
	</form>
	<br><br>
	<a href="index.php?list_mag"><button>Retour</button></a>
</div>