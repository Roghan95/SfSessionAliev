-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage des données de la table sfsessionaliev.categorie : ~4 rows (environ)
INSERT INTO `categorie` (`id`, `nom_categorie`) VALUES
	(1, 'Webdesign'),
	(2, 'Bureautique'),
	(3, 'Développement web'),
	(4, 'Vente');

-- Listage des données de la table sfsessionaliev.doctrine_migration_versions : ~1 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230921115427', '2023-09-21 11:55:13', 512);

-- Listage des données de la table sfsessionaliev.formateur : ~4 rows (environ)
INSERT INTO `formateur` (`id`, `nom_formateur`, `prenom_formateur`, `sexe`, `date_naissance`, `email`, `telephone`) VALUES
	(1, 'MURMANN', 'Mickael', 'M', '1990-09-21', 'mickael@exemple.com', '0664251418'),
	(2, 'MATHIEU', 'Quentin', 'M', '1994-09-21', 'quentin@exemple.com', '0664271814'),
	(3, 'SMAIL', 'Stéphane', 'M', '1987-09-21', 'stephane@exemple.com', '0765264748'),
	(4, 'DE FIGUEIREDO', 'Alexandre', 'M', '1989-09-21', 'alexandre@exemple.com', '0766224488');

-- Listage des données de la table sfsessionaliev.formation : ~4 rows (environ)
INSERT INTO `formation` (`id`, `nom_formation`) VALUES
	(1, 'Développement Web'),
	(2, 'Webdesign'),
	(3, 'Bureautique'),
	(4, 'Vente');

-- Listage des données de la table sfsessionaliev.messenger_messages : ~0 rows (environ)

-- Listage des données de la table sfsessionaliev.module : ~10 rows (environ)
INSERT INTO `module` (`id`, `categorie_id`, `nom_module`) VALUES
	(1, 3, 'HTML'),
	(2, 3, 'CSS'),
	(3, 3, 'PHP'),
	(4, 3, 'SQL'),
	(5, 2, 'Powerpoint'),
	(6, 1, 'Photoshop'),
	(7, 4, 'E-commerce'),
	(8, 2, 'Word'),
	(9, 2, 'Excel'),
	(10, 1, 'Figma');

-- Listage des données de la table sfsessionaliev.programme : ~8 rows (environ)
INSERT INTO `programme` (`id`, `session_id`, `module_id`, `nb_jours`) VALUES
	(1, 1, 1, 5),
	(2, 1, 2, 5),
	(3, 1, 3, 5),
	(4, 3, 10, 5),
	(5, 3, 6, 5),
	(6, 2, 5, 5),
	(7, 2, 8, 5),
	(8, 2, 9, 5);

-- Listage des données de la table sfsessionaliev.session : ~3 rows (environ)
INSERT INTO `session` (`id`, `formation_id`, `formateur_id`, `nom_session`, `nb_places`, `date_debut`, `date_fin`) VALUES
	(1, 1, 1, 'Plateau numérique', 15, '2023-09-21', '2024-02-16'),
	(2, 3, 4, 'Strasbourg Bureautique', 15, '2023-09-21', '2024-03-21'),
	(3, 2, 3, 'Colmar - Webdesign1', 15, '2023-09-21', '2023-10-05');

-- Listage des données de la table sfsessionaliev.session_stagiaire : ~6 rows (environ)
INSERT INTO `session_stagiaire` (`session_id`, `stagiaire_id`) VALUES
	(1, 1),
	(1, 2),
	(1, 3),
	(1, 4),
	(2, 1),
	(2, 3);

-- Listage des données de la table sfsessionaliev.stagiaire : ~4 rows (environ)
INSERT INTO `stagiaire` (`id`, `nom_stagiaire`, `prenom_stagiaire`, `sexe`, `date_naissance`, `email`, `telephone`) VALUES
	(1, 'FALDA', 'Cédric', 'M', '1987-07-29', 'cedric@exemple.com', '0764241748'),
	(2, 'ALIEV', 'Baisangour', 'M', '1997-07-29', 'aliev@exemple.com', '0764241516'),
	(3, 'CHAMAEV', 'Mansour', 'M', '1997-06-20', 'mansour@exemple.com', '0764241719'),
	(4, 'AKKARI', 'Aziz', 'M', '1999-07-20', 'aziz@exemple.com', '0764248969');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
