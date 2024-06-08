<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        {{ $slot }}
        <script type="module">
            import { Engine } from '{{ env('FLOATY_URL') }}/engine-v1.js?t={{ now()->timestamp }}';
            window.Engine = Engine;
        </script>
        @vite('resources/js/app.js')
    </body>
</html>
