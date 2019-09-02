<?php if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php'); ?>
<div id="tab_list">
	<form action="action.php?add_art" method="post">
		<input type="text" name="nom" placeholder="Nom">
		<br>
		<label>famille<select name="F">
			<?php
			//si les variables familles et sous familles existe
			if(isset($_SESSION['F']) && isset($_SESSION['SF']))
			{
				//parcour les options pour select select
				foreach ($_SESSION['F'] as $F_raw) 
				{
					if($F_raw[0] != '')	
					{
						echo "<option value='$F_raw[1]'";
						echo ">$F_raw[2]</option>";
					}
				}
			}
			//si non il charge les deux dans action.php
			else
			{
				$_SESSION['referer'] = 'index.php?add_art';
				header('location:action.php?list_FSF');
			}
			?>
		</select></label>
		<br>
		<label>sous_famille<select name="SF">
			<?php 
			//parcour pour chaque famille ses sous familles
			foreach ($_SESSION['SF'] as $famille) 
			{
				foreach ($famille as $SF_raw) 
				{
					if($SF_raw[0] != '')	
					{
						echo "<option value='$SF_raw[1]'";
						echo ">$SF_raw[2]</option>";
					}
				}
			}
			//suppression de variables
			unset($_SESSION['SF']);
			unset($_SESSION['F']);
			?>
		</select></label>
		<input type="submit" value="ajouter">
	</form>
	<br><br>
	<a href="index.php?list_art"><button>Retour</button></a>
</div>