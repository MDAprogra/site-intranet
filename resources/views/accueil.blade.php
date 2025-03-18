<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('icon-interfas.ico') }}" type="image/x-icon"/>
    <title>Intranet - Interfas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 1.5s ease-out forwards;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
<header class="w-full bg-gray-800 shadow-md py-4 px-6 flex items-center">
    <img src="{{ asset('images/logo-interfas.png') }}" alt="Logo Interfas" class="h-10 mr-3">

    <h1 class="text-xl font-semibold text-white">Intranet</h1>

    <nav class="ml-auto flex space-x-4">
        <a href="https://interfas.myyellowboxcrm.com/" target="_blank" class="text-gray-300 hover:text-white">YellowboxCRM</a>
        <a href="http://192.168.1.58:8180/webquartz/" target="_blank" class="text-gray-300 hover:text-white">Horoquartz</a>
        <div class="relative" x-data="{ open: false, timeout: null }"
             @mouseenter="clearTimeout(timeout); open = true"
             @mouseleave="timeout = setTimeout(() => open = false, 300)">

            <button class="text-gray-300 hover:text-white flex items-center">
                Transporteurs
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div class="absolute bg-white text-gray-900 shadow-md rounded-md mt-2 w-48"
                 x-show="open"
                 x-transition.opacity.duration.200ms>
                <a href="https://www.tnt.fr/public/login/index.do" class="block px-4 py-2 hover:bg-gray-200">TNT</a>
                <a href="https://www.dbschenker.com/fr-fr" class="block px-4 py-2 hover:bg-gray-200">DB SCHENKER</a>
                <a href="https://connect.gefco.net/psc-portal/login.html#LogIn" class="block px-4 py-2 hover:bg-gray-200">GEFCO</a>
            </div>
        </div>

        <a href="https://eprint.interfas.fr/" target="_blank" class="text-gray-300 hover:text-white">E-Print</a>
        <a href="https://www.esupply.valeo.com/" target="_blank" class="text-gray-300 hover:text-white">Valeo</a>
        <a href="https://auscp.aperam.com/oauth2/authorize?response_type=code&client_id=c2e184e7-af79-420f-90d6-c3bfa6b95449" target="_blank" class="text-gray-300 hover:text-white">Aperam</a>
        <a href="https://armoires.zeendoc.com/interfas/" target="_blank" class="text-gray-300 hover:text-white">Zeendoc</a>
        <a href="https://shop.bluestoreinc.com/fr" target="_blank" class="text-gray-300 hover:text-white">Bluestar</a>
        <a href="https://client.interfas.fr/proxiserve/" target="_blank" class="text-gray-300 hover:text-white">Proxyserve</a>
        <a href="{{ url('/indicateur') }}" class="text-gray-300 hover:text-white">Indicateurs</a>
    </nav>
</header>

<main class="flex-grow flex items-center justify-center">
    <div class="container mx-auto px-6 lg:px-20 flex justify-center">
        <div class="text-center fade-in">
            <h2 class="text-4xl font-bold mb-4 text-white drop-shadow-lg">Bienvenue sur l'Intranet</h2>
            <p class="text-gray-300 drop-shadow">Accédez à vos outils et ressources internes.</p>
        </div>
    </div>
</main>

<footer class="w-full bg-gray-800 py-4 text-center text-gray-400 text-sm">
    &copy; 2025 Interfas SAS. Tous droits réservés.
</footer>
</body>
</html>
