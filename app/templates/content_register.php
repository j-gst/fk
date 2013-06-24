<?php 
/**
 * Template fuer die die Registrieren-Seite
 * @author Beate Gericke, Maike Schroeder (Grundgeruest erste statische HTML-Version)
 */
?>
<?php if(!$displayData['registered']){ ?>
<div class="formular">
		<fieldset class="form_field">	
			<legend>Registrierung</legend>
				<form class="register" enctype="multipart/form-data" action="?page=register&amp;action=save" method="post">
					
						<label for="lastname">Nachname</label>
						<input id="lastname" type="text" name="lastname" value="<?php  echo isset($_POST['lastname'])?$_POST['lastname']:""; ?>"/>
						<p class="formerror"><?php echo $displayData ['formerrors'] ['lastname'] ?></p>

						<label for="firstname">Vorname</label>
						<input id="firstname" type="text" name="firstname" value="<?php  echo isset($_POST['firstname'])?$_POST['firstname']:""; ?>"/>
						<p class="formerror"><?php echo $displayData ['formerrors'] ['firstname'] ?></p>

						<label for="username">Benutzername</label>
						<input id="username" type="text" name="username" value="<?php  echo isset($_POST['username'])?$_POST['username']:""; ?>"/>
						<p class="formerror"> <?php echo  $displayData ['formerrors'] ['username'] ; ?> </p>
						<p class="formerror"><?php echo $displayData ['formerrors'] ['usernameCharacters'] ?></p>
						<p class="formerror"><?php echo $displayData ['formerrors'] ['usernameUsed'] ?></p>

						<label for="password">Passwort</label>
						<input id="password" type="password" name="password" />
						<p class="formerror"><?php echo $displayData ['formerrors'] ['password'] ?></p>

						<label for="confirmpw">Passwort best&auml;tigen</label>
						<input id="confirmpw" type="password" name="confirmpw" />
						<p class="formerror"><?php echo $displayData ['formerrors'] ['passwordConfirm'] ?></p>
						<p class="formerror"><?php echo $displayData ['formerrors'] ['passwordConfirmFalse'] ?></p>

						<label for="email">E-Mail</label>
						<input id="email" type="email" name="email" value="<?php  echo isset($_POST['email'])?$_POST['email']:""; ?>">
						<p class="formerror"><?php echo $displayData ['formerrors'] ['email'] ?></p>
						<p class="formerror"><?php echo $displayData ['formerrors'] ['emailFalse'] ?></p>
						<p class="formerror"><?php echo $displayData ['formerrors'] ['emailUsed'] ?></p>
					<button class="send" type="submit">Registieren</button>
			</form>	
		</fieldset>		
	</div>
<?php }else{ ?>
	<b>Sie haben sich erfolgreich registriert und koennen sich jetzt anmelden!</b>
<?php } ?>