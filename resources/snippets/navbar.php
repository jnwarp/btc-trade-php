<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
    <a class="navbar-brand" href="/">BTC Trader</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        <li class="nav-item active">
            <a class="nav-link" href="/">Dashboard <span class="sr-only">(current)</span></a>
        </li>
        </ul>
        <?php
        if ($login->loggedIn) {
            echo '<a id="logoutButton" class="btn btn-outline-success my-2 my-sm-0" href="">Logout</a>';
        } else {
            echo '<a class="btn btn-outline-danger my-2 my-sm-0" href="/login">Login</a>';
        }
        ?>
        
    </div>
    </div>
</nav>