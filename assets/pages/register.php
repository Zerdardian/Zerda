<?php
    $zerdardian = new Zerdardian;
    $form = new Register($zerdardian->returnSQL());
    $login = new Login($zerdardian->returnSQL());
    unset($zerdardian);

    $login->checkifLogged();

    $form->CreateForm('register');
    $form->formDate('Geboortedatum', true, 'Vul je geboortedatum in', false);
    $form->formString('Username', '100', true, 'Voer een Username in', 'Kies een Username');
    $form->formString('Email', null, true, 'Geldig Email adres', 'Kies een geldig email adres!');
    $form->formPassword('Password', null, true, 'Voer een wachtwoord in', 'Voer je wachtwoord in!');
    $form->formPassword('rePassword', null, true, 'Voer een wachtwoord in', 'Voer opnieuw wachtwoord in!');
    $form->formSubmit('Versturen', 'Registreren');


    $registerform = $form->buildForm();
?>
<?=$form->errors();?>
<div class="register" id="register">
    <div class="registerform" id="registerform">
        <?=$registerform?>
    </div>
</div>

<!-- <input type="date" name="" id=""> -->