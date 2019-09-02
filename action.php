<?php
	if(isset($_SESSION['logged']) && !$_SESSION['logged']) header('location:index.php');
	session_start();
	//bdd connect
	$bdd = new PDO('mysql:host=localhost;dbname=gp;charset=utf8', 'zaxo7', 'ZX2019m', array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	//adding
	//ajouter magasin
	if(isset($_GET['add_mag']))
	{
		if(isset($_POST['lien']))
		{
			$ans = $bdd->prepare("INSERT INTO Mag (lien) VALUES (?)");
			if($ans->execute(array($_POST['lien'])))
				header('location:index.php?list_mag');
			else
				header('location:index.php?list_mag&error');
		}
	}
	//ajouter une famille
	else if(isset($_GET['add_F']))
	{
		//prepare c pour éviter les injections
		//tester si le code de famille existe
		$ans = $bdd->prepare('SELECT Id_F FROM F where Cod_F = ?');
		$ans->execute(array(strtoupper($_POST['code'])));
		//extraire une ligne
		$i = $ans->fetch();
		//si n y a pas de résultat = le code n'existe pas
		if($i[0] != NULL)
			header('location:index.php?add_F&exists');
		//insertion
		else
		{
			$ans = $bdd->prepare('INSERT INTO F (Nom_F,Cod_F) VALUES (?,?)');
			if($ans->execute(array($_POST['nom'],strtoupper($_POST['code']))))
				header('location:index.php?add_F&ok');
			else
				header('location:index.php?add_F&error');
		}
	}
	//ajouter sous famille
	else if(isset($_GET['add_SF']))
	{
		//tester lea famille si il existe
		$ans = $bdd->prepare('SELECT id_f FROM F WHERE cod_F = ?');
		$ans->execute(array(strtoupper($_POST['famille'])));

		$t = $ans->fetch();

		//si elle existe
		if($t[0] != NULL)
		{
			//tester le code de sous famille si il existe deja
			$ans = $bdd->prepare("SELECT Id_SF FROM SF where Cod_SF = ? and Id_F = $t[0]");
			if(!$ans->execute(array(strtoupper($_POST['code']))))
				header('location:index.php?add_SF&error');


			$i = $ans->fetch();
			//si elle existe
			if($i[0] != NULL)
				header('location:index.php?add_SF&exists');
			//n'existe pas
			else
			{
				//on ajoute la sous famille
				$ans = $bdd->prepare('INSERT INTO SF (Nom_SF,Cod_SF,Id_F) VALUES (?,?,?)');
				if($ans->execute(array($_POST['nom'],strtoupper($_POST['code']),$t[0])))
					header('location:index.php?add_SF&ok');
				else
					header('location:index.php?add_SF&error');
			}
		}
		//n'existe pas
		else
		{
			header('location:index.php?add_SF&error');
		}

	}
	//ajouter article
	else if(isset($_GET['add_art']))
	{
		$ans = $bdd->prepare('call codif_art(?,?,?)');
		if($ans->execute(array($_POST['F'],$_POST['SF'],$_POST['nom'])))
			header('location:index.php?add_art&ok');
		else
			header('location:index.php?add_art&error');

	}
	//ajouter fournisseur
	else if(isset($_GET['add_four']))
	{
		$ans = $bdd->prepare('INSERT INTO fourniseur (nom,prenom,adresse) VALUES (?,?,?)');
		if($ans->execute(array($_POST['nom'],$_POST['prenom'],$_POST['adresse'])))
			header('location:index.php?add_four&ok');
		else
			header('location:index.php?add_four&error');

	}
	//acheter 
	else if(isset($_GET['in_stock']))
	{
		$ans = $bdd->prepare('call in_stock(?,?,?,?,?)');
		if($ans->execute(array($_POST['mag'],$_POST['art'],$_POST['four'],$_POST['qte'],$_POST['pu'])))
			header('location:index.php?list_stock=' . $_POST['mag'] . '&ok');
		else
			header('location:index.php?list_stock&error');
	}
	
	//deleting
	//supprimer un magasin
	else if(isset($_GET['del_mag']))
	{
		$ans = $bdd->prepare("DELETE FROM mag WHERE cod_mag = ?");
		if($ans->execute(array($_GET['del_mag'])))
			header('location:index.php?list_mag&ok');
		else
			header('location:index.php?list_mag$error');
	}
	//supprimer un fournisseur
	else if(isset($_GET['del_four']))
	{
		$ans = $bdd->prepare("DELETE FROM fourniseur WHERE cod_four = ?");
		if($ans->execute(array($_GET['del_four'])))
			header('location:index.php?list_four&ok');
		else
			header('location:index.php?list_four&error');
	}
	//supprimer un article
	else if(isset($_GET['del_art']))
	{
		$ans = $bdd->prepare("DELETE FROM article WHERE id_art = ?");
		if($ans->execute(array($_GET['del_art'])))
			header('location:index.php?list_art&ok');
		else
			header('location:index.php?list_art&error');
	}
	//vendre
	else if(isset($_GET['out_stock']))
	{
		$ans = $bdd->prepare("call out_stock(?,?,?)");
		if($ans->execute(array($_POST['mag'],$_POST['art'],$_POST['qte'])))
			header('location:index.php?list_stock=' . $_GET['out_stock'] . '&mag=' . $_GET['mag'] . '&ok');
		else
			header('location:index.php?list_stock=' . $_GET['out_stock'] . '&mag=' . $_GET['mag'] . '&error');
	}

	//listing
	//lister les magasins
	else if(isset($_GET['list_mag']))
	{
		$ans = $bdd->query('SELECT * FROM Mag');
		$i = 0;
		//charger le résultat dans la variable globale
		while($_SESSION['Mag'][$i++] = $ans->fetch());
		header('location:index.php?list_mag');
	}

	else if(isset($_GET['list_FSF']))
	{
		//trouver les familles
		$ans1 = $bdd->query('SELECT * FROM F');
		
		$i = 0;
		while($_SESSION['F'][$i++] = $ans1->fetch());
		
		//extraire les sous familles de chaque famille
		$j = 0;
		foreach ($_SESSION['F'] as $FAMILLE)
		{
			$ans2 = $bdd->prepare('SELECT * FROM SF WHERE id_F = ?');
			$ans2->execute(array($_SESSION['F'][$j][0]));
			$i = 0;
			while($_SESSION['SF'][$j][$i++] = $ans2->fetch());
			$j++;
		}

		header("location:" . $_SESSION['referer']);		
	}
	//lister les articles
	else if(isset($_GET['list_art']))
	{
		$ans = $bdd->query('select article.id_art,article.cod_art,article.nom,sf.nom_sf,f.nom_f From article INNER JOIN SF ON SF.Id_SF =  article.Id_sf INNER JOIN F ON F.id_F = SF.Id_F');
		$i = 0;
		while($_SESSION['art'][$i++] = $ans->fetch());

		header('location:' . $_SESSION['referer']);
	}
	//lister les fourniseurs
	else if(isset($_GET['list_four']))
	{
		$ans = $bdd->query('SELECT cod_four,nom,prenom,adresse FROM fourniseur');
		$i = 0;
		while($_SESSION['four'][$i++] = $ans->fetch());

		header('location:' . $_SESSION['referer']);
	}
	//lister les fourniseurs et les articles
	else if(isset($_GET['list_art_four']))
	{
		$ans = $bdd->query('SELECT cod_four,nom,prenom,adresse FROM fourniseur');
		$i = 0;
		while($_SESSION['four'][$i++] = $ans->fetch());

		$ans = $bdd->query('select article.id_art,article.cod_art,article.nom,sf.nom_sf,f.nom_f From article INNER JOIN SF ON SF.Id_SF =  article.Id_sf INNER JOIN F ON F.id_F = SF.Id_F');
		$i = 0;
		while($_SESSION['art'][$i++] = $ans->fetch());

		header('location:' . $_SESSION['referer']);
	}
	//lister le stock
	else if(isset($_GET['list_stock']))
	{
		
		$ans = $bdd->prepare(' SELECT mag.lien,fiche_stock.cod_mag,fiche_stock.id_art,fiche_stock.date_e,fiche_stock.qte,fiche_stock.pu,article.nom,article.cod_art FROM fiche_stock INNER JOIN mag ON fiche_stock.cod_mag = mag.cod_mag INNER JOIN article ON fiche_stock.id_art = article.id_art WHERE fiche_stock.cod_mag = ?');
		$ans->execute(array($_GET['list_stock']));

		$i = 0;
		while($_SESSION['stock'][$i++] = $ans->fetch());

		header('location:' . $_SESSION['referer']);

	}




//accounts 

	else if(isset($_GET['register']))
	{
		$ans = $bdd->prepare('INSERT INTO users (nom,prenom,psuedo,email,ddn,password,poste) VALUES (?,?,?,?,?,?,?)');
		if($ans->execute(array($_POST['nom'],$_POST['pnom'],$_POST['psuedo'],$_POST['email'],$_POST['ddn'],$_POST['password'],$_POST['poste'])))
			header('location:index.php?');
		else
			header('location:index.php?register&erreur');
	}
	else if(isset($_GET['login']))
	{
		$ans = $bdd->prepare('SELECT id_user FROM users WHERE psuedo = ? AND password = ?');
		if(isset($_GET['c']))
			$ans->execute(array($_COOKIE['name'],$_COOKIE['password']));
		else
			$ans->execute(array($_POST['name'],$_POST['password']));

		if($ans->fetch() != '')
		{
			$_SESSION['logged'] = 1;
			if(isset($_POST['mem']))
			{
				setcookie('name',$_POST['name'],-1);
				setcookie('password',$_POST['password'],-1);
			}
			header('location:index.php?list_mag');
		}
		else
		{
			header('location:index.php?error');
		}
	}
	else if(isset($_GET['logout']))
	{
		$_SESSION['logged'] = 0;
		unset($_SESSION['logged']);
		setcookie('name','',0);
		setcookie('password','',0);
		header('location:index.php');
	}

?>