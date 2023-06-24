<?php
session_start();

$connection = mysqli_connect("localhost:", "root", "", "foodblog");

if (!isset($connection)) {
    die('Connection failed ' . $connection->error);
}

if (isset($_SESSION['user_id'])) {

    $query = 'SELECT username FROM user WHERE ID = "' . $_SESSION['user_id'] . '";';

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Invalid query: ' . mysqli_error($connection));
    }

    $row = $result->fetch_assoc();

    $username = $row['username'];

    $stunde = date('H');

    if ($stunde >= 6 && $stunde < 12) {
        $begrueßung = "Guten Morgen " . $username . '!';
    } elseif ($stunde >= 12 && $stunde < 18) {
        $begrueßung = "Guten Tag " . $username . '!';
    } else {
        $begrueßung = "Guten Abend " . $username . '!';
    }
}

$query = 'SELECT * FROM beitrag ORDER BY id DESC LIMIT 6;';
$result_newest = mysqli_query($connection, $query);

if (!$result_newest) {
    die('Invalid query: ' . mysqli_error($connection));
}

$query = 'SELECT * FROM beitrag ORDER BY id ASC LIMIT 6;';
$result_popular = mysqli_query($connection, $query);

if (!$result_popular) {
    die('Invalid query: ' . mysqli_error($connection));
}
?>
<html>

<head>
    <link rel="stylesheet" href="styles/index.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('header.html'); ?>

    <main>
        <div class="container">
            <?php
            if (isset($_SESSION['user_id']) && $_SESSION['vorherige_seite'] === "/anmeldung.php") {
                echo ('
                    <div class="nachricht" id="nachricht">
                        <div class="font">' .
                    $begrueßung
                    . '</div>
                        <button class="nachricht-ausblenden">
                            <a href="#nachricht">
                                <img class="nachricht-icon" src="symbols/close.svg">
                            </a>
                        </button>
                    </div>');
            }
            ?>

            <div class="blogs sub-container">
                <div class="heading font">
                    Neuste Blogs
                </div>
                <div class="kachel-container">
                    <?php
                    foreach ($result_newest as $blog) {
                        $query = 'SELECT username FROM user WHERE id = "' . $blog['autor'] . '";';
                        $result_autor_username = mysqli_query($connection, $query);

                        if (!isset($result_autor_username)) {
                            die('Invalid query: ' . mysqli_error($connection));
                        }

                        $row = $result_autor_username->fetch_assoc();
                        $autor_username = $row['username'];
                        $zielverzeichnis = "uploads/" . $autor_username . '/';
                        echo ('
                            <a href="blog-anzeigen.php?id=' . $blog['id'] . '">
                                <div class="kachel">
                                    <img class="preview" src="' . $zielverzeichnis . $blog['bildpfad'] . '">
                                    <div class="title font">
                                       ' . $blog['titel'] . ' 
                                    </div>
                                    <div class="title font">
                                       von: ' . $autor_username . ' 
                                    </div>
                                </div>
                            </a>');
                    }
                    ?>
                </div>
            </div>

            <div class="blogs sub-container">
                <div class="heading font">
                    Beliebte Blogs
                </div>
                <div class="kachel-container">
                    <?php
                    foreach ($result_popular as $blog) {
                        $query = 'SELECT username FROM user WHERE id = "' . $blog['autor'] . '";';
                        $result_autor_username = mysqli_query($connection, $query);

                        if (!isset($result_autor_username)) {
                            die('Invalid query: ' . mysqli_error($connection));
                        }

                        $row = $result_autor_username->fetch_assoc();
                        $autor_username = $row['username'];
                        $zielverzeichnis = "uploads/" . $autor_username . '/';
                        echo ('
                            <a href="blog-anzeigen.php?id=' . $blog['id'] . '">
                                <div class="kachel">
                                    <img class="preview" src="' . $zielverzeichnis . $blog['bildpfad'] . '">
                                    <div class="title font">
                                       ' . $blog['titel'] . ' 
                                    </div>
                                    <div class="title font">
                                       von: ' . $autor_username . ' 
                                    </div>
                                </div>
                            </a>');
                    }

                    $connection->close();
                    ?>
                </div>
            </div>
        </div>
    </main>

    <?php include('footer.html'); ?>
</body>

</html>

<?php 
    $_SESSION['vorherige_seite'] = $_SERVER['PHP_SELF'];
?>