<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel="stylesheet" href="css/msplayer.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <button class="hdbtn">
                <i class="fi fi-sr-arrow-small-left"></i>
            </button>
            <h3 class="p-now">Playing now</h3>
            <button class="hdbtn">
                <i class="fi fi-sr-list"></i>
            </button>
        </header>
        <div class="player">
            <div class="img-area">
                <img src="image/favicon.png" alt="">
            </div>

            <div class="song-details">
                <h1 class="name">Nome da Música</h1>
                <h3 class="artist">Artista</h3>
            </div>
            
            <div class="audioC">
                <div class="song-timer">
                    <span class="current-timer">0:00</span>
                    <span class="max-duration">0:00</span>
                </div>
                <div class="progress-area">
                    <div class="progress-bar"></div>
                </div>
            </div>

            <div class="controls">
                <button class="btnC" id="prev">
                    <i class="fi fi-sr-rewind"></i>
                </button>
                <button class="play-pause btnC">
                    <i class="fi fi-sr-play play"></i>
                </button>
                <button class="btnC" id="next">
                    <i class="fi fi-sr-forward"></i>
                </button>
            </div>
        </div>
    </div>

    <audio id="main-audio" src=""></audio>

    <script>
        let allMusic = [];

        // Carregar músicas do arquivo PHP
        fetch('musicas.php')
            .then(response => response.json())
            .then(data => {
                allMusic = data; // Atribuir o JSON às músicas
                loadMusic(0); // Carregar a primeira música
            })
            .catch(error => {
                console.error('Erro ao carregar as músicas:', error);
            });

        const wrapper = document.querySelector(".wrapper"),
              musicImg = wrapper.querySelector("img"),
              musicName = wrapper.querySelector(".name"),
              musicArtist = wrapper.querySelector(".artist"),
              playPauseBtn = wrapper.querySelector(".play-pause"),
              prevBtn = wrapper.querySelector("#prev"),
              nextBtn = wrapper.querySelector("#next"),
              mainAudio = document.querySelector("#main-audio"),
              progressArea = wrapper.querySelector(".progress-area"),
              progressBar = wrapper.querySelector(".progress-bar");

        let musicIndex = 0;
        let isMusicPaused = true;

        function loadMusic(indexNumb) {
            musicName.innerText = allMusic[indexNumb].name;
            musicArtist.innerText = allMusic[indexNumb].artist;
            musicImg.src = `image_artist/${allMusic[indexNumb].img}.png`;
            mainAudio.src = `audio/${allMusic[indexNumb].src}.mp3`;
        }

        function playMusic() {
            wrapper.classList.add("paused");
            musicImg.classList.add('rotate');
            playPauseBtn.innerHTML = `<i class="fi fi-sr-pause"></i>`;
            mainAudio.play();
        }

        function pauseMusic() {
            wrapper.classList.remove("paused");
            musicImg.classList.remove('rotate');
            playPauseBtn.innerHTML = `<i class="fi fi-sr-play"></i>`;
            mainAudio.pause();
        }

        function prevMusic() {
            musicIndex = (musicIndex > 0) ? musicIndex - 1 : allMusic.length - 1;
            loadMusic(musicIndex);
            playMusic();
        }

        function nextMusic() {
            musicIndex = (musicIndex < allMusic.length - 1) ? musicIndex + 1 : 0;
            loadMusic(musicIndex);
            playMusic();
        }

        playPauseBtn.addEventListener("click", () => {
            const isMusicPlaying = wrapper.classList.contains("paused");
            isMusicPlaying ? pauseMusic() : playMusic();
        });

        prevBtn.addEventListener("click", () => {
            prevMusic();
        });

        nextBtn.addEventListener("click", () => {
            nextMusic();
        });

        mainAudio.addEventListener("ended", () => {
            nextMusic();
        });
    </script>

    <script src="js/music-list.js"></script>
    <script src="js/msplayer.js"></script>
</body>
</html>
