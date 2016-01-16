<?php

$config = array(
    "films" => array(
        array(
            "proper_name" => "Tytanik", // nazwa (na razie do porzadku)
            "image_name" => "titanic.jpg", // nazwa obrazka, ktory jest wykorzysytwany
            "answers" => array("tytanik", "tytanic", "titanic") // dopuszczalne odpowiedzi (moga byc z malej litery)
        ),
        array(
            "proper_name" => "Lśnienie",
            "image_name" => "shining.jpg",
            "answers" => array("lśnienie", "lsnienie", "shining")
        ),

    ),

    "places" => array(
        array(
            "proper_name" => "Watykan",
            "image_name" => "vatican.jpg",
            "answers" => array("watykan", "miasto watykan", "vatican city", "vatican")
        ),
        array(
            "proper_name" => "Tytanik",
            "image_name" => "titanic.jpg",
            "answers" => array("tytanik", "tytanic")
        )
    ),

    "celebrities" => array(
        array(
            "proper_name" => "Angelina Jolie",
            "image_name" => "jolie.jpg",
            "answers" => array("angelina", "jolie", "angelina jolie", "jolie angelina")
        ),
        array(
            "proper_name" => "Tytanik",
            "image_name" => "titanic.jpg",
            "answers" => array("tytanik", "tytanic")
        )
    )
);

echo "
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv='content-type' content='text/html;charset=utf-8' />
        <link type='text/css' rel='stylesheet' href='style.css'/>
        <title>Zgadywanka!</title>
    </head>
    <body>
        <div class='container'>
            <form method='post' action='guess.php' class='game-form'>";

        // sprawdzamy jaki typ gry wybral uzytkownik
        $game_type = $_GET['type'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $game_type = $_POST['type'];


        // sprawdzmy czy typ gry sie zgadza
        $ok = false;
        $max_questions = 0;
        foreach ($config as $type => $value) {
            if ($type == $game_type) {
                $ok = true;
                $max_questions = count($value);
                break;
            }
        }

        // sprawdzamy ile pytan zostalo do konca gry
        $questions_left = -1;
        if (is_numeric($_POST['questions_left'])) {
            $questions_left = intval($_POST['questions_left']);
        }

        // sprawdzamy ile pytan uzytkownik chcial dostac (potrzebne do rozpoczecia gry)
        $questions = -1;
        $error = "";
        if (!empty($_POST['questions'])) {
            if (is_numeric($_POST['questions'])) {
                $questions = intval($_POST['questions']);

                // sprawdzamy czy liczba jest z poprawnego zakresu
                if ($max_questions < $questions || $questions <= 0)
                    $error = "Wpisana liczba powinna być większa od 0 i nie przekraczać " . $max_questions . "";
            } else {
                // uzytkownik wpisal niepoprawna liczbe lub inny znak niz przewidywano
                $error = "Wpisana liczba nie jest poprawana";
            }

            if ($error === "") {
                // nie wystapil blad, wiec mozemy przygotowac sie do rozpoczecia gry
                $questions_left = $questions;
            }
        }

        if (!$ok) {
            // cos poszlo nie tak
            echo "
            <p class='text'>Wystąpił na błąd podczas gry :(</p>
            <a class='return' href='/index.php'>Powrót na stronę główną</a>
            ";
        } else {
            echo "<input name='type' type='hidden' value='" . $game_type . "'/>";

            // sprawdzamy czy gra sie juz zaczela
            if ($questions_left == -1) {
                if ($error != "")
                    echo "<p class='error'>" . $error . "</p>";

                // gra sie jeszcz nie rozpoczela
                echo "<p>Ile chcesz pytań? (Max " . $max_questions . ")</p>";
                echo "<input class='number-input' type='text' name='questions' value='1'/>";
                echo "<input class='submit' type='submit' value='Rozpocznij grę!'/>";
            } else {
                // sprawdzamy ile punktow ma gracz
                $points = 0;
                if (is_numeric($_POST['points']))
                    $points = intval($_POST['points']);

                // sprawdzamy jakie bylo ostatnio zadane pytanie
                $last_question = -1;
                if (is_numeric($_POST['last_question']))
                    $last_question = intval($_POST['last_question']);

                // sprawdzamy poprawnosc ostatnio zadanego pytania
                if (($last_question != -1) && ($last_question <= $max_questions - 1) && ($last_question >= 0)) {
                    $checker = $config[$game_type][$last_question];

                    // porownujemy odpowiedzi z dopuszczalnymi odpowiedzami
                    $found = false;
                    foreach ($checker["answers"] as $value) {
                        if ($value === strtolower($_POST['answer'])) {
                            $found = true;
                            break;
                        }
                    }

                    if ($found) {
                        // poprawna odpowiedz
                        $points = $points + 1; // dodajemy punkty
                        echo "<p class='answer good-answer'>DOBRZE!</p>";
                    } else {
                        // niepoprawna odpowiedz
                        echo "<p class='answer wrong-answer'>Źle :(</p>";
                    }
                }

                if ($questions_left == 0) {
                    // koniec rundy
                    echo "
                    <p class='text'>Zdobyta liczba punktów: <strong>" . $points . "</strong>. Gratulacje!</p>
                    <a class='return' href='/index.php'>Powrót do strony głównej</a>
                    ";
                } else {
                    // losujemy kolejne pytanie
                    $question = rand(0, $max_questions - 1);

                    echo "<p class='text'>Aktualna liczba punktów: <strong>" . $points . "</strong></p>";
                    echo "<p class='text'>Pytań do końca: <strong>" . $questions_left . "</strong></p>";

                    echo "<input name='points' type='hidden' value='" . $points . "'/>";
                    echo "<input name='last_question' type='hidden' value='" . ($question) . "'/>";
                    echo "<input name='questions_left' type='hidden' value='" . ($questions_left - 1) . "'/>";

                    echo "<div class='guess-image' style='background-image: url(\"images/" . $config[$game_type][$question]["image_name"] . "\")'></div>";
                    echo "<input class='text-input' name='answer' type='text'/>";
                    echo "<input class='submit' type='submit' value='Zgaduję!'/>";
                }
            }
        }

    echo "
            </form>
        </div>
    </body>
</html>";
?>
