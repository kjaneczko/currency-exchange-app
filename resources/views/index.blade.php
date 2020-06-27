<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="author" content="Kacper Janeczko">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

        <title>Currency Exchange App by Kacper Janeczko</title>
    </head>
    <body>
        <div id="app" class="mt-30"></div>
    </body>
    <script src="{{ asset('js/app.js') }}"></script>
</html>
