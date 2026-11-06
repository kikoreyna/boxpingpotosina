<!DOCTYPE html>
<html lang="<?= config('app','lang') ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= config('app','name') ?></title>
    <link href="<?= asset('css/app_print.css') ?>" rel="stylesheet">
   <!--<link href="https://fonts.googleapis.com/css?family=Libre+Barcode+39" rel="stylesheet"> -->
    <style type="text/css">
        @media print { 
            * {
                font-size: 10pt;
            }

            @page {
                size: 4.1in 5.8in;
                margin: 0.25in;
                 Orientation: portrait || landscape 
                /* size: 4.1in 5.8in || standard like A4; */
                /* A6 = 4.1in * 5.8in */
            }
        }
    </style>
</head>
<body class="">
    <?= $template->space('content') ?>
    <script>
        window.addEventListener('load', function () {
            if (!document.querySelector('.barcode39') || !document.fonts) {
                window.print();
                return;
            }

            document.fonts.load('20pt barcode39').then(function (fonts) {
                if (!fonts.length) throw new Error('No se pudo cargar la fuente del código de barras');
                window.print();
            }).catch(function () {
                alert('No se pudo cargar el código de barras. Verifica la conexión y vuelve a intentar.');
            });
        });
    </script>
</body>
</html>
