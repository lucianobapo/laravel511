<html>
    <head>
        <title>Laravel</title>

        <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
        <link href="{{ elixir('css/app.compiled.css') }}" rel="stylesheet">
        <style>
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 500;
                font-family: 'Lato';
            }

            .containerw {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .contentw {
                text-align: center;
                display: inline-block;
            }

            .titlew {
                font-size: 96px;
                margin-bottom: 40px;
            }

            .quotew {
                font-size: 24px;
            }
        </style>
    </head>
    <body>
        <div class="containerw">
            <div class="contentw">
                @include('flash::message')
                <div class="titlew">Laravel 5</div>
                <div class="quotew">{{ Inspiring::quote() }}</div>
            </div>
        </div>
        <script src="{{ elixir('js/app.compiled.js') }}"></script>
    </body>
</html>
