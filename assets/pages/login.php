<?php
    $form = new Form;
    $login = new Login;

    $login->checkIfLogged();

    $form->CreateForm('login');
    $form->formString('User', 100, true, 'Username or Email', 'Enter your username or Email', false);
    $form->formPassword('Password', null, true, 'Enter your password...', 'Password', false);
    $form->formSubmit('Submit', 'Custom verzenden');

    $loginform = $form->buildForm();
?>

<div class="login" id="login">
    <div class="loginform" id="loginform">
        <?=$loginform?>
    </div>
</div>