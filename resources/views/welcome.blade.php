<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
              integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <script
            src="http://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
    <div class="authId" hidden>{{Auth::id()}}</div>
    <div class="authName" hidden>{{Auth::user()->name}}</div>
        <div class="flex-center position-ref">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
                <div class="container">
                    <h1 class="jumbotron-heading align-content-center">Чатик</h1>
                    <div class="form-group">
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <button class="btn btn-info" onclick="send()">отправить</button>
                    <br><br>
                    <div class="row">
                        @foreach($messages as $message)
                            <div class="col-lg-12">
                                <div class="card mb-4 box-shadow">
                                    <div class="card-body">
                                        <p class="card-text">{{ $message->message }}   <span class="badge badge-secondary">{{ $message->created_at }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <script>
                    var conn = new WebSocket('ws://192.168.0.105:8080');
                    conn.onopen = function (e) {
                        console.log("Connection established!");

                    };

                    conn.onmessage = function (e) {
                        console.log('Получены данные: ' + e.data);
                        appendMessage(e.data);
                    };

                    function send() {
                        var text = $('.form-control').val();
                        var authId = $('.authId').text();
                        console.log(text);
                        console.log(authId);
                        conn.send(JSON.stringify({authId: authId, message: text}));

                        appendMessage(text);
                        clearTextArea();
                    }

                    function appendMessage(text) {
                        $('.row:last').append(
                            '        <div class="col-lg-12">\n' +
                            '            <div class="card mb-4 box-shadow">\n' +
                            '                <div class="card-body">\n' +
                            '                    <p class="card-text">'+ text +'   <span class="badge badge-secondary">New</span></p>\n' +
                            '                </div>\n' +
                            '            </div>\n' +
                            '        </div>'
                        );
                    }

                    function clearTextArea() {
                        $('.form-control').val('');
                    }

                </script>
        </div>
    </body>
</html>
