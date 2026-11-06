CREATE TABLE IF NOT EXISTS `tareas` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `entrada_id` int UNSIGNED NOT NULL,
  `usuario_id` int UNSIGNED NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `asignado_by` int UNSIGNED NOT NULL,
  `estatus` varchar(20) COLLATE utf8mb4_bin NOT NULL DEFAULT 'pendiente',
  `completado_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tareas_usuario_estatus` (`usuario_id`, `estatus`),
  KEY `tareas_entrada` (`entrada_id`),
  CONSTRAINT `tareas_entrada_fk` FOREIGN KEY (`entrada_id`) REFERENCES `entradas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
