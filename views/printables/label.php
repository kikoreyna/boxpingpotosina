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
<body class="" onload="window.print()">    
    <?= $template->space('content') ?>
</body>
</html>
