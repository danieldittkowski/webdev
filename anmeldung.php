<?php

session_start();

$_SESSION['vorherige_seite'] = $_SERVER['PHP_SELF'];

if (isset($_POST['submit-button'])) {

    $connection = mysqli_connect("localhost:", "root", "", "foodblog");

    if (!isset($connection)) {
        die("Connection failed: " . $connection->error);
    }

    $query = 'SELECT password FROM user WHERE userNAME = "' . $_POST['username'] . '";';
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Invalid query: ' . mysqli_error($connection));
    }

    $row = $result->fetch_assoc();

    if (!isset($row['password'])) {
        $fehler['username'] = "Der Username existiert nicht.";
    }

    if (isset($row['password']) && $row['password'] != password_verify($_POST['password'], $row['password'])) {
        $fehler['password'] = "Das Passwort ist falsch.";
    }

    if (!isset($fehler)) {
        $query = 'SELECT ID FROM user WHERE userNAME = "' . $_POST['username'] . '";';
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die('Invalid query: ' . mysqli_error($connection));
        }

        $row = $result->fetch_assoc();
        $user_id = $row['ID'];

        session_start();
        $_SESSION['id'] = session_id();
        $_SESSION['user_id'] = $user_id;
        header('Location: index.php');
    }

    $connection->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles/anmeldung.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('header.html'); ?>

    <main>
        <div class="container">
            <?php
            if (isset($fehler['username'])) {
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
            if (isset($fehler['password'])) {
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
            ?>
            <div class="heading font">
                Anmelden
            </div>
            <div class="form-container">
                <form method="post" action=<?php echo ('"' . $_SERVER['PHP_SELF'] . '"') ?>>
                    <div class="form-content">
                        <div>
                            <input class="input font" type="text" name="username" placeholder="Username" required>
                        </div>
                        <div>
                            <input class="input font" type="password" name="password" placeholder="Passwort" required>
                        </div>
                        <div class="button-container">
                            <a class="font registrieren button" href="registrierung.php">
                                Hier registrieren
                            </a>
                            <input class="font submit button" type="submit" name="submit-button" value="Anmelden">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include('footer.html'); ?>
</body>

</html>