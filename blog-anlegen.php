<?php
session_start();
$_SESSION['vorherige_seite'] = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles/blog-anlegen.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('header.html'); ?>

    <main>
        <div class="container">
			<?php
            if ($_GET['fehler'] == "size") {
                echo ('
                    <div class="fehler font" id="fehler-nachricht">
                        <div>
                            Bild größer als 5 MB.
                        </div>
                        <button class="fehler-ausblenden">
                            <a href="#fehler-nachricht">
                                <img class="icon-fehlermeldung" src="../symbols/close.svg">
                            </a>
                        </button>
                    </div>');
            }
			
			if ($_GET['fehler'] == "type") {
                echo ('
                    <div class="fehler font" id="fehler-nachricht">
                        <div>
                            Datei ist kein Bild.
                        </div>
                        <button class="fehler-ausblenden">
                            <a href="#fehler-nachricht">
                                <img class="icon-fehlermeldung" src="../symbols/close.svg">
                            </a>
                        </button>
                    </div>');
            }
            ?>
            <div class="heading font">
                Blog anlegen
            </div>
            <div class="form-container">
                <form method="post" action="blog-anzeigen.php" enctype="multipart/form-data">
                    <div class="form-content">
                        <div>
                            <input class="input font titel" type="text" name="titel" placeholder="Blogtitel" <?php if($_GET['fehler'] == TRUE) {echo('value='.$_GET['titel']);}?> required>
                        </div>
                        <div>
                            <textarea class="input font textfeld" name="text" placeholder="Blogtext" required><?php if($_GET['fehler'] == TRUE) {echo($_GET['text']);}?></textarea>
                        </div>
                        <div class="file">
                            <div>
                                <div class="upload-title font">
                                    <div>
                                        Hier Blogbild hochladen (max. 5 MB)
                                    </div>
                                    <img class="icon" src="symbols/chevron-down-circle-dark-green.svg">
                                </div>
                            </div>
                            <div>
                                <input class="input font file-upload" type="file" name="bild" required>
                                <input" type="hidden" name="MAX_FILE_SIZE" value="5000000">
                            </div>
                        </div>
                        <div class="button-container">
                            <input class="font reset button" type="reset" name="reset-button" value="Zurücksetzen">
                            <input class="font submit button" type="submit" name="submit-create-button" value="Blog erstellen">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include('footer.html'); ?>
</body>

</html>