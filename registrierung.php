<?php
$connection = mysqli_connect("localhost:", "root", "", "foodblog");

if (isset($_POST['submit-button'])) {

    if ($_POST['password'] != $_POST['password2']) {
        $fehler['password'] = "Passwörter ungleich.";
    }

    $query = 'SELECT * FROM user WHERE userNAME = "' . $_POST['username'] . '";';
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Invalid query: ' . mysqli_error($connection));
    }

    $row = $result->fetch_assoc();

    if (isset($row)) {
        $fehler['username'] = "Username bereits vergeben.";
    }
}

if (!isset($fehler) && isset($_POST['submit-button'])) {
    $query = 'INSERT INTO user(userNAME, PASSWORD) VALUES("' . $_POST['username'] . '", "' . password_hash($_POST['password'], PASSWORD_BCRYPT) . '");';

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Invalid query: ' . mysqli_error($connection));
    }

    header('Location: anmeldung.php');
}

$connection->close();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles/registrierung.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('header.html'); ?>

    <main>
        <div class="container">
            <?php
            if (isset($fehler['username']) && !isset($fehler['password'])) {
                echo ('
                    <div class="fehler font" id="fehler-nachricht">
                        <div>' .
                    $fehler['username']
                    . '</div>
                        <button class="fehler-ausblenden">
                            <a href="#fehler-nachricht">
                                <img class="icon" src="symbols/close.svg">
                            </a>
                        </button>
                    </div>');
            }
            if (!isset($fehler['username']) && isset($fehler['password'])) {
                echo ('
                    <div class="fehler font" id="fehler-nachricht">
                        <div>' .
                    $fehler['password']
                    . '</div>
                        <button class="fehler-ausblenden">
                            <a href="#fehler-nachricht">
                                <img class="icon" src="symbols/close.svg">
                            </a>
                        </button>
                    </div>');
            }
            if (isset($fehler['username']) && isset($fehler['password'])) {
                echo ('
                    <div class="zwei fehler font" id="fehler-nachricht">
                        <div>' .
                            $fehler['username']. '
                            <br>
                            <br>'.
                            $fehler['password'].'
                        </div>
                        <button class="fehler-ausblenden">
                            <a href="#fehler-nachricht">
                                <img class="icon" src="symbols/close.svg">
                            </a>
                        </button>
                    </div>');
            }
            ?>
            <div class="heading font">
                Registrierung
            </div>
            <div class="form-container">
                <form method="post" action=<?php echo ('"' . $_SERVER['PHP_SELF'] . '"') ?>>
                    <div class="form-content">
                        <div>
                            <?php
                            if (isset($fehler['username'])) {
                                echo ('
                                        <input class="input font username" type="text" name="username" placeholder="Username" value="' . $_POST['username'] . '" required>
                                    ');
                            }
                            if (!isset($fehler['username'])) {
                                echo ('
                                        <input class="input font" type="text" name="username" placeholder="Username" required value="' . $_POST['username'] . '">
                                    ');
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if (isset($fehler['password'])) {
                                echo ('
                                        <input class="input font password" type="password" name="password" placeholder="Passwort" required value="' . $_POST['password'] . '">
                                    ');
                            }
                            if (!isset($fehler['password'])) {
                                echo ('
                                        <input class="input font" type="password" name="password" placeholder="Passwort" value="' . $_POST['password'] . '" required>
                                    ');
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if (isset($fehler['password'])) {
                                echo ('
                                        <input class="input font password" type="password" name="password2" placeholder="Passwort wiederholen" required value="' . $_POST['password2'] . '">
                                    ');
                            }
                            if (!isset($fehler['password'])) {
                                echo ('
                                        <input class="input font" type="password" name="password2" placeholder="Passwort wiederholen" value="' . $_POST['password'] . '" required>
                                    ');
                            }
                            ?>
                        </div>
                        <div class="button-container">
                            <a class="font anmelden button" href="anmeldung.php">
                                Hier anmelden
                            </a>
                            <input class="font submit button" type="submit" name="submit-button" value="Registrieren">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include('footer.html'); ?>
</body>

</html>