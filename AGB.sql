CREATE DATABASE IF NOT EXISTS `agb_ausleihen_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `agb_ausleihen_db`;

-- Exportiere Struktur von Tabelle agb_ausleihen_db.gegenstand
CREATE TABLE IF NOT EXISTS `gegenstand` (
  `gegenstand_id` varchar(255) NOT NULL,
  `bezeichnung` varchar(50) NOT NULL,
  PRIMARY KEY (`gegenstand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle agb_ausleihen_db.schaden
CREATE TABLE IF NOT EXISTS `schaden` (
  `bezeichnung` varchar(50) NOT NULL,
  PRIMARY KEY (`bezeichnung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle agb_ausleihen_db.hat_schaden
CREATE TABLE IF NOT EXISTS `hat_schaden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gegenstand_id` varchar(50) NOT NULL DEFAULT '',
  `bezeichnung` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `gegenstand_id_bezeichnung` (`gegenstand_id`,`bezeichnung`),
  KEY `FK_hat_schaden_schaden` (`bezeichnung`),
  CONSTRAINT `FK_hat_schaden_gegenstand` FOREIGN KEY (`gegenstand_id`) REFERENCES `gegenstand` (`gegenstand_id`),
  CONSTRAINT `FK_hat_schaden_schaden` FOREIGN KEY (`bezeichnung`) REFERENCES `schaden` (`bezeichnung`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle agb_ausleihen_db.schueler
CREATE TABLE IF NOT EXISTS `schueler` (
  `schueler_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `mail` varchar(50) DEFAULT '0',
  PRIMARY KEY (`schueler_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle agb_ausleihen_db.leiht
CREATE TABLE IF NOT EXISTS `leiht` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schueler_id` varchar(50) NOT NULL DEFAULT '',
  `gegenstand_id` varchar(255) NOT NULL DEFAULT '',
  `datum_start` datetime NOT NULL,
  `datum_ende` datetime DEFAULT NULL,
  `datum_rueckgabe` datetime DEFAULT NULL,
  `weitere` varchar(50) DEFAULT NULL,
  `lehrer` varchar(50) DEFAULT NULL,
  `aktiv` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `FK_leiht_schueler` (`schueler_id`),
  KEY `FK_leiht_gegenstand` (`gegenstand_id`),
  CONSTRAINT `FK_leiht_gegenstand` FOREIGN KEY (`gegenstand_id`) REFERENCES `gegenstand` (`gegenstand_id`),
  CONSTRAINT `FK_leiht_schueler` FOREIGN KEY (`schueler_id`) REFERENCES `schueler` (`schueler_id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt