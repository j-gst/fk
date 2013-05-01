<!doctype html>
<html>
	<head>
		<title> Startseite </title> <!-- hier muss noch eine Variale eingearbeitet werden, damit immer der Titel der aktuellen Seite angezeigt wird ToDo: Beate -->
		<link href="styles/style.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="main.js"></script>
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
				
					<form action="index.php?page=login">
						<p><label>Benutzername</label></p>
						<input type="text" name="username">
						<p><label>Passwort</label></p>
						<input type="password" name="pass" value="">
						<br /><br /><input type="submit" name="anmelden" value="Anmelden">
						<a href="index.php?page=register">Registrieren</a>
					</form>
			
			</div> 
			