-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2020 at 06:08 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `IdK` int(11) NOT NULL,
  `Imejl` varchar(50) NOT NULL,
  `Sifra` varchar(30) NOT NULL,
  `Ime` varchar(30) NOT NULL,
  `Prezime` varchar(30) NOT NULL,
  `Adresa` varchar(30) NOT NULL,
  `Grad` varchar(30) NOT NULL,
  `Drzava` varchar(30) NOT NULL,
  `PostBroj` int(11) NOT NULL,
  `IdR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`IdK`, `Imejl`, `Sifra`, `Ime`, `Prezime`, `Adresa`, `Grad`, `Drzava`, `PostBroj`, `IdR`) VALUES
(0, 'admin@gmail.com', 'admin123', 'admin', 'admin', 'Nepoznato', 'Nepoznato', 'Nepoznato', 0, 0),
(2, 'moderator@gmail.com', 'moderator', 'moderator', 'moderator', 'X', 'X', 'X', 11000, 3),
(3, 'verifikovani@gmail.com', 'verifikovani', 'verifikovani', 'verifikovani', 'X', 'X', 'X', 11000, 2),
(4, 'vlade10lekic18@gmail.com', 'sifra', 'Vlade', 'Lekic', 'Decanska 17', 'Uzice', 'Srbija', 31000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kupovina`
--

CREATE TABLE `kupovina` (
  `IdK` int(11) DEFAULT NULL,
  `IdO` int(11) NOT NULL,
  `IdKup` int(11) NOT NULL,
  `Datum` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `IdN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `nacinkupovine`
--

CREATE TABLE `nacinkupovine` (
  `IdN` int(11) NOT NULL,
  `Opis` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `oglas`
--

CREATE TABLE `oglas` (
  `IdO` int(11) NOT NULL,
  `IdK` int(11) NOT NULL,
  `IdS` int(11) NOT NULL,
  `Autor` varchar(50) NOT NULL,
  `Naslov` varchar(40) NOT NULL,
  `Opis` varchar(200) DEFAULT NULL,
  `Cena` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `oglastag`
--

CREATE TABLE `oglastag` (
  `IdO` int(11) NOT NULL,
  `IdT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `poruka`
--

CREATE TABLE `poruka` (
  `Korisnik2` int(11) NOT NULL,
  `Korisnik1` int(11) NOT NULL,
  `IdPo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prijava`
--

CREATE TABLE `prijava` (
  `IdPr` int(11) NOT NULL,
  `IdO` int(11) NOT NULL,
  `IdK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `razgovor`
--

CREATE TABLE `razgovor` (
  `Korisnik2` int(11) NOT NULL,
  `Korisnik1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rola`
--

CREATE TABLE `rola` (
  `IdR` int(11) NOT NULL,
  `Opis` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rola`
--

INSERT INTO `rola` (`IdR`, `Opis`) VALUES
(0, 'Admin'),
(1, 'Korisnik'),
(2, 'Verifikovani'),
(3, 'Moderator');

-- --------------------------------------------------------

--
-- Table structure for table `stanjeoglasa`
--

CREATE TABLE `stanjeoglasa` (
  `IdS` int(11) NOT NULL,
  `Opis` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `IdT` int(11) NOT NULL,
  `Opis` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zahtevfajl`
--

CREATE TABLE `zahtevfajl` (
  `IdZ` int(11) NOT NULL,
  `Fajl` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zahtevver`
--

CREATE TABLE `zahtevver` (
  `IdZ` int(11) NOT NULL,
  `Stanje` varchar(20) NOT NULL,
  `Podneo` int(11) NOT NULL,
  `Odobrio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`IdK`),
  ADD KEY `R_1` (`IdR`);

--
-- Indexes for table `kupovina`
--
ALTER TABLE `kupovina`
  ADD PRIMARY KEY (`IdKup`),
  ADD KEY `R_22` (`IdK`),
  ADD KEY `R_24` (`IdO`),
  ADD KEY `R_26` (`IdN`);

--
-- Indexes for table `nacinkupovine`
--
ALTER TABLE `nacinkupovine`
  ADD PRIMARY KEY (`IdN`);

--
-- Indexes for table `oglas`
--
ALTER TABLE `oglas`
  ADD PRIMARY KEY (`IdO`),
  ADD KEY `R_6` (`IdK`),
  ADD KEY `R_27` (`IdS`);

--
-- Indexes for table `oglastag`
--
ALTER TABLE `oglastag`
  ADD PRIMARY KEY (`IdO`,`IdT`),
  ADD KEY `R_9` (`IdT`);

--
-- Indexes for table `poruka`
--
ALTER TABLE `poruka`
  ADD PRIMARY KEY (`IdPo`),
  ADD KEY `R_18` (`Korisnik2`,`Korisnik1`);

--
-- Indexes for table `prijava`
--
ALTER TABLE `prijava`
  ADD PRIMARY KEY (`IdPr`),
  ADD KEY `R_10` (`IdO`),
  ADD KEY `R_11` (`IdK`);

--
-- Indexes for table `razgovor`
--
ALTER TABLE `razgovor`
  ADD PRIMARY KEY (`Korisnik2`,`Korisnik1`),
  ADD KEY `R_17` (`Korisnik1`);

--
-- Indexes for table `rola`
--
ALTER TABLE `rola`
  ADD PRIMARY KEY (`IdR`);

--
-- Indexes for table `stanjeoglasa`
--
ALTER TABLE `stanjeoglasa`
  ADD PRIMARY KEY (`IdS`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`IdT`);

--
-- Indexes for table `zahtevfajl`
--
ALTER TABLE `zahtevfajl`
  ADD PRIMARY KEY (`IdZ`);

--
-- Indexes for table `zahtevver`
--
ALTER TABLE `zahtevver`
  ADD PRIMARY KEY (`IdZ`),
  ADD KEY `R_12` (`Podneo`),
  ADD KEY `R_13` (`Odobrio`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD CONSTRAINT `R_1` FOREIGN KEY (`IdR`) REFERENCES `rola` (`IdR`);

--
-- Constraints for table `kupovina`
--
ALTER TABLE `kupovina`
  ADD CONSTRAINT `R_22` FOREIGN KEY (`IdK`) REFERENCES `korisnik` (`IdK`),
  ADD CONSTRAINT `R_24` FOREIGN KEY (`IdO`) REFERENCES `oglas` (`IdO`),
  ADD CONSTRAINT `R_26` FOREIGN KEY (`IdN`) REFERENCES `nacinkupovine` (`IdN`);

--
-- Constraints for table `oglas`
--
ALTER TABLE `oglas`
  ADD CONSTRAINT `R_27` FOREIGN KEY (`IdS`) REFERENCES `stanjeoglasa` (`IdS`),
  ADD CONSTRAINT `R_6` FOREIGN KEY (`IdK`) REFERENCES `korisnik` (`IdK`);

--
-- Constraints for table `oglastag`
--
ALTER TABLE `oglastag`
  ADD CONSTRAINT `R_8` FOREIGN KEY (`IdO`) REFERENCES `oglas` (`IdO`),
  ADD CONSTRAINT `R_9` FOREIGN KEY (`IdT`) REFERENCES `tag` (`IdT`);

--
-- Constraints for table `poruka`
--
ALTER TABLE `poruka`
  ADD CONSTRAINT `R_18` FOREIGN KEY (`Korisnik2`,`Korisnik1`) REFERENCES `razgovor` (`Korisnik2`, `Korisnik1`);

--
-- Constraints for table `prijava`
--
ALTER TABLE `prijava`
  ADD CONSTRAINT `R_10` FOREIGN KEY (`IdO`) REFERENCES `oglas` (`IdO`),
  ADD CONSTRAINT `R_11` FOREIGN KEY (`IdK`) REFERENCES `korisnik` (`IdK`);

--
-- Constraints for table `razgovor`
--
ALTER TABLE `razgovor`
  ADD CONSTRAINT `R_16` FOREIGN KEY (`Korisnik2`) REFERENCES `korisnik` (`IdK`),
  ADD CONSTRAINT `R_17` FOREIGN KEY (`Korisnik1`) REFERENCES `korisnik` (`IdK`);

--
-- Constraints for table `zahtevfajl`
--
ALTER TABLE `zahtevfajl`
  ADD CONSTRAINT `R_14` FOREIGN KEY (`IdZ`) REFERENCES `zahtevver` (`IdZ`);

--
-- Constraints for table `zahtevver`
--
ALTER TABLE `zahtevver`
  ADD CONSTRAINT `R_12` FOREIGN KEY (`Podneo`) REFERENCES `korisnik` (`IdK`),
  ADD CONSTRAINT `R_13` FOREIGN KEY (`Odobrio`) REFERENCES `korisnik` (`IdK`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
