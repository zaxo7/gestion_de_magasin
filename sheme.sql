CREATE TABLE Mag 
(
	Cod_mag INT(3) PRIMARY KEY AUTO_INCREMENT,
	lien char(30) NOT NULL

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
	Id_F INT(3) PRIMARY KEY AUTO_INCREMENT,
	Cod_F char(1) NOT NULL UNIQUE,
	Nom_F CHAR(15) NOT NULL
);

CREATE TABLE SF
(
	Id_SF INT(3) PRIMARY KEY AUTO_INCREMENT,
	Cod_SF CHAR(2) NOT NULL UNIQUE,
	Nom_SF CHAR(15) NOT NULL,
	Id_F INT(3) NOT NULL,
	FOREIGN KEY (Id_f) REFERENCES f (Id_f) ON DELETE CASCADE 
);

CREATE TABLE Article
(
	Id_art INT(5) AUTO_INCREMENT PRIMARY KEY,
	Cod_art CHAR(6),
	num_art INT(3),
	Nom CHAR(30) NOT NULL,
	Id_SF INT(3) NOT NULL,
	FOREIGN KEY (Id_SF) REFERENCES SF(Id_SF) ON DELETE CASCADE
);

CREATE TABLE Fiche_stock
(
	Cod_mag INT(3) NOT NULL,
	Id_art INT(5) NOT NULL,
	Date_e DATETIME NOT NULL,
	Qte INT(4) UNSIGNED NOT NULL,
	Pu FLOAT UNSIGNED NOT NULL,
	FOREIGN KEY (Cod_mag) REFERENCES Mag(Cod_mag) ON DELETE CASCADE,
	FOREIGN KEY (Id_art) REFERENCES Article(Id_art) ON DELETE CASCADE,
	PRIMARY KEY(Cod_mag,Id_art)
);

CREATE TABLE sortie
(
	Cod_mag INT(3) NOT NULL,
	Id_art INT(5) NOT NULL,
	Date_s DATETIME NOT NULL,
	Qte INT(4) UNSIGNED NOT NULL,
	Pu FLOAT UNSIGNED NOT NULL,
	FOREIGN KEY (Cod_mag) REFERENCES Mag(Cod_mag) ON DELETE CASCADE,
	FOREIGN KEY (Id_art) REFERENCES Article(Id_art) ON DELETE CASCADE,
	PRIMARY KEY(Cod_mag,Id_art,Date_s)
);

CREATE TABLE entrer
(
	Cod_mag INT(3) NOT NULL,
	Id_art INT(5) NOT NULL,
	Cod_four INT(4) NOT NULL,
	Date_e DATETIME NOT NULL,
	Qte INT(4) UNSIGNED NOT NULL,
	Pu FLOAT UNSIGNED NOT NULL,
	FOREIGN KEY (Cod_mag) REFERENCES Mag(Cod_mag) ON DELETE CASCADE,
	FOREIGN KEY (Id_art) REFERENCES Article(Id_art) ON DELETE CASCADE,
	FOREIGN KEY (Cod_four) REFERENCES Fourniseur(Cod_four) ON DELETE CASCADE,
	PRIMARY KEY(Cod_mag,Id_art,Cod_four,Date_e)
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
CREATE PROCEDURE in_stock (IN p_cod_mag INT(3), IN p_id_art INT(5), IN p_cod_four INT(4), IN p_qte INT(4),IN p_pu FLOAT)
BEGIN
	IF p_qte > 0
	THEN
		INSERT INTO entrer (Cod_mag,Id_art,cod_four,Date_e,Qte,Pu) VALUES (p_cod_mag,p_id_art,p_cod_four,NOW(),p_qte,p_pu);
		IF EXISTS(SELECT cod_mag FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art)
		THEN
			SELECT DISTINCT pu,qte INTO @pum,@qte FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art;
			SET @pum = ((@pum * @qte) + (p_pu * p_qte))/(@qte+p_qte);
			UPDATE Fiche_stock SET Qte = Qte + p_qte , pu = @pum WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art;
		ELSE
			INSERT INTO Fiche_stock (Cod_mag,Id_art,Date_e,Qte,Pu) VALUES (p_cod_mag,p_id_art,NOW(),p_qte,p_pu);
		END IF;

	END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE out_stock (IN p_cod_mag INT(3), IN p_id_art INT(5), IN p_qte INT(4))
BEGIN
	IF p_qte > 0
	THEN	
		IF (SELECT DISTINCT Qte FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art) > p_qte
		THEN
			INSERT INTO sortie (cod_mag,id_art,date_s,qte,pu) SELECT p_cod_mag, p_id_art, NOW(), p_qte, Pu FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art ;
			UPDATE Fiche_stock SET Qte = Qte - p_qte WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art;
		ELSEIF (SELECT DISTINCT Qte FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art) = p_qte
		THEN
			INSERT INTO sortie (cod_mag,id_art,date_s,qte,pu) SELECT p_cod_mag, p_id_art, NOW(), p_qte, Pu FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art ;
			DELETE FROM Fiche_stock WHERE Cod_mag = p_cod_mag AND Id_art = p_id_art;
		END IF;
	END IF;
END//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE codif_art (IN p_cod_f CHAR(1),IN p_cod_sf CHAR(2),IN p_nom CHAR(30))
BEGIN
	-- trouver l'id de sous famille avec son code
	SELECT id_sf INTO @sf FROM SF WHERE cod_sf = p_cod_sf;


	SELECT COUNT(id_art) INTO @num_art FROM article WHERE id_sf = @sf;
	SELECT MAX(id_art) INTO @max_num_art FROM article WHERE id_sf = @sf;

	IF @max_num_art IS NULL THEN
		SET @max_num_art = 0;
	END IF;
	
	IF @max_num_art > @num_art THEN
		SET @i = 0;
		for_l : LOOP
			IF EXISTS (SELECT id_art FROM article WHERE num_art = @i AND id_sf = @sf) THEN
				SET @i = @i + 1;
			ELSE
				LEAVE for_l;
			END IF;
		END LOOP for_l;
	ELSEIF @max_num_art = 0 THEN
		SET @i = 0;
		ELSE
		SET @i = @max_num_art + 1;
	END IF;

	SET @cod_art = CONCAT(CONVERT(p_cod_f,char),CONVERT(p_cod_sf,char),LPAD(CONVERT(@i,char),3,'0'));


	INSERT INTO article (cod_art,num_art,nom,id_sf) VALUES (@cod_art,@i,p_nom,@sf);

	

END//
DELIMITER ;
