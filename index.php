<?php
//démarer la session
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Gestion de magasin</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php
			//si logged =1 utilisateur connecté
			if(isset($_SESSION['logged']) && $_SESSION['logged'])
			{
		?>
		<div id="page">
			<nav class="main-nav" role="navigation">
				<div class="containertop">
								<ul>
									<li><a href="index.php?list_mag">Magazains</a></li>
									<li><a href="index.php?compte">Compte</a></li>
									<li><a href="action.php?logout">Déconnection</a></li>
								</ul>			
				</div>
			</nav>
			<div class="containerbottom">
				<br>
			<center>
				<?php
				// tester la présense de parametre get
				if (isset($_GET['list_mag']))
				{
					include('include/list_mag.php'); 
				}
				else if(isset($_GET['add_mag']))
				{
					include('include/add_mag.php');
				}
				else if(isset($_GET['list_FSF']))
				{
					include('include/list_fsf.php');
				}
				else if(isset($_GET['add_F']))
				{
					include('include/add_fsf.php');
				}
				else if(isset($_GET['add_SF']))
				{
					include('include/add_fsf.php');
				}
				else if(isset($_GET['list_art']))
				{
					include('include/list_art.php');
				}
				else if(isset($_GET['add_art']))
				{
					include('include/add_art.php');
				}
				else if(isset($_GET['add_four']))
				{
					include('include/add_four.php');
				}
				else if(isset($_GET['list_four']))
				{
					include('include/list_four.php');
				}
				else if(isset($_GET['in_stock']))
				{
					include('include/in_stock.php');
				}
				else if(isset($_GET['list_stock']))
				{
					include('include/list_stock.php');
				}
				else if(isset($_GET['compte']))
				{
					include('include/compte.php');
				}
				
				else
				{
					include('include/list_mag.php'); 
				}
				?>
				<br>
				</center>
				</div>
		</div>
		<?php 
			}
			//si l'utilisateur n'est pas connecté
			else
		    {
	    ?>
		<div class="frm" align="center">
			<?php
				//tester les parametres get
				if(isset($_GET['register']))
				{
			?>
			<h3><b>REGISTER</b></h3>
			<form action = "action.php?register" method="POST">
				<label>
					psuedo 
					<input type="text" name="psuedo" autofocus="" required>
				</label>
				<label>
					Nom
					<input type="text" name="nom" required> 
				</label>
				<label>
					Prenom
					<input type="text" name="pnom" required>
				</label>
				<label>
					email
					<input type="email" name="email" required>
				</label>
				<label>
					Date de naissance
					<input type="date" name="ddn" required>
				</label>
				<label>
					Mot de passe
					<input id="pass" type="password" name="password" required>
				</label>
				<label>
					confirmation
					<input id="cpass" type="password" name="cpassword" required>
				</label>
				<label>
					poste
					<input type="text" name="poste" required>
				</label>
				<input type="submit" name="register" value="Register" onclick="return pass_check('#pass','#cpass','register')">
			</form>
			<br><br>
			<a href="index.php?login"><button>Retour</button></a>
			<?php 
				}
				//mot de passe oublié
				else if(isset($_GET['forgot']))
				{ 
			?>
			<form action = "action.php?forgot" method="POST" >
			<label>email  <input type="text" name="email" autofocus=""></label>
			<input type="hidden" name="do" value="reset">
			<input align="center" type="submit" name="reset" value="Reset">
			</form>

			<?php 
				}
				//si les deux cookies sont présent il test le login avec leur valeurs en ajoutent le parm c
				else if (isset($_COOKIE['name']) && isset($_COOKIE['password']))
				{
					header('location:action.php?login&c');	
				}
				//affiche la page login
				else
				{
			?>

			<form action = "action.php?login" method="POST">
			<label>
				Psuedo
				<input type="text" name="name" autofocus="">
			</label>
			<label>
				Mot de passe
				<input type="password" name="password">
			</label>
			<input type="hidden" name="do" value="login">
			<label>
				mémoriser le mot de passe
				<input type="checkbox" name="mem">
			</label>
			<input type="submit" name="login" value="Login">
			</form>
			<br>
			<a href="index.php?register" style="padding-left: 2%;"><button>Register</button><a/>
			<br>
			<a href="index.php?forgot">Forgot account?<a/>
			
		</div>
		<?php 
			}
			}
			//ce fichier contient des alertes de status
			include('include/error.php');
		?>
		<script type="text/javascript" src="js/gp.js"></script>
	</body>
</html>