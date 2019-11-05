<?php
	if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
	if(!isset($_SESSION['flag'])) $_SESSION['flag'] = 1;
?>
<div>
	<?php
		echo '<p>Salut ' . $_SESSION['prenom'] . '</p>';
		echo '<p>Nom : ' . $_SESSION['nom'] . '</p>';
		echo '<p>Prenom : ' . $_SESSION['prenom'] . '</p>';
		if(isset($_GET['change']))
		{
	?>
	<form action="action.php?edit_pass" method="post">
		<label>
			ancien mot de passe
			<input type="password" name="ancien">
		</label>
		<label>
			neveaux mot de passe
			<input id="pass" type="password" name="nouv" oninput="conf()">
		</label>
		<input type="submit" value="changer" onclick="return pass_check('#pass','#cpass','compte&change')">
	</form>
	<?php 
		}
		else
		{
			echo '<a href="index.php?compte&change"><button>changer le mot de passe</button></a>';
		}
		if($_SESSION['status'] == 0)
		{
			if(!isset($_GET['userm']))
				echo '<br><a href="index.php?compte&userm=-1"><button>gestion des utilisateurs</button></a>';
			else
			{
	?>
	<br><a href="index.php?compte&userm=-1"><button>non acepté</button></a>
	<a href="index.php?compte&userm=1"><button>utilisateurs simple</button></a>
	<a href="index.php?compte&userm=0"><button>admins</button></a>
	<table id="table-list" align="center">
	<tr>
		<td>
			<h1>psuedo</h1>
		</td>
		<td>
			<h1>nom</h1>
		</td>
		<td>
			<h1>prenom</h1>
		</td>
		<td>
			<h1>poste</h1>
		</td>
		<td>
			<h1>email</h1>
		</td>
		<td>
			
		</td>
	</tr>
	<?php
			if($_SESSION['flag'])
			{
				$_SESSION['flag'] = 0;
				$_SESSION['referer'] = 'index.php?compte&userm=';
				header('location:action.php?list_users=' . $_GET['userm']);
			}
			else
			{
				$_SESSION['flag'] = 1;
				foreach ($_SESSION['users'] as $user) 
				{
					if($user[0] != '')
						echo "<tr><td>$user[3]</td><td>$user[1]</td><td>$user[2]</td><td>$user[7]</td><td>$user[4]</td></td><td><a href='action.php?del_user=$user[0]&userm=" . $_GET['userm'] . "'><button onclick='return confirm(\"Êtes-vous sûr ?\")'>supprimer</button></a><a href='action.php?upg_user=" . $user[0] . "&userm=" . $_GET['userm'] . "'><button>accepter</button></a><a href='action.php?upg_user=" . $user[0] . "&userm=" . $_GET['userm'] . "&a'><button onclick='return confirm(\"Êtes-vous sûr ?\")'>make admin</button></a></td>";	
				}
				unset($_SESSION['users']);
			}
			}
		}

	?>
</div>