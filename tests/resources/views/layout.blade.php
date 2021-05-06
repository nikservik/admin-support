<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Test</title>
  <!-- Styles -->
</head>
<body>
  <div id="app">
    <div class="content">
        <header id="nav">
            <div class="max-w-2xl mx-auto px-2 text-white">
                <a href="/dashboard" class="block font-bold text-gray-700 text-4xl py-6">
                  Testing dashboard template
                </a>
                @stack('menu')
            </div>
        </header>
        <div class="max-w-2xl mx-auto px-1 py-4" id="content">
          @yield('content')
        </div>
    </div>
  </div>
</body>
</html>
