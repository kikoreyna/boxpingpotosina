CREATE TABLE IF NOT EXISTS `bitacora` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `accion` varchar(20) COLLATE utf8mb4_bin NOT NULL DEFAULT 'creacion',
  `tabla` varchar(40) COLLATE utf8mb4_bin NOT NULL,
  `registro_id` int UNSIGNED DEFAULT NULL,
  `detalle` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `cambios` text COLLATE utf8mb4_bin,
  `usuario_id` int UNSIGNED DEFAULT NULL,
  `usuario_nombre` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `usuario_tipo` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bitacora_tabla` (`tabla`, `created_at`),
  KEY `bitacora_usuario` (`usuario_id`, `created_at`),
  KEY `bitacora_fecha` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
