<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Title</title>
    <link rel="stylesheet" href="{{ mix('vue-element-admin/app.css') }}">
</head>
<body>
<div id="app"></div>
</body>
<script src="{{ mix('vue-element-admin/main.js') }}"></script>
</html>
