<?php
    require_once(dirname(__FILE__) . '/../resources/prepend.php');
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
        <?php include(dirname(__FILE__) . '/../resources/navbar.php') ?>
        
        <div class="container" style="padding-top: 60px" align="center">
            <div class="card border-danger mb-3" style="max-width: 30rem; border-width: 2px">
                <div class="card-body text-danger">
                <h1>Please sign in</h1>
                <hr class="bg-danger">
                <p class="lead">This is a restricted site that requires you to sign in!</p>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    </body>
</html>