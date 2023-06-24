<?php
session_start();

$connection = mysqli_connect("localhost:", "root", "", "foodblog");

if (!isset($connection)) {
    die('Connection failed ' . $connection->error);
}

if (!isset($_GET['id'])) {

    $query = 'SELECT username FROM user WHERE ID = "' . $_SESSION['user_id'] . '";';

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Invalid query: ' . mysqli_error($connection));
    }

    $row = $result->fetch_assoc();

    $username = $row['username'];

    $zielverzeichnis = 'uploads/' . $username . '/';

    if (isset($_POST['submit-create-button'])) {
        if ($_SESSION['vorherige_seite'] !== $_SERVER['PHP_SELF'] . "create") {

            $fileNameSubstringsReversed = array_reverse(explode('.', strtolower($_FILES['bild']['name'])));

            if ($fileNameSubstringsReversed[0] != "jpg" && $fileNameSubstringsReversed[0] != "jpeg" && $fileNameSubstringsReversed[0] != "png") {
                $fehler = "type";
                header('Location: blog-anlegen.php?titel=' . $_POST['titel'] . '&text=' . $_POST['text'] . '&fehler=' . $fehler . '');
            } elseif ($_FILES['bild']['error'] == 1) {
                $fehler = "size";
                header('Location: blog-anlegen.php?titel=' . $_POST['titel'] . '&text=' . $_POST['text'] . '&fehler=' . $fehler . '');
                exit;
            } else {

                if (!file_exists($zielverzeichnis)) {
                    mkdir($zielverzeichnis, 0777, true);
                }

                $bildname = time() . basename($_FILES['bild']['name']);

                $bildpfad = $zielverzeichnis . $bildname;


                $moved_successfully = move_uploaded_file($_FILES['bild']['tmp_name'], $bildpfad);

                if ($moved_successfully) {
                    $query = 'INSERT INTO beitrag(TITEL, TEXT, BILDPFAD, AUTOR) VALUES("' .
                        $_POST['titel'] . '", "' . $_POST['text'] . '", "' . $bildname . '", "' .
                        $_SESSION['user_id'] . '");';

                    $result = mysqli_query($connection, $query);

                    if (!$result) {
                        die('Invalid query: ' . mysqli_error($connection));
                    }
                } 
            }
        }

        $id_query = 'SELECT MAX(ID) AS id FROM beitrag;';
        $beitrag_id = mysqli_query($connection, $id_query)->fetch_assoc()['id'];

        $_SESSION['vorherige_seite'] = $_SERVER['PHP_SELF'] . "create";
    }

    if (isset($_POST['submit-edit-button'])) {

        $beitrag_id = $_POST['beitrag_id'];

        if ($_SESSION['vorherige_seite'] !== $_SERVER['PHP_SELF'] . "edit") {
            if ($_FILES['bild']['error'] !== UPLOAD_ERR_NO_FILE) {

                $fileNameSubstringsReversed = array_reverse(explode('.', strtolower($_FILES['bild']['name'])));

                if ($fileNameSubstringsReversed[0] != "jpg" && $fileNameSubstringsReversed[0] != "jpeg" && $fileNameSubstringsReversed[0] != "png") {
                    $fehler = "type";
                    header('Location: blog-bearbeiten.php?titel=' . urlencode($_POST['titel']) . '&text=' . urlencode($_POST['text']) . '&fehler=type&id_fehler=' . $beitrag_id . '');
                    exit;
                }

                if ($_FILES['bild']['error'] == 1) {
                    header('Location: blog-bearbeiten.php?titel=' . urlencode($_POST['titel']) . '&text=' . urlencode($_POST['text']) . '&fehler=size&id_fehler=' . $beitrag_id . '');
                    exit;
                }

                $query = 'SELECT bildpfad FROM beitrag WHERE ID = "' . $beitrag_id . '";';

                $result = mysqli_query($connection, $query);

                if (!$result) {
                    die('Invalid query: ' . mysqli_error($connection));
                }

                $row = $result->fetch_assoc();

                $bildpfad = $row['bildpfad'];

                unlink($zielverzeichnis . $bildpfad);

                $bildname = time() . basename($_FILES['bild']['name']);
                $bildpfad = $zielverzeichnis . $bildname;
				
				$moved_successfully = move_uploaded_file($_FILES['bild']['tmp_name'], $bildpfad);
				
                if ($moved_successfully) {

                    $query = 'UPDATE beitrag SET TITEL = "' . $_POST['titel'] . '", TEXT = "' . $_POST['text'] . '", BILDPFAD = "' . $bildname . '" WHERE ID = ' . $beitrag_id . ';';

                    $result = mysqli_query($connection, $query);

                    if (!$result) {
                        echo ('Invalid query: ' . mysqli_error($connection));
                    }
                }
            }

            $query = 'UPDATE beitrag SET TITEL = "' . $_POST['titel'] . '", TEXT = "' . $_POST['text'] . '" WHERE ID = ' . $beitrag_id . ';';

            $result = mysqli_query($connection, $query);

            if (!$result) {
                echo ('Invalid query: ' . mysqli_error($connection));
            }

            $_SESSION['vorherige_seite'] = $_SERVER['PHP_SELF'] . "edit";
        }
    }

    if (isset($_POST['submit-delete-button'])) {

        $query = 'SELECT bildpfad FROM beitrag WHERE ID = "' . $_POST['beitrag_id'] . '";';

        $result = mysqli_query($connection, $query);

        if (!$result) {
            die('Invalid query: ' . mysqli_error($connection));
        }

        $row = $result->fetch_assoc();

        $bildpfad = $row['bildpfad'];

        unlink($_POST['zielverzeichnis'] . $bildpfad);


        $query = 'DELETE FROM beitrag WHERE ID = "' . $_POST['beitrag_id'] . '";';

        $result = mysqli_query($connection, $query);

        if (!$result) {
            die('Invalid query: ' . mysqli_error($connection));
        }

        header('Location: blogs.php');

        $_SESSION['vorherige_seite'] = $_SERVER['PHP_SELF'] . "delete";
    }
}

if (isset($_GET['id'])) {
    $beitrag_id = $_GET['id'];
}

$query = 'SELECT * FROM beitrag WHERE ID = ' . $beitrag_id . ';';
$result = mysqli_query($connection, $query);

if (!$result) {
    die('Invalid query: ' . mysqli_error($connection));
}

$row = $result->fetch_assoc();

$titel = $row['titel'];
$text = $row['text'];
$bildpfad = $row['bildpfad'];
$autor = $row['autor'];

if (isset($_GET['id'])) {
    $query = 'SELECT * FROM user WHERE ID = ' . $autor . ';';
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Invalid query: ' . mysqli_error($connection));
    }

    $row = $result->fetch_assoc();

    $zielverzeichnis = "uploads/" . $row['username'] . '/';

    $_SESSION['vorherige_seite'] = $_SERVER['PHP_SELF'] . "load";
}

$connection->close();

?>

<html>

<head>
    <link rel="stylesheet" href="styles/blog-anzeigen.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('header.html'); ?>

    <main>
        <div class="container">
            <?php
            if (isset($_POST['submit-create-button'])) {
                echo ('
                    <div class="nachricht font" id="nachricht">
                        <div>
                            Der Beitrag wurde erfolgreich erstellt.
                        </div>
                        <button class="nachricht-ausblenden">
                            <a href="#nachricht">
                                <img class="nachricht-icon" src="../symbols/close.svg">
                            </a>
                        </button>
                    </div>');
            }
            ?>
            <?php
            if (isset($_POST['submit-edit-button'])) {
                echo ('
                    <div class="nachricht font" id="nachricht">
                        <div>
                            Der Beitrag wurde erfolgreich bearbeitet.
                        </div>
                        <button class="nachricht-ausblenden">
                            <a href="#nachricht">
                                <img class="nachricht-icon" src="../symbols/close.svg">
                            </a>
                        </button>
                    </div>');
            }
            ?>
            <div class="top sub-container">
                <image class="image" src=<?php echo ('"' . $zielverzeichnis . $bildpfad . '"'); ?>>
            </div>

            <hr>

            <div class="mid sub-container">
                <div class="heading font">
                    <?php echo ($titel) ?>
                </div>
                <?php
                if ($_SESSION['user_id'] === $autor) {
                    echo ('
                        <div class="interactions">
                            <div class="edit">
                                <a href="blog-bearbeiten.php?id=' . $beitrag_id . '">
                                    <img class="icon" src="../symbols/create-dark-green.svg">
                                </a>
                            </div>
                            <div class="delete">
                                <form method="post" action="' . $_SERVER['PHP_SELF'] . '" onsubmit="return confirm(\'Sind Sie sich sicher, dass Sie den beitrag löschen möchten?\')">
                                    <button class="delete-button" type="submit" name="submit-delete-button">
                                        <img class="icon" src="../symbols/trash.svg">
                                    </button>
                                    <input type="hidden" name="beitrag_id" value=' . $beitrag_id . '>
                                    <input type="hidden" name="zielverzeichnis" value=' . $zielverzeichnis . '>
                                <form>
                            </div>
                        </div>
                    ');
                }
                ?>
            </div>

            <div class="bottom sub-container font">
                <div class="text">
                    <?php echo nl2br($text) ?>
                </div>
            </div>
        </div>
    </main>

    <?php include('footer.html'); ?>
</body>

</html>