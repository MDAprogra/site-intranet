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
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

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

        header {
            background-color: #1f2937;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        nav a {
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #f3f4f6;
        }

        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .nav-links a {
            position: relative;
            padding-bottom: 2px;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: #f3f4f6;
            left: 0;
            bottom: 0;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease-out;
        }

        .nav-links a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            z-index: 20;
        }

        .dropdown-menu a {
            transition: background 0.2s ease;
        }

        .dropdown-menu a:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .dropdown-button:hover {
            color: #f3f4f6;
        }

        main {
            background-color: #111827;
        }

        footer {
            background-color: #1f2937;
        }

        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
                margin-top: 1rem;
            }

            .nav-links a {
                margin: 0;
            }

            header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
<header class="w-full py-4 px-6 flex flex-col sm:flex-row sm:items-center">
    <div class="flex items-center mb-3 sm:mb-0">
        <img src="{{ asset('images/logo-interfas.png') }}" alt="Logo Interfas" class="h-10 mr-3">
        <h1 class="text-xl font-semibold text-white">Intranet</h1>
    </div>

    <nav class="ml-auto nav-links" aria-label="Navigation principale">
        <a href="https://interfas.myyellowboxcrm.com/" target="_blank">YellowboxCRM</a>
        <a href="http://192.168.1.58:8180/webquartz/" target="_blank">Horoquartz</a>

        <div class="relative" x-data="{ open: false, timeout: null }"
             @mouseenter="clearTimeout(timeout); open = true"
             @mouseleave="timeout = setTimeout(() => open = false, 300)">
            <button class="dropdown-button text-gray-300 flex items-center" aria-haspopup="true" :aria-expanded="open">
                Transporteurs
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="absolute dropdown-menu mt-2 w-48"
                 x-show="open"
                 x-transition.opacity.duration.200ms
                 @click.outside="open = false">
                <a href="https://www.tnt.fr/public/login/index.do" class="block px-4 py-2 text-gray-800">TNT</a>
                <a href="https://www.dbschenker.com/fr-fr" class="block px-4 py-2 text-gray-800">DB SCHENKER</a>
                <a href="https://connect.gefco.net/psc-portal/login.html#LogIn" class="block px-4 py-2 text-gray-800">GEFCO</a>
                <a href="http://www.dpd.fr/trace" class="block px-4 py-2 text-gray-800">DPD</a>
            </div>
        </div>

        <a href="https://eprint.interfas.fr/" target="_blank">E-Print</a>
        <a href="https://www.esupply.valeo.com/" target="_blank">Valeo</a>
        <a href="https://auscp.aperam.com/oauth2/authorize?response_type=code&client_id=c2e184e7-af79-420f-90d6-c3bfa6b95449"
           target="_blank">Aperam</a>
        <a href="https://armoires.zeendoc.com/interfas/" target="_blank">Zeendoc</a>
        <a href="https://shop.bluestoreinc.com/fr" target="_blank">Bluestar</a>
        <a href="https://client.interfas.fr/proxiserve/" target="_blank">Proxyserve</a>

        @if(Auth::user())
            <a href="{{ route('indicateur') }}" class="font-bold">Tableau de bord</a>
        @else
        <a href="{{ route('login') }}">Connexion</a>
        @endif
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

<footer class="w-full py-4 text-center text-gray-400 text-sm">
    &copy; 2025 Interfas SAS. Tous droits réservés.
</footer>
</body>
</html>
