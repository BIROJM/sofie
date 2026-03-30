SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Base de données
--
USE `db_sofiev1`;

--
-- Tables ------------------------------------------------------------------------------------------------
--

--
-- t_profile
--
INSERT INTO `t_profile` (`IDProfile`, `Designation`) VALUES (100, 'Itinérant');
UPDATE `t_profile` SET `sync`='N' WHERE  `IDProfile`=5;
UPDATE `t_profile` SET `sync`='N' WHERE  `IDProfile`=4;
UPDATE `t_profile` SET `sync`='N' WHERE  `IDProfile`=3;
UPDATE `t_profile` SET `sync`='N' WHERE  `IDProfile`=2;
UPDATE `t_profile` SET `sync`='N' WHERE  `IDProfile`=1;
INSERT INTO `t_profile` (`IDProfile`, `Designation`) VALUES (140, 'Administrateur');


--
-- t_droit
--
ALTER TABLE `t_droit` CHANGE COLUMN `context` `context` ENUM('Région','Ouvrage','Réparateur','Localité','Comité','Utilisateur','Groupe','Collecte','Panne','Numéro appel','Evènement','Statistique','Paramétre','Diffusion SMS','Agent','Appel téléphonique','Autre','Interface','Notification') NULL DEFAULT 'Autre' COLLATE 'utf8_unicode_ci' AFTER `deleted_at`;
UPDATE `t_droit` SET `libelle`='Ajouter un réparateur' WHERE  `id`=3;
UPDATE `t_droit` SET `libelle`='Modifier une région' WHERE  `id`=6;
UPDATE `t_droit` SET `libelle`='Modifier un réparateur' WHERE  `id`=8;
UPDATE `t_droit` SET `libelle`='Supprimer un réparateur' WHERE  `id`=9;
UPDATE `t_droit` SET `libelle`='Voir les régions' WHERE  `id`=14;
UPDATE `t_droit` SET `libelle`='Voir les réparateurs' WHERE  `id`=15;
UPDATE `t_droit` SET `libelle`='Ajouter un comité' WHERE  `id`=4;
UPDATE `t_droit` SET `libelle`='Ajouter une localité' WHERE  `id`=5;
UPDATE `t_droit` SET `libelle`='Modifier une localité' WHERE  `id`=16;
UPDATE `t_droit` SET `libelle`='Supprimer une localité' WHERE  `id`=17;
UPDATE `t_droit` SET `libelle`='Voir les localité' WHERE  `id`=18;
UPDATE `t_droit` SET `libelle`='Modifier un comité' WHERE  `id`=19;
UPDATE `t_droit` SET `libelle`='Supprimer un comité' WHERE  `id`=20;
UPDATE `t_droit` SET `libelle`='Voir les comité' WHERE  `id`=21;
UPDATE `t_droit` SET `libelle`='Voir les évènements' WHERE  `id`=28;
UPDATE `t_droit` SET `libelle`='Configurer les paramètres d\'envoie des SMS' WHERE  `id`=30;
UPDATE `t_droit` SET `libelle`='Configurer les paramètres de délais de notification' WHERE  `id`=31;
UPDATE `t_droit` SET `libelle`='Voir les appels téléphoniques' WHERE  `id`=33;
UPDATE `t_droit` SET `libelle`='Voir les numéros d\'appel' WHERE  `id`=34;
UPDATE `t_droit` SET `libelle`='Gérer les droits d\'un groupe' WHERE  `id`=47;
UPDATE `t_droit` SET `libelle`='Accéder au menu exploitation' WHERE  `id`=54;
UPDATE `t_droit` SET `libelle`='Accéder au menu administration' WHERE  `id`=55;
UPDATE `t_droit` SET `libelle`='Voir les paramètres d\'administration' WHERE  `id`=57;
UPDATE `t_droit` SET `context`='Paramétre' WHERE  `id`=57;
UPDATE `t_droit` SET `context`='Evènement' WHERE  `id`=28;
UPDATE `t_droit` SET `context`='Réparateur' WHERE  `id`=3;
UPDATE `t_droit` SET `context`='Comité' WHERE  `id`=4;
UPDATE `t_droit` SET `context`='Localité' WHERE  `id`=5;
UPDATE `t_droit` SET `context`='Région' WHERE  `id`=6;
UPDATE `t_droit` SET `context`='Réparateur' WHERE  `id`=8;
UPDATE `t_droit` SET `context`='Réparateur' WHERE  `id`=9;
UPDATE `t_droit` SET `context`='Région' WHERE  `id`=14;
UPDATE `t_droit` SET `context`='Réparateur' WHERE  `id`=15;
UPDATE `t_droit` SET `context`='Localité' WHERE  `id`=16;
UPDATE `t_droit` SET `context`='Localité' WHERE  `id`=17;
UPDATE `t_droit` SET `context`='Localité' WHERE  `id`=18;
UPDATE `t_droit` SET `context`='Comité' WHERE  `id`=19;
UPDATE `t_droit` SET `context`='Comité' WHERE  `id`=20;
UPDATE `t_droit` SET `context`='Comité' WHERE  `id`=21;
UPDATE `t_droit` SET `context`='Paramétre' WHERE  `id`=30;
UPDATE `t_droit` SET `context`='Paramétre' WHERE  `id`=31;
UPDATE `t_droit` SET `context`='Paramétre' WHERE  `id`=32;
UPDATE `t_droit` SET `context`='Paramétre' WHERE  `id`=33;
UPDATE `t_droit` SET `context`='Numéro appel' WHERE  `id`=34;
INSERT INTO `t_droit` (`id`, `role`, `libelle`, `context`, `droitcategory_id`) VALUES (60, 'ROLE_ACTIVE_USER', 'Activer et désactiver un utilisateur', 'Utilisateur', 12);
ALTER TABLE `t_droit` CHANGE COLUMN `context` `context` ENUM('Région','Ouvrage','Réparateur','Localité','Comité','Utilisateur','Groupe','Collecte','Panne','Numéro appel','Evènement','Statistique','Paramétre','Diffusion SMS','Agent','Appel téléphonique','Autres','Interface','Notification') NULL DEFAULT NULL COLLATE 'utf8_unicode_ci' AFTER `deleted_at`;
UPDATE `t_droit` SET `role`='ROLE_ACTIVATE_USER' WHERE  `id`=60;
INSERT INTO `t_droit` (`id`, `role`, `libelle`, `context`, `droitcategory_id`) VALUES (61, 'ROLE_STAT_REPARATEUR', 'Rendement des réparateurs', 'Statistique', 13);
INSERT INTO `t_droit` (`id`, `role`, `libelle`, `context`, `droitcategory_id`) VALUES (62, 'ROLE_STAT_AGENT_FORMEN', 'Rendement des agents Formen', 'Statistique', 13);
INSERT INTO `t_droit` (`id`, `role`, `libelle`, `context`, `droitcategory_id`) VALUES (63, 'ROLE_STAT_SOCIOLOGUE', 'Sociologue', 'Statistique', 13);
INSERT INTO `t_droit` (`id`, `role`, `libelle`, `context`, `droitcategory_id`) VALUES (64, 'ROLE_STAT_DR', 'Directeurs régionaux', 'Statistique', 13);
INSERT INTO `t_droit` (`id`, `role`, `libelle`, `context`, `droitcategory_id`) VALUES (65, 'ROLE_STAT_POINT_FORAGE', 'Point des forages', 'Statistique', 13);
INSERT INTO `t_droit` (`id`, `role`, `libelle`, `context`, `droitcategory_id`) VALUES (66, 'ROLE_STAT_COMITE', 'Rendement des comités eaux', 'Statistique', 13);
INSERT INTO `t_droit` (`id`, `role`, `libelle`, `context`, `droitcategory_id`) VALUES (67, 'ROLE_STAT_GLOBALE', 'Statistiques globales', 'Statistique', 13);
DELETE FROM `t_droit` WHERE  `id`=26;


--
-- t_unitedelais
--
UPDATE `t_unitedelais` SET `libelle`='seconde' WHERE  `id`=1;


--
-- users
--



--
--
--
SET FOREIGN_KEY_CHECKS=1;
COMMIT;