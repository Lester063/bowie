<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!--  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous"> -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        @spladeHead
        <style>
            .editButton {
                background-color:#aeaadf;
                color:#fff;
                padding:5px;
                border-radius:5px;
            }
            .greenButton {
                background-color:green;
                color:#fff;
                width:fit-content;
                padding:5px;
                border-radius:5px;
            }
            .redButton {
                background-color:red;
                color:#fff;
                width:fit-content;
                padding:5px;
                border-radius:5px;
            }
            .genButton {
                background-color:#aeaadf;
                padding:10px;
                border-radius:5px;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        @splade
    </body>

<script>

</script>

</html>
