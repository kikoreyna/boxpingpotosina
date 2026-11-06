<?php

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: text/csv");
header("Content-Transfer-Encoding: binary");
header("Content-Length: {$filesize}");
// header("Content-Type: application/zip");

// Read the file
readfile($filepath);

// https://www.jose-aguilar.com/blog/forzar-la-descarga-archivos-php/
// https://parasitovirtual.wordpress.com/2010/06/15/curso-php-capitulo-10-subida-y-descarga-de-ficheros/
// https://www.sololinux.es/descargar-archivos-con-php/
// https://programacion.net/articulo/como_forzar_la_descarga_de_un_fichero_en_php_1935
// https://mimentevuela.wordpress.com/2015/01/20/descarga-de-archivos-con-php/