<?php
    require_once(dirname(__FILE__) . '/../resources/snippets/prepend.php');

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
        <link href="/css/font-awesome.min.css" rel="stylesheet">
        <link href="/css/homepage.css" rel="stylesheet">
    </head>
    <body>
        <?php include(dirname(__FILE__) . '/../resources/snippets/navbar.php'); ?>
        
        <?php 
            if (!$login->loggedIn) {
                include(dirname(__FILE__) . '/../resources/snippets/restricted.php');
            } else {
                include(dirname(__FILE__) . '/../resources/pages/homepage.php');
            }
        ?>
        
        <?php include(dirname(__FILE__) . '/../resources/snippets/footer.php'); ?>
        <?php if ($login->loggedIn) echo('<script src="/js/homepage.js"></script>'); ?>
    </body>
</html>