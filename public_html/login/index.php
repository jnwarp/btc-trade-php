<?php
    require_once(dirname(__FILE__) . '/../../resources/prepend.php');

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
        <link href="/css/login.css" rel="stylesheet">
    </head>
    <body>
        <?php include(dirname(__FILE__) . '/../../resources/navbar.php') ?>

        <div class="container" style="padding-top: 60px" align="center">
            <div class="card border-primary mb-3" style="max-width: 20rem; border-width: 1px">
                <h4 class="card-header text-primary">Please sign in</h4>
                <div class="card-body text-primary">
                    <form class="form-signin">
                        <label for="inputEmail" class="sr-only">Email address</label>
                        <input type="email" id="loginEmail" class="form-control" placeholder="Email address" required autofocus>
                        <label for="inputPassword" class="sr-only">Password</label>
                        <input type="password" id="loginPassword" class="form-control" placeholder="Password" required>
                        <a id="loginButton" class="btn btn-lg btn-block btn-primary" href="#">Sign in</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="/js/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/bootstrap-notify.min.js"></script>
        <script src="/js/login.js"></script>
    </body>
</html>