<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- for ios 7 style, multi-resolution icon of 152x152 -->
    <!-- <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
    <link rel="apple-touch-icon" href="/flatkit/assets/images/logo.png">
    <meta name="apple-mobile-web-app-title" content="Flatkit"> -->
    <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
    <!-- <meta name="mobile-web-app-capable" content="yes">
    <link rel="shortcut icon" sizes="196x196" href="/flatkit/assets/images/logo.png"> -->

    <!-- style -->
    <link rel="stylesheet" type="text/css" href="/css/vuetify.min.css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
</head>
<body>
  <div id="app">
    <App></App>
  </div>
{{-- / setting value to your CSRFglobal variables  --}}
<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
        ]); ?>
</script>

<script src="/js/app.js"></script>

<!-- <script src="/js/taggd.min.js"></script> -->

</body>
</html>
