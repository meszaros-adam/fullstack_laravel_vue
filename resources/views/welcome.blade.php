<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ mix('css/all.css') }}" rel="stylesheet">

        <title>Laravel</title>

        <script>
            (function(){
                window.Laravel = {
                    csrfToken: '{{ csrf_token() }}'
                };
            })();
        </script>

    </head>
    <body>
        <div id="app">
            @if(Auth::check())
            <mainapp :user="{{Auth::user()}}" :permission="{{Auth::user()->role->permission}}"></mainapp>
            @else
            <mainapp :user="false"></mainapp>
            @endif
        </div>        
        <script src="{{mix('/js/app.js')}}"></script>
    </body>
</html>
