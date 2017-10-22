<?php
    require_once(dirname(__FILE__) . '/../resources/prepend.php');

    $login = new Login();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/homepage.css" rel="stylesheet">
    </head>
    <body>
        <?php include(dirname(__FILE__) . '/../resources/navbar.php'); ?>
        
        <?php 
            if (!$login->loggedIn) {
                include(dirname(__FILE__) . '/../resources/restricted.php');
            } else {
                echo '<p>You are logged in!</p>';
            }
        ?>
        

        <?php include(dirname(__FILE__) . '/../resources/footer.php') ?>
    </body>
</html>