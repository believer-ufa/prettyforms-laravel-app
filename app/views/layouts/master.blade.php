<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title>PrettyForms Laravel test app</title>

        @if (Config::get('app.debug'))
            <link href="/assets/application.css" rel="stylesheet">

            <script src="/assets/bower/jquery/jquery.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/affix.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/alert.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/button.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/carousel.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/collapse.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/dropdown.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/modal.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/tooltip.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/popover.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/scrollspy.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/tab.js"></script>
            <script src="/assets/bower/bootstrap-sass-official/transition.js"></script>
            <script src="/assets/bower/underscore/underscore.js"></script>
            <script src="/assets/bower/backbone/backbone.js"></script>
            <script src="/assets/bower/prettyforms/prettyforms.js"></script>
        @else
            <link href="/assets/application.css" rel="stylesheet">
            <script src="/assets/application.js"></script>
        @endif

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
         <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
         <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
       <![endif]-->
    </head>
    <body>
        <div class="container">
            <!-- Static navbar -->
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ URL::to('') }}">Тестовое приложение для PrettyForms</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            @section('menulinks')
                            @show
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                            <li><a href="/auth/register">Регистрация</a></li>
                            <li><a href="/auth/login">Войти</a></li>
                        @else
                            <li><a href="/auth/logout">Выйти</a></li>
                        @endif
                        </ul>

                    </div><!--/.nav-collapse -->
                </div><!--/.container-fluid -->
            </nav>

            <div id="content">
                @yield('content')
            </div>
        </div>
    </body>
</html>