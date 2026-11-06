CREATE TABLE IF NOT EXISTS `bodegas_mex` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `direccion` varchar(160) COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `entradas` ADD COLUMN `bodega_mex_id` int UNSIGNED DEFAULT NULL AFTER `en_bodega_mex_by`;
