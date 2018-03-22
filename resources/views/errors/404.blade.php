<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        zoo.ru
    </title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <style>
        body {
            background: #00203b!important;
        }
        h1 {
            font-size: 5em!important;
            color: #fff!important;
        }
        .error-template {padding: 50px 15px;text-align: left;}
        .error-details {color: #fff!important;}
        .error-actions {margin-top:15px;margin-bottom:15px;}
        .error-actions .btn { margin-right:10px; }
        .little_stars {
            background: url('/images/little_stars.svg') repeat;
            position: fixed;
            overflow: hidden;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: -4;
        }
        .planetdog {
            position: fixed;
            overflow: hidden;
            bottom: -40%;
            right: -10%;
            z-index: -2;
        }
        .roundfly {
            position: absolute;
            transition: all 0.1s ease-in-out;
            z-index: -4;
            width: 100px;
        }
        #round {
            position: fixed;
            right: 0;
            bottom: 0;
            z-index: -4;
            width: 70%;
            height: 70%;
        }
    </style>
    <!-- Styles -->
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<div id="app">

    {{--TODO на проде раскоментить--}}
    @include('partials.header')
    @include('partials.menu')

    <div class="container">
        {{--<div class="row">--}}
            {{--<div class="col-md-12">--}}
                {{--<a id="logo-white" class="logo" href="/"></a>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--@include('partials.menu')--}}

        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>Oops! 404</h1>
                    <div class="error-details">
                        страница не найдена
                    </div>
                    <div class="error-actions">
                        <a href="/" class="btn btn-green btn-sqaure" style="max-width: 300px">На главную</a>
                    </div>
                    <div class="transform__mm little_stars" data-offset="15"></div>
                    <img class="transform__mm planetdog" data-offset="95" src="/images/planetdog.svg"/>
                </div>
                <div id="round">
                    <img class="transform__mm roundfly" data-offset="25" src="/images/ufo.svg" id="ufo"/>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="/js/app.js"></script>
</body>
</html>


<script>
    const mouseMoveEls = Array.from(document.getElementsByClassName('transform__mm'));
    document.addEventListener( 'mousemove', (e) => {
        const width = window.outerWidth;
        const height = window.outerHeight;
        const offsetX = 0.5 - e.pageX / width;
        const offsetY = 0.5 - e.pageY / height;
        mouseMoveEls.forEach((el, i) => {
            const dataOffset = parseInt(el.getAttribute('data-offset'));
            const translate = `translate3d(${Math.round(offsetX * dataOffset)}px, ${Math.round(offsetY * dataOffset)}px, 0px)`;
            el.style.webkitTransform = translate;
            el.style.MozTransform = translate;
            el.style.msTransform = translate;
            el.style.OTransform = translate;
            el.style.transform = translate;
        });
    });

    function animation(elem) { // некоторые аргументы определим на будущее
        let $ = {
            radius  :     250, // радиус окружности
            speed   :     50 // скорость/задержка ( в js это мс, например 10 мс = 100 кадров в секунду)
        }
        let f = 0;
        let s = 2 * Math.PI / 180;
        setInterval(function() {
            f -= s; // приращение аргумента
            elem.style.left =  235 + $.radius * Math.sin(f)  + 'px';
            elem.style.top =   205 + $.radius * Math.cos(f) + 'px';
            elem.style.transform = 'rotate('+$.radius *Math.sin(f)+'deg)';
        }, $.speed)
    }

    animation(document.getElementById('ufo'));
</script>