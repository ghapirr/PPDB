CREATE TABLE `nilai_pendaftar` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pendaftaran_id` INT(11) NOT NULL,
  `mapel` VARCHAR(50) NOT NULL,
  `nilai` DECIMAL(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pendaftaran_mapel` (`pendaftaran_id`, `mapel`),
  FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;