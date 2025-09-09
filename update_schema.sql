--
-- Table structure for table `jalur_dokumen_wajib`
--

CREATE TABLE `jalur_dokumen_wajib` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jalur_id` int(11) NOT NULL,
  `jenis_dokumen` enum('foto','rapor','sktm','prestasi') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jalur_id` (`jalur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jalur_dokumen_wajib`
--

INSERT INTO `jalur_dokumen_wajib` (`jalur_id`, `jenis_dokumen`) VALUES
(1, 'foto'),
(1, 'rapor'),
(2, 'foto'),
(2, 'rapor'),
(2, 'prestasi'),
(3, 'foto'),
(3, 'rapor'),
(3, 'sktm');

--
-- Constraints for table `jalur_dokumen_wajib`
--
ALTER TABLE `jalur_dokumen_wajib`
  ADD CONSTRAINT `jalur_dokumen_wajib_ibfk_1` FOREIGN KEY (`jalur_id`) REFERENCES `jalur` (`id`) ON DELETE CASCADE;
