<?php
    $form = new Register;
    $login = new Login;

    $login->checkifLogged();

    $form->CreateForm('register');
    $form->formString('Username', '100', true, 'Voer een Username in', 'Kies een Username');
    $form->formString('Email', null, true, 'Geldig Email adres', 'Kies een geldig email adres!');


    $registerform = $form->buildForm();
?>

<div class="login" id="login">
    <div class="loginform" id="loginform">
        <?=$registerform?>
    </div>
</div>