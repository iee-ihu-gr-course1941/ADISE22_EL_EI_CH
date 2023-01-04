-- --------------------------------------------------------
-- Διακομιστής:                  127.0.0.1
-- Έκδοση διακομιστή:            10.4.22-MariaDB - mariadb.org binary distribution
-- Λειτ. σύστημα διακομιστή:     Win64
-- HeidiSQL Έκδοση:              12.2.0.6576
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for domino
CREATE DATABASE IF NOT EXISTS `domino` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `domino`;

-- Dumping structure for πίνακας domino.active_players
CREATE TABLE IF NOT EXISTS `active_players` (
  `username` varchar(20) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table domino.active_players: ~0 rows (approximately)

-- Dumping structure for πίνακας domino.board
CREATE TABLE IF NOT EXISTS `board` (
  `tile` varchar(10) DEFAULT NULL,
  `tile` enum('00','01','02','03','04','05','06','11','12','13','14','15','16','22','23','24','25','26','33','34','35','36','44,','45','46','55','56','66') DEFAULT NULL,
  `last_change` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table domino.board: ~56 rows (approximately)
INSERT INTO `board` (`tile`, `last_change`) VALUES
	('1', '2023-01-04 17:22:28'),
	('2', '2023-01-04 17:22:28'),
	('3', '2023-01-04 17:22:28'),
	('4', '2023-01-04 17:22:28'),
	('5', '2023-01-04 17:22:28'),
	('6', '2023-01-04 17:22:28'),
	('7', '2023-01-04 17:22:28'),
	('8', '2023-01-04 17:22:28'),
	('9', '2023-01-04 17:22:28'),
	('10', '2023-01-04 17:22:28'),
	('11', '2023-01-04 17:22:28'),
	('12', '2023-01-04 17:22:28'),
	('13', '2023-01-04 17:22:28'),
	('14', '2023-01-04 17:22:28'),
	('15', '2023-01-04 17:22:28'),
	('16', '2023-01-04 17:22:28'),
	('17', '2023-01-04 17:22:28'),
	('18', '2023-01-04 17:22:28'),
	('19', '2023-01-04 17:22:28'),
	('20', '2023-01-04 17:22:28'),
	('21', '2023-01-04 17:22:28'),
	('22', '2023-01-04 17:22:28'),
	('23', '2023-01-04 17:22:28'),
	('24', '2023-01-04 17:22:28'),
	('25', '2023-01-04 17:22:28'),
	('26', '2023-01-04 17:22:28'),
	('27', '2023-01-04 17:22:28'),
	('28', '2023-01-04 17:22:28');

-- Dumping structure for procedure domino.clean_board
DELIMITER //
CREATE PROCEDURE `clean_board`()
BEGIN
REPLACE INTO board SELECT * FROM board_empty;
END//
DELIMITER ;

-- Dumping structure for πίνακας domino.game_status
CREATE TABLE IF NOT EXISTS `game_status` (
  `status` enum('not active','initialized','started','\r\nended','aborded') NOT NULL DEFAULT 'not active',
  `last_change` timestamp NULL DEFAULT NULL,
  `result` enum('1','2','3','4','D') DEFAULT NULL,
  `p_turn` enum('1','2','3','4') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table domino.game_status: ~0 rows (approximately)

-- Dumping structure for πίνακας domino.players
CREATE TABLE IF NOT EXISTS `players` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=185446 DEFAULT CHARSET=utf8;

-- Dumping data for table domino.players: ~4 rows (approximately)
INSERT INTO `players` (`id`, `username`, `password`) VALUES
	(1, 'user1', '333333'),
	(2, 'user2', '444444'),
	(3, 'user3', '222222'),
	(4, 'user4', '111111');

-- Dumping structure for πίνακας domino.state
CREATE TABLE IF NOT EXISTS `state` (
  `gameID` int(6) NOT NULL AUTO_INCREMENT,
  `curState` mediumtext DEFAULT NULL,
  `player1` varchar(20) NOT NULL,
  `player2` varchar(20) NOT NULL,
  PRIMARY KEY (`gameID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table domino.state: ~0 rows (approximately)

-- Dumping structure for πίνακας domino.tiles
CREATE TABLE IF NOT EXISTS `tiles` (
  `numTiles` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table domino.tiles: ~7 rows (approximately)
INSERT INTO `tiles` (`numTiles`) VALUES
	(0),
	(1),
	(2),
	(3),
	(4),
	(5),
	(6);

-- Dumping structure for trigger domino.game_status_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER game_status_update BEFORE UPDATE
ON game_status
FOR EACH ROW BEGIN
SET NEW.last_change = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
