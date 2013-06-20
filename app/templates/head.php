<?php
/**
 * HTML Header
 * @author  Beate Gericke, Gerrit Storm
 */
?>
<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 strict//EN" "http://www.w3.org/TR/xhtml1-strict.dtd">
<html>
<head>
    <title>FotoKommentare</title>
    <meta name="author" content="Beate, Maike, Gerrit, Thies" />
    <meta name="description" content="Fotos und Kommentare" />
    <meta name="keywords" content="Foto Kommentar" />
    <meta name="date" content="2013-04-09" />
    <meta name="robots" content="index, follow" />
    <link href="styles/style.css" type="text/css" rel="stylesheet">
</head>
<body>

<!-- der div class=site umschliesst den gestalteten Inhalt, um diesen im Browser ausrichten zu koennen -->
<div class="site">

<div id="header">
    <h1>Fotoblog</h1>
</div>

<!-- die Menue-Bar -->
<div class="menue">
<ul>
	<li
	<?php if(!isset($_REQUEST['page'])||$_REQUEST['page']=="main") echo  ' id="menue_selected"'; ?>><a
		href="index.php">Home</a></li>
	<li
	<?php if(isset($_REQUEST['page'])&& $_REQUEST['page']=="upload") echo  ' id="menue_selected"'; ?>><a
		href="index.php?page=upload">Upload</a></li>
	<li
	<?php if(isset($_REQUEST['page'])&& $_REQUEST['page']=="admin") echo  ' id="menue_selected"'; ?>><a
		href="index.php?page=admin">Admin</a>
		<div class="dropdown-menue">
                <ul>
                     <li><a href="index.php?page=admin&amp;m=u">Benutzer</a></li>
                     <li><a href="index.php?page=admin&amp;m=r">Rechte</a></li>
                 </ul>
           </div>
	</li>
	
	<li>
	
	
<?php /** @author Maike Scroeder */?>
	    <a href="#">User</a>
          <div class="dropdown-menue">
                <ul>
                     <li><a href="#">User 1</a></li>
                     <li><a href="#">User 2</a></li>
                     <li><a href="#">User 3</a></li>
                     <li><a href="#">User 4</a></li>
                 </ul>
           </div>
    </li>
    
    
	<form class="search-form" action="index.php?page=search" method="post">
	<input type="text" name="tosearch"> <input type="submit" name="search"
		value="Suche"></form>
	</li>
</ul>
</div>



<div class="content">

<!-- Das Login-Feld -->
<div class="login"><?php if ( $user->isLoggedIn() ){ ?>
    <p>Sie sind angemeldet als <?php echo $user->getUsername() ?></p>
    <a href="index.php?page=login&amp;action=logout">Abmelden</a> <?php } else{ ?>
    <form action="index.php?page=login&amp;action=login" method="post"><label>Benutzername</label><br>
    <input type="text" name="username"><br>
    <label>Passwort</label><br>
    <input type="password" name="password" value=""> <br />
    <br />
    <input type="submit" name="anmelden" value="Anmelden"> <a
    	href="index.php?page=register">Registrieren</a></form>
    <p class="form_error"><?php } if (isset($_SESSION['loginMsg'])) echo $_SESSION['loginMsg'] ; unset($_SESSION['loginMsg']) ?>
    </p>
</div>