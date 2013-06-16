<!--  @autor: Beate Gericke, Maike Schröder (HTML-Gerüst) -->

<!-- TODO kommentieren und Help-Fenster -->

<div class="formular">
        <fieldset class="form_field">
            <legend>Registrierung</legend>
                <form class="register" enctype="multipart/form-data" action="?page=register&action=save" method="post">


                    <!-- ("lastname" => "", "firstname" => "", "username" => "", "usernameCharacters" => "", "usernameUsed" => "", "password" => "",
                    "passwordConfirm" => "", "passwordConfirmFalse" => "", "email" => "", "emailFalse" => "", "emailUsed" => "") -->
                    <p>
                        <label for="lastname">Nachname</label>
                        <input id="lastname" type="text" name="lastname" />
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['lastname'] ?></p>


                    <p>
                        <label for="firstname">Vorname</label>
                        <input id="firstname" type="text" name="firstname" />
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['firstname'] ?></p>


                    <p>
                        <label for="username">Benutzername</label>
                        <input id="username" type="text" name="username" />
                        <p class="formerror"> <?php echo  $displayData ['formerrors'] ['username'] ; ?> </p>
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['usernameCharacters'] ?></p>
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['usernameUsed'] ?></p>


                    <p>
                        <label for="password">Passwort</label>
                        <input id="password" type="password" name="password" />
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['password'] ?></p>


                    <p>
                        <label for="confirmpw">Passwort best&auml;tigen</label>
                        <input id="confirmpw" type="password" name="confirmpw" />
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['passwordConfirm'] ?></p>
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['passwordConfirmFalse'] ?></p>


                    <p>
                        <label for="email">E-Mail</label>
                        <input id="email" type="email" name="email">
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['email'] ?></p>
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['emailFalse'] ?></p>
                        <p class="formerror"><?php echo $displayData ['formerrors'] ['emailUsed'] ?></p>

                    <p>
                    <button class="send" type="submit">Registieren</button>
                    </p>


            </form>



        </fieldset>
    </div>