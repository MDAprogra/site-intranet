<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slideshow TV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #000;
            overflow: hidden;
        }

        .slideshow {
            width: 100vw;
            height: 100vh;
        }

        .slide {
            width: 100vw;
            height: 100vh;
            display: flex !important;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .slide img, .slide video {
            max-width: 100%;
            max-height: 100%;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Masquer les contrôles vidéo pour une meilleure expérience TV */
        video {
            object-fit: cover;
        }

        /* Agrandir les points de navigation pour télécommande */
        .slick-dots {
            bottom: 30px;
        }

        .slick-dots li button:before {
            font-size: 16px;
            color: #fff;
            opacity: 0.5;
        }

        .slick-dots li.slick-active button:before {
            opacity: 1;
            color: #fff;
        }

        /* Masquer les flèches de navigation (utilisation automatique) */
        .slick-prev, .slick-next {
            display: none !important;
        }
    </style>
</head>
<body>
<div class="slideshow">
    @foreach($mediaFiles as $file)
        @if($file->type == 'image')
            <div class="slide"><img src="{{ asset('storage/' . $file->path) }}" alt="Image"></div>
        @elseif($file->type == 'video')
            <div class="slide"><video src="{{ asset('storage/' . $file->path) }}" autoplay muted loop></video></div>
        @endif
    @endforeach
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function(){
        // Obtenir le nombre total de slides
        var totalSlides = $('.slide').length;
        var cycleComplete = false;
        var slideCounter = 0;

        $('.slideshow').slick({
            dots: true,
            infinite: true,
            speed: 1000,
            fade: true,
            cssEase: 'linear',
            autoplay: true,
            autoplaySpeed: 7500,
            arrows: false,
            pauseOnHover: false,
            pauseOnFocus: false,
            adaptiveHeight: false,
            responsive: true,
            lazyLoad: 'ondemand'
        });

        // Gestion des vidéos
        $('.slideshow').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            // Pause toutes les vidéos lorsque le slide change
            var currentVideo = $(slick.$slides[currentSlide]).find('video').get(0);
            if (currentVideo) {
                currentVideo.pause();
            }
        });

        $('.slideshow').on('afterChange', function(event, slick, currentSlide) {
            // Joue la vidéo du slide actuel
            var currentVideo = $(slick.$slides[currentSlide]).find('video').get(0);
            if (currentVideo) {
                currentVideo.play();
            }

            // Incrémenter le compteur de slides
            slideCounter++;

            // Vérifier si nous avons fait un cycle complet
            if (slideCounter >= totalSlides && !cycleComplete) {
                cycleComplete = true;
                console.log("Cycle complet, rafraîchissement de la page...");
                // Rafraîchir la page après un court délai pour permettre la transition
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        });

        // Mode plein écran adapté pour TV
        function goFullScreen() {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        }

        // Activation automatique du plein écran après quelques secondes
        setTimeout(goFullScreen, 1000);
    });
</script>
</body>
</html>
