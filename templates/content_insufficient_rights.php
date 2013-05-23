<!-- autor: Gerrit Storm -->

<!-- TODO Kommentieren -->

<div style="height:220px;color:red;">
<?php if($user->isLoggedIn()){ ?>

<h2><?php ?> Sie haben nicht die n&ouml;tige Berechtigung!</h2>

<?php } else {?>

<h2><?php ?> Sie m&uuml;ssen angemeldet sein!</h2>
<a href="index.php?page=register">Jetzt Registrieren</a>
<?php } ?>
</div>