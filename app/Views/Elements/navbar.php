<nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
    <!-- Brand -->
    <a class="navbar-brand" href="<?= ROOT ?>"><?= PROJECT ?></a>

    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <span class="navbar-text mr-auto">...a fento-framework PHP</span>
        <?= isset($_SESSION['auth']) ? "<span class='navbar-text'><a href='".ROOT."logoff'>logoff</a></span>" : "" ?>
        <ul class="navbar-nav">
            <!-- <li class="nav-item"><a class="nav-link" href="#">Link</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Link</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Link</a></li>  -->
        </ul>
    </div> 
</nav>