-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               5.6.24 - MySQL Community Server (GPL)
-- Server Betriebssystem:        Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportiere Struktur von Tabelle dh-registry.cc_config_acos
DROP TABLE IF EXISTS `cc_config_acos`;
CREATE TABLE IF NOT EXISTS `cc_config_acos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Eg. the user name',
  `foreign_key` int(11) NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`foreign_key`,`model`),
  KEY `model` (`model`),
  CONSTRAINT `FK_cc_config_acos_user_roles` FOREIGN KEY (`foreign_key`) REFERENCES `user_roles` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.cc_config_actions
DROP TABLE IF EXISTS `cc_config_actions`;
CREATE TABLE IF NOT EXISTS `cc_config_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_config_table_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The final Auth-check is performed against this path. Consider possible plugin routes here.',
  `contextual` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Makes clear if the action belongs into a record''s context or not. If yes, the record identifier will be appended, if bulk_processing ability is false. If not, it will also appear in more general areas such as the main menu or on top of an index view.',
  `has_form` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'If the view contains a form, option lists will be generated from related models.',
  `bulk_processing` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'If the action is contextual, if it accepts a POSTed array of record IDs for bulk processing or not.',
  `has_view` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If the action does not have a view, it cannot have child-actions that appear in the view. ',
  `comment` text COLLATE utf8_unicode_ci,
  `position` int(3) DEFAULT NULL COMMENT 'Consider this as a default positioning',
  `controller_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_app_override` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cc_config_actions_cc_config_tables` (`cc_config_table_id`),
  CONSTRAINT `FK_cc_config_actions_cc_config_tables` FOREIGN KEY (`cc_config_table_id`) REFERENCES `cc_config_tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.cc_config_actions_views
DROP TABLE IF EXISTS `cc_config_actions_views`;
CREATE TABLE IF NOT EXISTS `cc_config_actions_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(3) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `parent_action_id` int(11) NOT NULL,
  `child_action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cc_config_actions_views_cc_config_actions` (`parent_action_id`),
  KEY `FK_cc_config_actions_views_cc_config_actions_2` (`child_action_id`),
  CONSTRAINT `FK_cc_config_actions_views_cc_config_actions` FOREIGN KEY (`parent_action_id`) REFERENCES `cc_config_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_cc_config_actions_views_cc_config_actions_2` FOREIGN KEY (`child_action_id`) REFERENCES `cc_config_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.cc_config_configurations
DROP TABLE IF EXISTS `cc_config_configurations`;
CREATE TABLE IF NOT EXISTS `cc_config_configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.cc_config_displayedrelations
DROP TABLE IF EXISTS `cc_config_displayedrelations`;
CREATE TABLE IF NOT EXISTS `cc_config_displayedrelations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_config_table_id` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `position` int(3) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'association type',
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tablename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `primary_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'id' COMMENT 'The name of the primary key of the related model.',
  PRIMARY KEY (`id`),
  KEY `visisble` (`visible`),
  KEY `FK_cc_config_displayedrelations_cc_config_tables` (`cc_config_table_id`),
  CONSTRAINT `FK_cc_config_displayedrelations_cc_config_tables` FOREIGN KEY (`cc_config_table_id`) REFERENCES `cc_config_tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.cc_config_fielddefinitions
DROP TABLE IF EXISTS `cc_config_fielddefinitions`;
CREATE TABLE IF NOT EXISTS `cc_config_fielddefinitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_config_table_id` int(11) DEFAULT NULL COMMENT 'Define a fieldlist belonging to the table directly as a default fieldlist, applying to any action. Leave empty action_id then.',
  `cc_config_action_id` int(11) DEFAULT NULL COMMENT 'If assigned to a specific action, the fielddefinition overrides the default fielddefinition of the table.',
  `context` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'main' COMMENT 'What this fieldlist''s fielddefinition is for. "main" serves the main purpose of a view, but there might be other areas in the same view requiring an additional fieldlist, possibly one from a foreign model.',
  `position` int(3) DEFAULT NULL,
  `fieldname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_method` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auto' COMMENT 'value must be one of available methods from DisplayHelper',
  `display_options` text COLLATE utf8_unicode_ci COMMENT 'a list of options, depends from the selected display method',
  PRIMARY KEY (`id`),
  KEY `FK_cc_config_fielddefinitions_cc_config_tables` (`cc_config_table_id`),
  KEY `FK_cc_config_fielddefinitions_cc_config_actions` (`cc_config_action_id`),
  CONSTRAINT `FK_cc_config_fielddefinitions_cc_config_actions` FOREIGN KEY (`cc_config_action_id`) REFERENCES `cc_config_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_cc_config_fielddefinitions_cc_config_tables` FOREIGN KEY (`cc_config_table_id`) REFERENCES `cc_config_tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.cc_config_menus
DROP TABLE IF EXISTS `cc_config_menus`;
CREATE TABLE IF NOT EXISTS `cc_config_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(3) DEFAULT '0',
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `block` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cakeclient_nav',
  `foreign_key` int(11) DEFAULT NULL COMMENT 'Public menus have a NULL value here and belong to no ARO',
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'UserRole' COMMENT 'eg, could also be User',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_cc_config_menus_user_roles` (`foreign_key`),
  KEY `model` (`model`),
  CONSTRAINT `FK_cc_config_menus_user_roles` FOREIGN KEY (`foreign_key`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.cc_config_menu_items
DROP TABLE IF EXISTS `cc_config_menu_items`;
CREATE TABLE IF NOT EXISTS `cc_config_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(3) DEFAULT NULL,
  `cc_config_table_id` int(11) NOT NULL,
  `cc_config_action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cc_config_actions_cc_config_tables_cc_config_tables` (`cc_config_table_id`),
  KEY `FK_cc_config_actions_cc_config_tables_cc_config_actions` (`cc_config_action_id`),
  CONSTRAINT `FK_cc_config_actions_cc_config_tables_cc_config_actions` FOREIGN KEY (`cc_config_action_id`) REFERENCES `cc_config_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_cc_config_actions_cc_config_tables_cc_config_tables` FOREIGN KEY (`cc_config_table_id`) REFERENCES `cc_config_tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.cc_config_tables
DROP TABLE IF EXISTS `cc_config_tables`;
CREATE TABLE IF NOT EXISTS `cc_config_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_config_aco_id` int(11) NOT NULL,
  `cc_config_menu_id` int(11) DEFAULT NULL COMMENT 'ACOs need to be created before menus',
  `position` int(3) DEFAULT NULL COMMENT 'sorting for menu generation',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `allow_all` tinyint(1) NOT NULL DEFAULT '0',
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'if not following naming conventions',
  `controller` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'if not following naming conventions',
  `displayfield` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `displayfield_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_associations` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'if associated tables are shown AT ALL',
  PRIMARY KEY (`id`),
  KEY `FK_cc_config_tables_cc_config_menus` (`cc_config_menu_id`),
  KEY `FK_cc_config_tables_cc_config_acos` (`cc_config_aco_id`),
  CONSTRAINT `FK_cc_config_tables_cc_config_acos` FOREIGN KEY (`cc_config_aco_id`) REFERENCES `cc_config_acos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_cc_config_tables_cc_config_menus` FOREIGN KEY (`cc_config_menu_id`) REFERENCES `cc_config_menus` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt


-- Exportiere Struktur von Tabelle dh-registry.user_roles
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `cakeclient_prefix` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Daten Export vom Benutzer nicht ausgewählt
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
