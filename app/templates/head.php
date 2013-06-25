<?php
/**
 * HTML Header
 * @author  Beate Gericke, Gerrit Storm
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>FotoKommentare</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="author" content="Beate, Maike, Gerrit, Thies" />
    <meta name="description" content="Fotos und Kommentare" />
    <meta name="keywords" content="Foto Kommentar" />
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
<form class="search-form" action="index.php?page=search" method="post">
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
	<li  style="text-align:right;">
	Filter: <?php if(isset($_SESSION["ufilter"]) && $_SESSION["ufilter"] !== "0")  
	                    echo "(" , $_SESSION["ufilter"] == 1 ? "Gast" : $_SESSION["ufilter"] ,")"; 
	       ?>
	</li>
	<li>
<?php /** @author Maike Scroeder (Ausklappmenue) */?>
	    <a href="#">Benutzer</a>
          <div class="dropdown-menue">
                <ul>
                <li><a href="index.php?ufilter=0">Alle</a></li>
                <li><a href="index.php?ufilter=1">Gast</a></li>
                <?php foreach ($config->userList as $u ){?>     
                     <li><a href="index.php?ufilter=<?php echo $u ?>"><?php echo $u ?></a></li>
                <?php } // foreach?>
                 </ul>
           </div>
    </li>

    <li style="width:20%;">
	    <input type="text" name="tosearch">
	    <input type="submit" name="search" value="Suche">
	</li>

</ul>

</div>
</form>

<div class="sitecontent">

<!-- Das Login-Feld -->
<div class="login"><?php if ( $user->isLoggedIn() ){ ?>
    <p>Sie sind angemeldet als <?php echo $user->getUsername() ?></p>
    <a href="index.php?page=login&amp;action=logout">Abmelden</a><?php } else{ ?>
    <form action="index.php?page=login&amp;action=login" method="post"><label>Benutzername</label><br>
    <input type="text" name="username"><br>
    <label>Passwort</label><br>
    <input type="password" name="password" value=""> <br />
    <br />
    <input type="submit" name="anmelden" value="Anmelden"> <a
    	href="index.php?page=register">Registrieren</a></form>
    <?php } if (isset($_SESSION['loginMsg'])) echo '<p class="form_error">',$_SESSION['loginMsg'],'</p>' ; unset($_SESSION['loginMsg']) ?>
</div>