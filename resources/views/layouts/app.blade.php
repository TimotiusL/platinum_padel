<!DOCTYPE html>
<html>

<head>

    @include('components.head')

</head>

<body>

    @include('components.navbar')

    <main>

        @yield('content')

    </main>

    @include('components.footer')

    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')

</body>

</html>