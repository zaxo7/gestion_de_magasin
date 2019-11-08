CREATE TABLE chef_proj
(
	Cod_chef INT(3) PRIMARY KEY AUTO_INCREMENT,
	Nom CHAR(30) NOT NULL,
	Prenom CHAR(30) NOT NULL
);

CREATE TABLE Mag 
(
	Cod_mag INT(3) PRIMARY KEY AUTO_INCREMENT,
	affaire CHAR(100) NOT NULL,
	lieu char(30) NOT NULL,
	client INT(3) NOT NULL,
	ChefProjet INT(3) NOT NULL,
	date_cr DATE NOT NULL,
	FOREIGN KEY (ChefProjet) REFERENCES chef_proj (Cod_chef) ON DELETE CASCADE

);


CREATE TABLE Fourniseur
(
	Cod_four INT(4) PRIMARY KEY AUTO_INCREMENT,
	Nom CHAR(20) NOT NULL,
	Prenom CHAR(20) NOT NULL,
	Adresse CHAR(30) NOT NULL
);

CREATE TABLE F
(
	Cod_F char(1) PRIMARY KEY,
	Desig_F CHAR(15) NOT NULL
);

CREATE TABLE SF
(
	Cod_SF CHAR(2) PRIMARY KEY,
	Desig_SF CHAR(15) NOT NULL,
	Cod_F CHAR(1) NOT NULL,
	FOREIGN KEY (cod_f) REFERENCES f (cod_f) ON DELETE CASCADE 
);

CREATE TABLE Article
(
	#Id_art INT(5) AUTO_INCREMENT PRIMARY KEY,
	Cod_art CHAR(6) PRIMARY KEY,
	num_art INT(3),
	Desig_art CHAR(30) NOT NULL,
	COD_SF CHAR(2) NOT NULL,
	FOREIGN KEY (cod_SF) REFERENCES SF(cod_SF) ON DELETE CASCADE
);

CREATE TABLE Fiche_stock
(
	Cod_mag INT(3) NOT NULL,
	Cod_art CHAR(6) NOT NULL,
	Date_e DATETIME NOT NULL,
	Qte INT(4) UNSIGNED NOT NULL,
	Pu FLOAT UNSIGNED NOT NULL,
	Pt FLOAT UNSIGNED NOT NULL,
	FOREIGN KEY (Cod_mag) REFERENCES Mag(Cod_mag) ON DELETE CASCADE,
	FOREIGN KEY (Cod_art) REFERENCES Article(Cod_art) ON DELETE CASCADE,
	PRIMARY KEY(Cod_mag,Cod_art)
);

CREATE TABLE sortie
(
	Cod_mag INT(3) NOT NULL,
	Cod_art CHAR(6) NOT NULL,
	Date_s DATETIME NOT NULL,
	Qte INT(4) UNSIGNED NOT NULL,
	Pu FLOAT UNSIGNED NOT NULL,
	Pt FLOAT UNSIGNED NOT NULL,
	FOREIGN KEY (Cod_mag) REFERENCES Mag(Cod_mag) ON DELETE CASCADE,
	FOREIGN KEY (Cod_art) REFERENCES Article(Cod_art) ON DELETE CASCADE,
	PRIMARY KEY(Cod_mag,Cod_art,Date_s)
);

CREATE TABLE entrer
(
	Cod_mag INT(3) NOT NULL,
	Cod_art CHAR(6) NOT NULL,
	Cod_four INT(4) NOT NULL,
	Date_e DATETIME NOT NULL,
	Qte INT(4) UNSIGNED NOT NULL,
	Pu FLOAT UNSIGNED NOT NULL,
	Pt FLOAT UNSIGNED NOT NULL,
	FOREIGN KEY (Cod_mag) REFERENCES Mag(Cod_mag) ON DELETE CASCADE,
	FOREIGN KEY (Cod_art) REFERENCES Article(Cod_art) ON DELETE CASCADE,
	FOREIGN KEY (Cod_four) REFERENCES Fourniseur(Cod_four) ON DELETE CASCADE,
	PRIMARY KEY(Cod_mag,Cod_art,Cod_four,Date_e)
);


CREATE TABLE users
(
	id_user INT(4) PRIMARY KEY AUTO_INCREMENT,
	nom CHAR(20) NOT NULL ,
	prenom CHAR(20) NOT NULL,
	psuedo CHAR(15) NOT NULL,
	email CHAR(20) NOT NULL,
	ddn DATE,
	password CHAR(30) NOT NULL,
	poste CHAR(30) NOT NULL,
	status INT(1) DEFAULT -1
);


DELIMITER //
CREATE PROCEDURE in_stock (IN p_cod_mag INT(3), IN p_cod_art CHAR(6), IN p_cod_four INT(4), IN p_qte INT(4),IN p_pu FLOAT)
BEGIN
	IF p_qte > 0
	THEN
		INSERT INTO entrer (Cod_mag,Cod_art,cod_four,Date_e,Qte,Pu,pt) VALUES (p_cod_mag,p_cod_art,p_cod_four,NOW(),p_qte,p_pu,(p_qte * p_pu));
		IF EXISTS(SELECT cod_mag FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art)
		THEN
			SELECT DISTINCT pu,qte INTO @pum,@qte FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art;
			SET @pum = ((@pum * @qte) + (p_pu * p_qte))/(@qte+p_qte);
			SET @ptm = @pum * (@qte + p_qte);
			UPDATE Fiche_stock SET Qte = Qte + p_qte , pu = @pum, pt = @ptm WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art;
		ELSE
			INSERT INTO Fiche_stock (Cod_mag,Cod_art,Date_e,Qte,Pu,pt) VALUES (p_cod_mag,p_cod_art,NOW(),p_qte,p_pu,(p_qte * p_pu));
		END IF;

	END IF;
END//
DELIMITER ;

DROP PROCEDURE out_stock;
DELIMITER //
CREATE PROCEDURE out_stock (IN p_cod_mag INT(3), IN p_cod_art CHAR(6), IN p_qte INT(4), IN p_cod_mag_dst INT(3))
BEGIN
	IF p_qte > 0
	THEN	
		SELECT DISTINCT pu INTO @pum_src FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art;
		SELECT DISTINCT pu,qte INTO @pum_dst,@qte_dst FROM Fiche_stock WHERE Cod_mag = p_cod_mag_dst AND Cod_art = p_cod_art;
		IF (SELECT DISTINCT Qte FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art) > p_qte
		THEN
			INSERT INTO sortie (cod_mag,Cod_art,date_s,qte,pu,pt) SELECT p_cod_mag, p_cod_art, NOW(), p_qte, Pu, (Pu * p_qte) FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art ;
			UPDATE Fiche_stock SET Pt = (Pt/Qte) * (Qte - p_qte), Qte = Qte - p_qte WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art;
		ELSEIF (SELECT DISTINCT Qte FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art) = p_qte
		THEN
			INSERT INTO sortie (cod_mag,Cod_art,date_s,qte,pu,pt) SELECT p_cod_mag, p_cod_art, NOW(), p_qte, Pu, (Pu * p_qte) FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art ;
			-- INSERT INTO sortie (cod_mag,Cod_art,date_s,qte,pu) SELECT p_cod_mag, p_cod_art, NOW(), p_qte, Pu FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art ;
			DELETE FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Cod_art = p_cod_art;
		END IF;

		SET @p_cod_four = 1;

		INSERT INTO entrer (Cod_mag,Cod_art,cod_four,Date_e,Qte,Pu,pt) VALUES (p_cod_mag_dst,p_cod_art,@p_cod_four,NOW(),p_qte,@pum_src,(p_qte * @pum_src));
		IF @qte_dst > 0
		THEN
			SET @pum_dst = ((@pum_dst * @qte_dst) + (@pum_src * p_qte))/(@qte_dst+p_qte);
			SET @ptm = @pum_dst * (@qte_dst + p_qte);
		ELSE
			SET @pum_dst = @pum_src;
			SET @ptm = @pum_dst * p_qte;
		END IF;
		IF EXISTS(SELECT cod_mag FROM Fiche_stock WHERE Cod_mag = p_cod_mag_dst AND Cod_art = p_cod_art)
		THEN

			UPDATE Fiche_stock SET Qte = Qte + p_qte , pu = @pum_dst, pt = @ptm WHERE Cod_mag = p_cod_mag_dst AND Cod_art = p_cod_art;
		ELSE
			INSERT INTO test (a,b,c) VALUES (@pum_dst,@pum_src,@qte_dst);
			INSERT INTO Fiche_stock (Cod_mag,Cod_art,Date_e,Qte,Pu,pt) VALUES (p_cod_mag_dst,p_cod_art,NOW(),p_qte,@pum_dst,(p_qte * @pum_dst));
		END IF;
	END IF;
END//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE codif_art (IN p_cod_f CHAR(1),IN p_cod_sf CHAR(2),IN p_nom CHAR(30))
BEGIN
	-- trouver l'id de sous famille avec son code
	#SELECT id_sf INTO @sf FROM SF WHERE cod_sf = p_cod_sf;


	SELECT COUNT(Cod_art) INTO @num_art FROM article WHERE Cod_sf = p_cod_sf;
	SELECT MAX(num_art) INTO @max_num_art FROM article WHERE Cod_sf = p_cod_sf;

	-- si y a pas d'articles max_num d'articles sera NULL;
	#SET @max_num_art = @max_num_art + 1;
	IF @max_num_art IS NULL THEN
		SET @max_num_art = 0;
	END IF;
	
	-- si On a des numeros d'articles non utilisées nous cherchons des crous
	IF @max_num_art > @num_art THEN
		SET @i = 1;
		for_l : LOOP
			IF EXISTS (SELECT Cod_art FROM article WHERE num_art = @i AND Cod_sf = p_cod_sf) THEN
				SET @i = @i + 1;
			ELSE
				LEAVE for_l;
			END IF;
		END LOOP for_l;
	-- ELSEIF @max_num_art = 0 THEN
		-- SET @i = 0;
	ELSE
		SET @i = @max_num_art + 1;
	END IF;

	SET @cod_art = CONCAT(CONVERT(p_cod_f,char),CONVERT(p_cod_sf,char),LPAD(CONVERT(@i,char),3,'0'));

	IF NOT EXISTS (SELECT Cod_art FROM article WHERE Desig_art = p_nom) THEN
		
		INSERT INTO article (Cod_art,num_art,Desig_art,Cod_sf) VALUES (@cod_art,@i,p_nom,p_cod_sf);
	
	END IF;


END//
DELIMITER ;
