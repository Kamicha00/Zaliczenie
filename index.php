<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
        <link type='text/css' rel='stylesheet' href='style.css'/>
        <title>Zgadywanka!</title>
    </head>
    <body>
        <div class='container'>
            <h2 class='header'>Wybierz tryb gry</h1>

            <div class='types'>
                <div class='type'>
                    <a href='/guess.php?type=films'>
                        <div class='background-image' style='background-image: url("images/films.jpg");'></div>
                        <p class='text'>Filmy</p>
                    </a>
                </div>

                <div class='type'>
                    <a href='/guess.php?type=places'>
                        <div class='background-image' style='background-image: url("images/places.jpg");'></div>
                        <p class='text'>Znane miasta</p>
                    </a>
                </div>

                <div class='type'>
                    <a href='/guess.php?type=celebrities'>
                        <div class='background-image' style='background-image: url("images/people.jpg");'></div>
                        <p class='text'>Znane osoby</p>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
