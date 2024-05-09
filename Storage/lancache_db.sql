

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `lancache_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_logs`
--

CREATE TABLE `access_logs` (
  `LID` int(11) NOT NULL,
  `LogDate` datetime NOT NULL DEFAULT current_timestamp(),
  `Upstream` varchar(100) NOT NULL,
  `LStatus` varchar(20) NOT NULL,
  `IP` varchar(15) DEFAULT NULL,
  `Bytes` bigint(20) UNSIGNED DEFAULT NULL,
  `App` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cache_disk`
--

CREATE TABLE `cache_disk` (
  `Location` VARCHAR(4) NOT NULL,
  `GBUsed` int(11) NOT NULL,
  `GBFree` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cache_disk`
--

INSERT INTO `cache_disk` (`Location`, `GBUsed`, `GBFree`) VALUES
('data', 0, 0),
('logs', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `steamapps`
--

CREATE TABLE `steamapps` (
  `AppID` int(11) NOT NULL,
  `AppName` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `steamapps`
--

INSERT INTO `steamapps` (`AppID`, `AppName`) VALUES
(735, 'Counter-Strike 2'),
(578082, 'PUBG: BATTLEGROUNDS'),
(945361, 'Among Us'),
(1899671, 'Grand Theft Auto V'),
(2347770, 'Counter-Strike 2'),
(2347771, 'Counter-Strike 2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache_disk`
--
ALTER TABLE `cache_disk`
  ADD PRIMARY KEY (`Location`);

--
-- Indexes for table `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`LID`);

ALTER TABLE `lancachestats`.`access_logs` 
  ADD INDEX `TRACE` USING BTREE (`LogDate`, `LStatus`) VISIBLE;
;

--
-- Indexes for table `steamapps`
--
ALTER TABLE `steamapps`
  ADD UNIQUE KEY `AppID` (`AppID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `LID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
