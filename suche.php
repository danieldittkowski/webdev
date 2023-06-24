<?php
$connection = mysqli_connect("localhost:", "root", "", "foodblog");

if (!isset($connection)) {
    die('Connection failed ' . $connection->error);
}

$query = 'SELECT * FROM beitrag WHERE TITEL LIKE "%' . $_POST['sucheingabe'] . '%" OR TEXT LIKE "%' . $_POST['sucheingabe'] . '%";';
$result = mysqli_query($connection, $query);

if (!$result) {
    die('Invalid query: ' . mysqli_error($connection));
}

?>

<html>

<head>
    <link rel="stylesheet" href="styles/suche.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('header.html'); ?>

    <main>
        <div class="container">
            <form method="post" action=<?php echo ('"' . $_SERVER['PHP_SELF'] . '"'); ?>>
                <input class=" suche font" type="text" name="sucheingabe" placeholder="Suche" value=<?php echo ($_POST['sucheingabe']); ?>>
                <button class="button" type="submit" name="submit-search-button">
                    <img class="icon" src="symbols/search-dark-green.svg">
                </button>
            </form>
            <?php
            if (!isset($_POST['sucheingabe'])) {
                echo ('
                <div class="blogs sub-container">
                    <div class="heading font">
                        Alle Beiträge
                    </div>

                    <div class="kachel-container">');
                foreach ($result as $blog) {
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
            }
            if (isset($_POST['sucheingabe'])) {
                echo ('
                    <div class="blogs sub-container">
                        <div class="heading font">
                            Suchergebnisse
                        </div>
    
                        <div class="kachel-container">');
                foreach ($result as $blog) {
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
            }


            $connection->close();
            ?>
        </div>
    </main>

    <?php include('footer.html'); ?>
</body>

</html>