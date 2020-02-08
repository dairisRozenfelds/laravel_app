<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Homework</title>
    <meta name="description" content="Homework">
    <meta name="author" content="Dairis Rozenfelds">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="Main">
        <div class="Header">
            <div class="Header__title">
                <h1>Valūtu kursi</h1>
                <h2>Dairis Rozenfelds</h2>
            </div>
        </div>
        <div class="App" id="app"></div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
