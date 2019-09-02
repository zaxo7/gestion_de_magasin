<?php 
if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php'); 
if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1;
?>
<div>
		<h1>Ajouter Une 
		<?php
			/*
				tmp == 1 c'est pour la page de ajouter sous famille
				tmp == 0 pour ajouter une famille
			*/
			if(isset($_GET['add_SF']))
			{
				echo "Sous"; 
				$tmp = 1;
			}
			else
				$tmp = 0;

		?> 
		Famille</h1>

		<form action="action.php?add_<?php if($tmp) echo 'S'; ?>F" method="post">
			<label>Nom<input type="text" name="nom" required></label>
			<label>Cod<input type="text" name="code" required maxlength="<?php if($tmp) echo '2'; else echo '1'; ?>"></label>
			<?php 
				if($tmp)
				{
					echo "<label>famille<select name='famille'>";
					//si le flag != 0 charger les variables dans action.php
					if($_SESSION['flag'])
					{
						$_SESSION['flag'] = 0;
						//enrg les parametres get
						$_SESSION['get_params'] = $_GET;
						if($tmp)
							$_SESSION['referer'] = 'index.php?add_SF';
						else
							$_SESSION['referer'] = 'index.php?add_F';
						//redirection
						header('location:action.php?list_FSF');
					}
					else
					{
						//restorer la valeur
						$_SESSION['flag'] = 1;
						//parcour les lignes de familles
						foreach ($_SESSION['F'] as $F_raw) 
						{
							if($F_raw[0] != '')	
							{
								echo "<option value='$F_raw[1]'";
								if(isset($_SESSION['get_params'][$F_raw[1]])) echo 'selected';
								echo ">$F_raw[2]</option>";
							}
						}
						echo "</select></label>";
					}
				}
			?>
			<br>
			<input type="submit" value="ajouter">
		</form>
		<br><br>
		<a href="index.php?list_FSF"><button>Retour</button></a>
</div>