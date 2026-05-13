-- Aquest script NOMÉS s'executa la primera vegada que es crea el contenidor.
-- Si es vol recrear les taules de nou cal esborrar el contenidor, o bé les dades del contenidor
-- és a dir, 
-- esborrar el contingut de la carpeta db_data 
-- o canviant el nom de la carpeta, però atenció a no pujar-la a git


-- És un exemple d'script per crear una base de dades i una taula
-- i afegir-hi dades inicials

-- Si creem la BBDD aquí podem control·lar la codificació i el collation
-- en canvi en el docker-compose no podem especificar el collation ni la codificació

-- Per assegurar-nes de que la codificació dels caràcters d'aquest script és la correcta
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS cantina
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Donem permisos a l'usuari 'usuari' per accedir a la base de dades 'cantina'
-- sinó, aquest usuari no podrà veure la base de dades i no podrà accedir a les taules
GRANT ALL PRIVILEGES ON cantina.* TO 'usuari'@'%';
FLUSH PRIVILEGES;


-- Després de crear la base de dades, cal seleccionar-la per treballar-hi
USE cantina;

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(5,2) DEFAULT NULL,
  `day` text COLLATE utf8mb4_unicode_ci,
  `week` int DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `menus2`;
CREATE TABLE `menus2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `day` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `week` int DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(65,0) NOT NULL,
  `category` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `name`, `price`, `category`) VALUES
(4,	'cafe',	12,	'Begudes'),
(5,	'Tallat',	1,	'Begudes'),
(6,	'Cafe amb Liet',	1,	'Begudes'),
(7,	'Infusions',	1,	'Begudes'),
(8,	'Aigua 500ml',	1,	'Begudes'),
(9,	'Aigua 1500ml',	1,	'Begudes'),
(10,	'Liaunes',	1,	'Begudes'),
(11,	'Cacaulat',	2,	'Begudes'),
(12,	'Liet soja i sense lactosa',	1,	'Begudes'),
(13,	'Gel',	1,	'Begudes'),
(14,	'Croissant',	1,	'Pastes'),
(15,	'Donut Sucre',	1,	'Pastes'),
(16,	'Donut xoco',	1,	'Pastes'),
(17,	'Canya',	1,	'Pastes'),
(18,	'Tonyina',	2,	'Entrepans Freds'),
(19,	'Pernil savat',	2,	'Entrepans Freds'),
(20,	'Fuet ',	2,	'Entrepans Freds'),
(21,	'Formatge',	2,	'Entrepans Freds'),
(22,	'Xoriço',	2,	'Entrepans Freds'),
(23,	'Pernil Dolç',	2,	'Entrepans Freds'),
(24,	'Frankfurt',	2,	'Entrepans Calents'),
(25,	'Bacon',	2,	'Entrepans Calents'),
(26,	'Llom',	3,	'Entrepans Calents'),
(27,	'Hamburguesa',	3,	'Entrepans Calents'),
(28,	'Truita',	3,	'Entrepans Calents'),
(29,	'Mallorqui',	3,	'2'),
(30,	'Bikini',	3,	'Entrepans Calents'),
(31,	'Xistorra',	3,	'Entrepans Calents'),
(32,	'Serranito',	2,	'Entrepans Calents');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1,	'admin',	'1234');

-- 2026-05-11 09:22:55 UTC



