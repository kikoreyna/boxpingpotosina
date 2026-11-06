ALTER TABLE `usuarios`
  MODIFY `tipo` ENUM('superadministrador','administrador','documentador','bodega_usa','bodega_mex','cliente') NOT NULL;

UPDATE `usuarios` SET `tipo` = 'superadministrador' WHERE `id` = 1;
