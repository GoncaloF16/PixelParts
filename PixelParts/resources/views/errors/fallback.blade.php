<!DOCTYPE html>
<html class="h-full" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>404 - Page Not Found</title>
</head>
<body class="h-full">
    <main class="grid min-h-full place-items-center bg-black-900 px-6 py-24 sm:py-32 lg:px-8">
         <div class="particles-container absolute inset-0">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
    <div class="text-center">
        <p class="text-base font-semibold text-white">404</p>
        <h1 class="mt-4 text-5xl font-semibold tracking-tight text-balance text-white sm:text-7xl">Page <span class="text-gradient-brand"> not found </span> </h1>
        <p class="mt-6 text-lg font-medium text-pretty text-gray-400 sm:text-xl/8">Sorry, we couldn’t find the page you’re looking for.</p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
        <a href="{{ route('home') }}" class="bg-gradient-to-r from-brand-green to-brand-blue rounded-md px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs focus-visible:outline-2 focus-visible:outline-offset-2">Go back home</a>
        <a href="#" class="text-sm font-semibold text-white">Contact support <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>
    </main>
</body>
</html>
