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
			<h1><?php echo $conf->title ?> </h1>
		</div>
				
		<!-- die Menue-Bar -->
		<!-- div class="menue" -->
		<div id="navi">

			
			<ul>
			<li>
				<a href="index.php">Home</a></li>
			<li><a href="#">Kategorie</a>
					<div class="dropdown-menue">
						<ul>
							<li><a href="#"><?php echo $conf->categories['1']['name'] ?></a></li>
							<li><a href="#"><?php echo $conf->categories['2']['name'] ?></a></li>
							<li><a href="#"><?php echo $conf->categories['3']['name'] ?></a></li>
							<li><a href="#"><?php echo $conf->categories['4']['name'] ?></a></li>
						</ul>
					</div>
           </li>
			<li><a href="#">User</a>
					<div class="dropdown-menue">
						<ul>
							<li><a href="#">User 1</a></li>
							<li><a href="#">User 2</a></li>
							<li><a href="#">User 3</a></li>
							<li><a href="#">User 4</a></li>
						</ul>
					</div>
           </li>
           <!--  Thies Schillhorn: Upload wieder einfuegen, wenn registrierter Benutzer eingeloggt -->
	       <?php if ( $user->isLoggedIn() ){ ?>
			  <li><a href="index.php?page=upload">Upload</a></li>
		   <?php } ?>           
	    </ul>
	
			<form class="search-form" action="index.php?page=search" method="post">
				<input type="text" name="s">
				<button type="submit">go</button>
			</form>	

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
			