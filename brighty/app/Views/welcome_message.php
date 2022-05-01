<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />

    <!-- Splash Screen/Loader Styles -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('/loader.css');  ?>" />

    <link rel="icon" href="<?= base_url('/favicon.ico');  ?>" />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap"
      rel="stylesheet"
    />
    <title>Vuexy - Vuejs Admin Dashboard Template</title>
  </head>
  <body>
    <noscript>
      <strong
        >We're sorry but our sites requires JavaScript enabled. Please
        enable it to continue.</strong
      >
    </noscript>
    <div id="loading-bg">
      <div class="loading-logo">
        <img src="<?= base_url('/logo.png');  ?>" alt="Logo" style="max-width:200px" />
      </div>
      <div class="loading">
        <div class="effect-1 effects"></div>
        <div class="effect-2 effects"></div>
        <div class="effect-3 effects"></div>
      </div>
    </div>
    <div id="app"></div>
    <!-- built files will be auto injected -->
  </body>
</html>
