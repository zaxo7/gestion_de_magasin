<?php if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php'); ?>
<div id="tab_list">
	<form action="action.php?add_art" method="post">
		<input type="text" name="nom" placeholder="Nom" required autofocus>
		<br>
		<label>famille<select id="F" name="F" onchange="giveSelection(this.value)" onshow="init_select()">
			<?php
			//si les variables familles et sous familles existe
			if(isset($_SESSION['F']) && isset($_SESSION['SF']))
			{
				//parcour les options pour select select
				foreach ($_SESSION['F'] as $F_raw) 
				{
					if($F_raw[0] != '')	
					{
						echo "<option value='$F_raw[0]'";
						echo ">$F_raw[1]</option>";
					}
				}

				include("include/error.php");
			}
			//si non il charge les deux dans action.php
			else
			{
				$_SESSION['get_params'] = $_GET;
				$_SESSION['referer'] = 'index.php?add_art';
				header('location:action.php?list_FSF');
			}
			?>
		</select></label>
		<br>
		<label>sous_famille<select id="SF" name="SF">
			<?php 
			//parcour pour chaque famille ses sous familles
			$i = 0;
			foreach ($_SESSION['SF'] as $famille) 
			{
				foreach ($famille as $SF_raw) 
				{
					if($SF_raw[0] != '')	
					{
						echo "<option data-option='" .  $_SESSION['F'][$i][0] . "' value='$SF_raw[0]'";
						echo ">$SF_raw[1]</option>";
					}
				}
				$i++;
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