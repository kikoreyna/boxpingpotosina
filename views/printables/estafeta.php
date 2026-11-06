<!DOCTYPE html>
<html lang="<?= config('app','lang') ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= config('app','name') ?></title>
    <link href="<?= asset('css/app_print.css?v=1.1') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Barcode+39" rel="stylesheet">
    <style type="text/css"> 
        @media print { 
            * {
                font-size: 9pt;
            }

            @page {
                size: 4.6in 6in;
                margin-top: 3in;
            }
            #print-area {
                font-size: 10pt !important;
                width: 2.5in;
            }
        } 
    </style>
</head>
<body onload="window.print()">    
    <?= $template->space('content') ?>
</body>
</html>