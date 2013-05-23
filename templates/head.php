<!-- @autor: Beate Gericke, Gerrit Storm, Maike Schröder -->

<!doctype html>
<html>
	<head>
		<title> Startseite </title> <!-- hier muss noch eine Variale eingearbeitet werden, damit immer der Titel der aktuellen Seite angezeigt wird ToDo: Beate -->
		<link href="styles/style.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="js/main.js"></script>
	</head>	
	<body>
		<!-- der div class=site umschließt den gestalteten Inhalt, um diesen im Browser ausrichten zu können -->
		<div class="site">
		<div id="header">
			<h1>Fotoblog</h1>
		</div>
				
		<!-- die Menue-Bar -->
		<div class="menue">

			
			<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href=".">Suche</a></li>
			<li><a href="index.php?page=upload">Upload</a></li>
			<li><a href="index.php?page=register">Register</a></li>
			</ul>

		</div>
		
		
	
		<!-- hier beginnt der (zu Teil) austauschbare Inhalt -->
		<div class="content">

			<!-- Das Login-Feld -->
			<div class="login">		
				
				<?php if ( $user->isLoggedIn() ){ ?>
				
				    <p> Sie sind angemeldet als <?php echo $user->getUsername() ?> </p>
					<a href="index.php?page=login&action=logout">Abmelden</a>
				
				<?php } else{ ?>
					<form action="index.php?page=login&action=login" method="post">
						<label>Benutzername</label><br>
						<input type="text" name="username"><br>
						<label>Passwort</label><br>
						<input type="password" name="password" value="">
						<br /><br /><input type="submit" name="anmelden" value="Anmelden">
						<a href="index.php?page=register">Registrieren</a>
					</form>
			    <p class="form_error"> <?php } if (isset($_SESSION['loginMsg'])) echo $_SESSION['loginMsg'] ; unset($_SESSION['loginMsg']) ?> </p>
			</div> 
			