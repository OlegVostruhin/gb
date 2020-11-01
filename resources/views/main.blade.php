<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials._head')
    </head>
    <body>
        @include('partials._nav')

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>

        @include('partials._javascript')
        @include('partials._footer')

    </body>
</html>
