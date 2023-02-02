<div class="usermenu">
    <div class="background">

    </div>
    <div class="info">
        <div class="profilepicture">
            <div class="image">
                <img src="<?= $profilepicture ?>" alt="<?= $_SESSION['user']['username'] ?>">
            </div>
        </div>
        <div class="username"><?= $_SESSION['user']['username'] ?></div>
    </div>
    <div class="usermenu col-xs-4" id="usermenu">
        <a href="/user/">
            <div class="menuitem">
                Home
            </div>
        </a>
        <a href="/user/userinfo/">
            <div class="menuitem">
                Userinfo
            </div>
        </a>
        <a href="/user/connections/">
            <div class="menuitem">
                Connecties
            </div>
        </a>
        <a href="/user/settings/">
            <div class="menuitem">
                Instellingen
            </div>
        </a>
    </div>
</div>