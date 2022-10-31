<?php $cspnonce = base64_encode(bin2hex(random_bytes(64))); ?>
<?php header("content-security-policy: default-src 'self'; script-src 'self' 'nonce-" . $cspnonce . "'; img-src 'self' data: https://*.openstreetmap.org") ?>
<html>
    <head>
        <title><?php echo SITE_TITLE_TRACKER_APP ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="<?php echo SITE_ROBOTS ?>">
        <meta http-equiv="x-ua-compatible" content="IE=edge">
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="120x120" href="../../apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../favicon-16x16.png">
        <link rel="manifest" href="../../site.webmanifest">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../media/css/bootstrap.min.css" integrity="sha384-2diKOETIi1xfrzQsm1wbWyFuiEELWcgL5bZMfLj0fZPSNrBaPVYW5sZfu2hBpKve" crossorigin="anonymous">
        <link rel="stylesheet" href="../../media/css/fontawesome.css" />
        <link rel="stylesheet" href="../../media/css/app.css" />
    </head>
    <body>
