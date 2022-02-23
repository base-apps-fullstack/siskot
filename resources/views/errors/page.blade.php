<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <title>@yield('title')</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:300">
  <style type="text/css">
    html, body {
      height: 100%;
    }

    body {
      margin: 0;
      padding: 0;
      width: 100%;
      color: #b0bec5;
      display: table;
      font-weight: 300;
      font-family: 'Lato', sans-serif;
    }

    .container {
      text-align: center;
      display: table-cell;
      vertical-align: middle;
    }

    .content {
      text-align: center;
      display: inline-block;
    }

    .title {
      font-size: 72px;
      margin-bottom: 40px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="content">
      <div class="title">@yield('title')</div>
    </div>
  </div>
</body>
</html>
