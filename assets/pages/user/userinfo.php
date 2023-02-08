<?php
$zerdardian = new Zerdardian();
$user = new User($zerdardian->returnSQL());
$form = new Form();

$form->CreateForm('userinfo');
$form->formString('firstname', 30, true, 'Voornaam', 'Voornaam');
$form->formString('between', 20, false, 'Tussenvoegsels', 'Tussenvoegels');
$form->formString('lastname', 60, true, 'Achternaam', 'Achternaam');
$form->formDate('age', true, 'Geboortedatum');
$form->formSubmit('update', 'Updaten');

$formuserinfo = $form->CreateForm('Userinfo');
?>
<div class="userinfo">
    <div class="form"></div>
    <div class="other">
        <div class="username">
            <div class="upperusername">
                <label for="upperusername">Voeg hoofdletters toe!</label>
                <input type="text" name="upperusername" id="upperusername" value="<?=$user->returnUsername();?>">
                <a href="/user/userinfo/username">
                    <button>Request new username</button>
                </a>
            </div>
        </div>
        <div class="password">
            <a href="/user/userinfo/password/">
                <button>Request a Password Change</button>
            </a>
        </div>
    </div>
</div>