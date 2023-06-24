<?php
session_start();
$connection = mysqli_connect("localhost:", "root", "", "foodblog");

if (!isset($connection)) {
    die('Connection failed ' . $connection->error);
}

if (isset($_SESSION['user_id'])) {
    $query = 'SELECT * FROM beitrag WHERE AUTOR = '.$_SESSION['user_id'].';';
    $result_user = mysqli_query($connection, $query);

    if (!isset($result_user)) {
        die('Invalid query: ' . mysqli_error($connection));
    }
}

$query = 'SELECT * FROM beitrag ORDER BY id ASC LIMIT 6;';
$result_popular = mysqli_query($connection, $query);

if (!isset($result_popular)) {
    die('Invalid query: ' . mysqli_error($connection));
}

?>

<html>

<head>
    <link rel="stylesheet" href="styles/blogs.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('header.html'); ?>

    <main>
        <div class="container">
        <?php
            if (isset($_SESSION['user_id'])) {
                    echo ('
                    <div class="button sub-container smartphone-view">
                    <a href="blog-anlegen.php">
                        <button class="font">
                            Neuen Blog posten
                        </button>
                    </a>
                </div>
                    ');
                
                if (mysqli_num_rows($result_user) != 0) {
                echo('
                <div class="blogs sub-container">
                    <div class="heading font">
                        Meine Blogs
                    </div>

                    <div class="kachel-container">');
                    foreach ($result_user as $blog) {
                        $query = 'SELECT username FROM user WHERE id = "' . $blog['autor'] . '";';
                        $result_autor_username = mysqli_query($connection, $query);

                        if (!isset($result_autor_username)) {
                            die('Invalid query: ' . mysqli_error($connection));
                        }

                        $row = $result_autor_username->fetch_assoc();
                        $autor_username = $row ['username'];
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
                    echo('</div>');
                }
            }
            ?>
            <div class="blogs sub-container">
                <div class="heading font">
                    Neuste Blogs
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
                        $autor_username = $row ['username'];
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
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo ('
                    <div class="button sub-container desktop-view">
                    <a href="blog-anlegen.php">
                        <button class="font">
                            Neuen Blog posten
                        </button>
                    </a>
                </div>
                    ');
                }
                ?>
            </div>
        </div>
    </main>

    <?php include('footer.html'); ?>
</body>

</html>