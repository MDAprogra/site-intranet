<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
            @layer theme {
                :root, :host {
                    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                    --font-serif: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
                    --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                    --color-red-50: oklch(0.971 0.013 17.38);
                    --color-red-950: oklch(0.258 0.092 26.042);
                    --color-orange-50: oklch(0.98 0.016 73.684);
                    --color-orange-950: oklch(0.266 0.079 36.259);
                    --color-amber-50: oklch(0.987 0.022 95.277);
                    --color-amber-950: oklch(0.279 0.077 45.635);
                    --color-yellow-50: oklch(0.987 0.026 102.212);
                    --color-yellow-950: oklch(0.286 0.066 53.813);
                    --color-lime-50: oklch(0.986 0.031 120.757);
                    --color-lime-950: oklch(0.274 0.072 132.109);
                    --color-green-50: oklch(0.982 0.018 155.826);
                    --color-green-950: oklch(0.266 0.065 152.934);
                    --color-emerald-50: oklch(0.979 0.021 166.113);
                    --color-emerald-950: oklch(0.262 0.051 172.552);
                    --color-teal-50: oklch(0.984 0.014 180.72);
                    --color-teal-950: oklch(0.277 0.046 192.524);
                    --color-cyan-50: oklch(0.984 0.019 200.873);
                    --color-cyan-950: oklch(0.302 0.056 229.695);
                    --color-sky-50: oklch(0.977 0.013 236.62);
                    --color-sky-950: oklch(0.293 0.066 243.157);
                    --color-blue-50: oklch(0.97 0.014 254.604);
                    --color-blue-950: oklch(0.282 0.091 267.935);
                    --color-indigo-50: oklch(0.962 0.018 272.314);
                    --color-indigo-950: oklch(0.257 0.09 281.288);
                    --color-violet-50: oklch(0.969 0.016 293.756);
                    --color-violet-950: oklch(0.283 0.141 291.089);
                    --color-purple-50: oklch(0.977 0.014 308.299);
                    --color-purple-950: oklch(0.291 0.149 302.717);
                    --color-fuchsia-50: oklch(0.977 0.017 320.058);
                    --color-fuchsia-950: oklch(0.293 0.136 325.661);
                    --color-pink-50: oklch(0.971 0.014 343.198);
                    --color-pink-950: oklch(0.284 0.109 3.907);
                    --color-rose-50: oklch(0.969 0.015 12.422);
                    --color-rose-950: oklch(0.271 0.105 12.094);
                    --color-slate-50: oklch(0.984 0.003 247.858);
                    --color-slate-950: oklch(0.129 0.042 264.695);
                    --color-gray-50: oklch(0.985 0.002 247.839);
                    --color-gray-950: oklch(0.13 0.028 261.692);
                    --color-zinc-50: oklch(0.985 0 0);
                    --color-zinc-950: oklch(0.141 0.005 285.823);
                    --color-neutral-50: oklch(0.985 0 0);
                    --color-neutral-950: oklch(0.145 0 0);
                    --color-stone-50: oklch(0.985 0.001 106.423);
                    --color-stone-950: oklch(0.147 0.004 49.25);
                    --color-black: #000;
                    --color-white: #fff;
                    --spacing: 0.25rem;
                    --breakpoint-sm: 40rem;
                    --breakpoint-md: 48rem;
                    --breakpoint-lg: 64rem;
                    --breakpoint-xl: 80rem;
                    --breakpoint-2xl: 96rem;
                    --container-3xs: 16rem;
                    --container-2xs: 18rem;
                    --container-xs: 20rem;
                    --container-sm: 24rem;
                    --container-md: 28rem;
                    --container-lg: 32rem;
                    --container-xl: 36rem;
                    --container-2xl: 42rem;
                    --container-3xl: 48rem;
                    --container-4xl: 56rem;
                    --container-5xl: 64rem;
                    --container-6xl: 72rem;
                    --container-7xl: 80rem;
                    --text-xs: 0.75rem;
                    --text-xs--line-height: calc(1 / 0.75);
                    --text-sm: 0.875rem;
                    --text-sm--line-height: calc(1.25 / 0.875);
                    --text-base: 1rem;
                    --text-base--line-height: 1.5;
                    --text-lg: 1.125rem;
                    --text-lg--line-height: calc(1.75 / 1.125);
                    --text-xl: 1.25rem;
                    --text-xl--line-height: calc(1.75 / 1.25);
                    --text-2xl: 1.5rem;
                    --text-2xl--line-height: calc(2 / 1.5);
                    --text-3xl: 1.875rem;
                    --text-3xl--line-height: 1.2;
                    --text-4xl: 2.25rem;
                    --text-4xl--line-height: calc(2.5 / 2.25);
                    --text-5xl: 3rem;
                    --text-5xl--line-height: 1;
                    --text-6xl: 3.75rem;
                    --text-6xl--line-height: 1;
                    --text-7xl: 4.5rem;
                    --text-7xl--line-height: 1;
                    --text-8xl: 6rem;
                    --text-8xl--line-height: 1;
                    --text-9xl: 8rem;
                    --text-9xl--line-height: 1;
                    --font-weight-thin: 100;
                    --font-weight-extralight: 200;
                    --font-weight-light: 300;
                    --font-weight-normal: 400;
                    --font-weight-medium: 500;
                    --font-weight-semibold: 600;
                    --font-weight-bold: 700;
                    --font-weight-extrabold: 800;
                    --font-weight-black: 900;
                    --tracking-tighter: -0.05em;
                    --tracking-tight: -0.025em;
                    --tracking-normal: 0em;
                    --tracking-wide: 0.025em;
                    --tracking-wider: 0.05em;
                    --tracking-widest: 0.1em;
                    --leading-tight: 1.25;
                    --leading-snug: 1.375;
                    --leading-normal: 1.5;
                    --leading-relaxed: 1.625;
                    --leading-loose: 2;
                    --radius-xs: 0.125rem;
                    --radius-sm: 0.25rem;
                    --radius-md: 0.375rem;
                    --radius-lg: 0.5rem;
                    --radius-xl: 0.75rem;
                    --radius-2xl: 1rem;
                    --radius-3xl: 1.5rem;
                    --radius-4xl: 2rem;
                    --shadow-2xs: 0 1px #0000000d;
                    --shadow-xs: 0 1px 2px 0 #0000000d;
                    --shadow-sm: 0 1px 3px 0 #0000001a, 0 1px 2px -1px #0000001a;
                    --shadow-md: 0 4px 6px -1px #0000001a, 0 2px 4px -2px #0000001a;
                    --shadow-lg: 0 10px 15px -3px #0000001a, 0 4px 6px -4px #0000001a;
                    --shadow-xl: 0 20px 25px -5px #0000001a, 0 8px 10px -6px #0000001a;
                    --shadow-2xl: 0 25px 50px -12px #00000040;
                    --inset-shadow-2xs: inset 0 1px #0000000d;
                    --inset-shadow-xs: inset 0 1px 1px #0000000d;
                    --inset-shadow-sm: inset 0 2px 4px #0000000d;
                    --drop-shadow-xs: 0 1px 1px #0000000d;
                    --drop-shadow-sm: 0 1px 2px #00000026;
                    --drop-shadow-md: 0 3px 3px #0000001f;
                    --drop-shadow-lg: 0 4px 4px #00000026;
                    --drop-shadow-xl: 0 9px 7px #0000001a;
                    --drop-shadow-2xl: 0 25px 25px #00000026;
                    --ease-in: cubic-bezier(0.4, 0, 1, 1);
                    --ease-out: cubic-bezier(0, 0, 0.2, 1);
                    --ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
                    --animate-spin: spin 1s linear infinite;
                    --animate-ping: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
                    --animate-pulse: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                    --animate-bounce: bounce 1s infinite;
                    --blur-xs: 4px;
                    --blur-sm: 8px;
                    --blur-md: 12px;
                    --blur-lg: 16px;
                    --blur-xl: 24px;
                    --blur-2xl: 40px;
                    --blur-3xl: 64px;
                    --perspective-dramatic: 100px;
                    --perspective-near: 300px;
                    --perspective-normal: 500px;
                    --perspective-midrange: 800px;
                    --perspective-distant: 1200px;
                    --aspect-video: 16 / 9;
                    --default-transition-duration: 0.15s;
                    --default-transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                    --default-font-family: var(--font-sans);
                    --default-font-feature-settings: var(--font-sans--font-feature-settings);
                    --default-font-variation-settings: var(--font-sans--font-variation-settings);
                    --default-mono-font-family: var(--font-mono);
                    --default-mono-font-feature-settings: var(--font-mono--font-feature-settings);
                    --default-mono-font-variation-settings: var(--font-mono--font-variation-settings);
                }
            }
        </style>
    @endif
</head>
<body class="bg-[#a6b9b9] text-[#354f44] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
<header class="w-screen text-sm absolute top-0 right-0 p-4 bg-[#a6b9b9] shadow-md">
    <div class="flex items-center justify-between">
        <h1 class="text-[#354f44] font-medium text-xl">Intranet</h1>

        <!-- Navigation -->
        <nav class="flex items-center gap-4" aria-label="Main Navigation">
            <a href="https://interfas.myyellowboxcrm.com/" target="_blank"
               class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
               aria-label="YellowboxCRM">
                YellowboxCRM
            </a>

            <a href="http://192.168.1.58:8180/webquartz/" target="_blank"
               class="inline-flex items-center px-4 py-2  rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
               aria-label="Horoquartz">
                Horoquartz
            </a>

            <div class="relative group" id="transportersMenu">
                <button
                    class="inline-flex items-center px-4 py-2  rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
                    aria-haspopup="true" aria-expanded="false">
                    Transporteurs
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="transportersDropdown"
                     class="absolute hidden group-hover:block bg-white border border-gray-200 rounded-md shadow-lg mt-2 w-48">
                    <a href="https://www.tnt.fr/public/login/index.do"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">TNT</a>
                    <a href="https://www.dbschenker.com/fr-fr"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">DB SCHENKER</a>
                    <a href="https://connect.gefco.net/psc-portal/login.html#LogIn"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">GEFCO</a>
                    <a href="http://www.dpd.fr/trace"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">DPD</a>
                    <a href="http://chargeurweb.com/tracking"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">VARILLON</a>
                </div>
            </div>
            <a href="https://eprint.interfas.fr/" target="_blank"
               class="inline-flex items-center px-4 py-2  rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
               aria-label="e-print">
                e-print
            </a>
            <a href="https://www.esupply.valeo.com/" target="_blank"
               class="inline-flex items-center px-4 py-2  rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
               aria-label="Valeo">
                Valeo
            </a>
            <a href="https://auscp.aperam.com/oauth2/authorize?response_type=code&client_id=c2e184e7-af79-420f-90d6-c3bfa6b95449"
               target="_blank"
               class="inline-flex items-center px-4 py-2  rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
               aria-label="Aperam">
                Aperam
            </a>
            <a href="https://armoires.zeendoc.com/interfas/" target="_blank"
               class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
               aria-label="Zeendoc">
                Zeendoc
            </a>
            <a href="https://shop.bluestoreinc.com/fr" target="_blank"
               class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
               aria-label="Bluestar">
                Bluestar
            </a>
            <a href="https://client.interfas.fr/proxiserve/" target="_blank"
               class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
               aria-label="Proxyserve">
                Proxyserve
            </a>
        </nav>

        <!-- Connexion -->
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
                       aria-label="Tableau de bord">
                        Indicateurs
                    </a>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition duration-150 ease-in-out"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')"
                                                 class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('Profil') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault(); this.closest('form').submit();"
                                                     class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        {{ __('Déconnexion') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-block px-5 py-1.5 border-[#19140035] hover:border-[#1915014a] hover:bg-[#19140035] hover:font-medium border text-[#354f44] rounded-md text-sm leading-normal transition-colors duration-200"
                       aria-label="Se connecter">
                        Se connecter
                    </a>
                @endauth
            </nav>
        @endif
    </div>
</header>
<main class="container mx-auto px-4 py-8">
    <section class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Charte Informatique d'INTERFAS</h2>

        <p class="mb-4">
            La présente charte définit les règles d'utilisation des ressources informatiques au sein d'INTERFAS,
            rappelant les responsabilités de chaque utilisateur et soulignant le cadre juridique de ces activités.
        </p>

        <h3 class="text-xl font-semibold mb-2">Utilisation Responsable des Ressources</h3>
        <ul class="list-disc list-inside mb-4">
            <li>Usage strictement professionnel des ressources partagées.</li>
            <li>Responsabilité individuelle pour l'utilisation des comptes et matériels.</li>
            <li>Confidentialité absolue des mots de passe.</li>
            <li>Responsabilité des accès aux informations partagées avec des tiers.</li>
            <li>Interdiction d'utiliser des comptes non autorisés ou de tenter de déchiffrer des mots de passe.</li>
            <li>Signalement immédiat de toute violation de sécurité.</li>
            <li>Interdiction d'installer des logiciels sans autorisation.</li>
            <li>Respect de la confidentialité des fichiers d'autrui et des communications.</li>
            <li>Obligation de réserve sur les informations internes à INTERFAS.</li>
        </ul>

        <h3 class="text-xl font-semibold mb-2">Sécurité et Intégrité</h3>
        <p class="mb-4">
            Tout utilisateur est tenu de respecter l'intégrité des systèmes informatiques et de signaler toute anomalie.
            L'installation de logiciels non autorisés et la tentative d'accès à des informations confidentielles sont
            strictement interdites.
        </p>

        <h3 class="text-xl font-semibold mb-2">Mots de Passe et Accès</h3>
        <p class="mb-4">
            La sécurité de vos mots de passe est cruciale. Ne les partagez jamais, surtout par téléphone.
            L'accès à des ressources informatiques sans autorisation est formellement interdit.
        </p>

        <p class="mb-4 text-red-600 font-bold">
            Le non-respect de cette charte peut entraîner des sanctions administratives et pénales.
        </p>
    </section>
</main>
<footer class="w-full bg-[#a6b9b9] text-[#354f44] text-center p-4">
    <p>&copy; {{ date('Y') }} INTERFAS. Tous droits réservés.</p>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const transportersMenu = document.getElementById('transportersMenu');
        const transportersDropdown = document.getElementById('transportersDropdown');
        let hideTimeout;

        transportersMenu.addEventListener('mouseenter', function () {
            clearTimeout(hideTimeout); // Annule un délai précédent si le menu est ré-survolé
            transportersDropdown.classList.remove('hidden'); // Affiche le menu
        });

        transportersMenu.addEventListener('mouseleave', function () {
            hideTimeout = setTimeout(function () {
                transportersDropdown.classList.add('hidden'); // Cache le menu après 0.5 secondes
            }, 50); // 500 millisecondes = 0.5 secondes
        });
    });
</script>
</body>
</html>
