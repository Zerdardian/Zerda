<?php
$zerdardian = new Zerdardian();
$user = new Admin($zerdardian->returnSQL());
$recent = $user->recentUser();
$role = $user->returnrole();
?>

<div class="adminmain">
    <div class="recent">
        <div class="row-2">
            <!-- New Users! -->
            <div class="newuser">
                <div class="user">
                    <div class="picture">
                        <div class="image">
                            <img src="/assets/images/basis/user-picture.png" alt="zerdardian">
                        </div>
                    </div>
                    <div class="userinfo">
                        <div class="username"><?= $recent['username'] ?></div>
                        <div class="email"><?= $recent['email'] ?></div>
                        <div class="created"><?= $recent['created'] ?></div>
                    </div>
                </div>
                <?php
                if ($role == 2 || $role == 3) {
                ?>
                    <div class="link">
                        <a href="/admin/user/<?= $recent['id'] ?>">
                            <button>Edit <?= $recent['username'] ?></button>
                        </a>
                    </div>
                <?php
                }
                ?>

            </div>
            <!-- Unavalable. -->
            <div class="newcomment"></div>
        </div>
    </div>
    <div class="updated">
        <div class="userupdated"></div>
    </div>
</div>