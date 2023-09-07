------------------------------------------------------------
--        Script Postgre 
------------------------------------------------------------



------------------------------------------------------------
-- Table: villes
------------------------------------------------------------

DROP TABLE IF EXISTS villes CASCADE;
DROP TABLE IF EXISTS match CASCADE;
DROP TABLE IF EXISTS participe CASCADE;
DROP TABLE IF EXISTS player CASCADE;
DROP TABLE IF EXISTS accepte_demande CASCADE;

CREATE TABLE public.villes(
	id      SERIAL NOT NULL ,
	ville   VARCHAR (50) NOT NULL  ,
	CONSTRAINT villes_PK PRIMARY KEY (id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: player
------------------------------------------------------------
CREATE TABLE public.player(
	id               SERIAL NOT NULL ,
	prenom           VARCHAR (50) NOT NULL ,
	nom              VARCHAR (50) NOT NULL ,
	age              INT  NOT NULL ,
	photo            VARCHAR (50) NOT NULL ,
	mot_de_passe     VARCHAR (100) NOT NULL ,
	forme_sportive   VARCHAR (50) NOT NULL ,
	mail             VARCHAR (50) NOT NULL ,
	--matchs_passes    INT  NOT NULL ,
	id_villes        INT  NOT NULL  ,
	note			 INT,
	CONSTRAINT player_PK PRIMARY KEY (id)

	,CONSTRAINT player_villes_FK FOREIGN KEY (id_villes) REFERENCES public.villes(id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: match
------------------------------------------------------------
CREATE TABLE public.match(
	id                SERIAL NOT NULL ,
	sport             VARCHAR (50) NOT NULL ,
	nb_joueurs        INT  NOT NULL ,
	nb_joueurs_min    INT  NOT NULL ,
	nb_joueurs_max    INT  NOT NULL ,
	date_debut        TIMESTAMP  NOT NULL ,
	date_fin          TIMESTAMP  NOT NULL ,
	adresse			  VARCHAR (100) NOT NULL,
	prix              FLOAT  NOT NULL ,
	complet           BOOL  NOT NULL ,
	meilleur_joueur   VARCHAR (50),
	score             VARCHAR (50),
	id_player         INT  NOT NULL ,
	id_villes         INT  NOT NULL  ,
	CONSTRAINT match_PK PRIMARY KEY (id)

	,CONSTRAINT match_player_FK FOREIGN KEY (id_player) REFERENCES public.player(id)
	,CONSTRAINT match_villes0_FK FOREIGN KEY (id_villes) REFERENCES public.villes(id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: participe
------------------------------------------------------------
CREATE TABLE public.participe(
	id          INT  NOT NULL ,
	id_player   INT  NOT NULL  ,
	CONSTRAINT participe_PK PRIMARY KEY (id,id_player)

	,CONSTRAINT participe_match_FK FOREIGN KEY (id) REFERENCES public.match(id)
	,CONSTRAINT participe_player0_FK FOREIGN KEY (id_player) REFERENCES public.player(id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: accepte/demande
------------------------------------------------------------
CREATE TABLE public.accepte_demande(
	id          INT  NOT NULL ,
	id_player   INT  NOT NULL ,
	accepter    INT  NOT NULL  ,
	CONSTRAINT accepte_demande_PK PRIMARY KEY (id,id_player)

	,CONSTRAINT accepte_demande_match_FK FOREIGN KEY (id) REFERENCES public.match(id)
	,CONSTRAINT accepte_demande_player0_FK FOREIGN KEY (id_player) REFERENCES public.player(id)
)WITHOUT OIDS;





