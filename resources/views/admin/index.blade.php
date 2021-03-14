@php
/**
 * 管理画面用テンプレート
 */
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no" />
  <meta name="robots" content="noindex,nofollow,noarchive" />
  <title>{{ config('app.name') }} | 管理画面</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <!--link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet"-->
  <!--script src="https://use.fontawesome.com/releases/v5.6.1/css/all.css"></script-->
  <script src="{{ asset(mix('js/admin/app.js')) }}" defer></script>
</head>
<body>
  <div id="app"></div>
</body>
</html>
