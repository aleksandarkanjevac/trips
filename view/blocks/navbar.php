<body>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>
            <ul class="nav navbar-nav">
                
                <?php
                if (!core\App::is_guest()) {
                    echo '<li><a href="index.php">Trips</a></li>';                    
                    echo '<li><a href="index.php?r=uploads">Upload trip</a></li>';
                    echo '<li><a href="index.php?r=logout">Logout</a></li>';
                } else {
                    echo '<li><a href="index.php">Login</a></li>';
                }
                
                ?>

            </ul>            
        </div>
    </div>
</nav>
