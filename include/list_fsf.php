<?php
if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php'); 
if(!isset($_SESSION['flag'])) $_SESSION['flag']=1;
?>
<div id="fsf-list">
	<ul>
		<?php 
			/*
				le flag c pour éviter la boucle infinie de redirection
			*/
			if($_SESSION['flag'])
			{
				$_SESSION['flag'] = 0;
				$_SESSION['referer'] = 'index.php?list_FSF';
				header('location:action.php?list_FSF');
			}
			else
			{
				$i = 0;
				foreach ($_SESSION['F'] as $F_raw) 
				{
					if($F_raw[0] != '')
					{
						echo  '<li><b>' . $F_raw[2] . '<a href="action.php?del_F=' . $F_raw[0] . '">&nbsp&nbspX</a></b></li>	<ul>';
						foreach ($_SESSION['SF'][$i] as $SF_raw) 
						{
							if($SF_raw[2] != '')
								echo  '<li>' . $SF_raw[2] . '<a href="action.php?del_SF=' . $SF_raw[0] . '">&nbsp&nbspX</a></li>';
						}
						echo "<li><a href='index.php?add_SF&$F_raw[1]'>ajouter une sous famille</a></li>	</ul>";
						$i++;
					}
				}

				$_SESSION['flag'] = 1;
				unset($_SESSION['SF']);
				unset($_SESSION['F']);
			}
		?>
		<li><a href="index.php?add_F">ajouter une famille</a></li>
	</ul>
	<br><br>
	<a href="index.php?list_mag"><button>Retour</button></a>
</div>