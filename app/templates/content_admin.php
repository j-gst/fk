<?php 
/**
 * Template fuer das Admin Menue (Benutzerverwaltung, Rechteverwaltung)
 * @author Gerrit Storm
 */
?>


	<style type="text/css" media="all">
	
	/* body {
		font: small arial, helvetica, sans-serif;
	} */
	
	#admin_navi ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	
	#admin_navi li {
		display: inline;
		margin: 0 2px 0 0;
	}
	
	#admin_navi a {
		padding: 0 1em;
		text-decoration: none;
		color: #a80;
		background: #fe5;
	}
	
	#admin_navi a:hover {
		background: #fc0;
		color: #540;
	}0123456789a
	
	#admin_navi #admin_navi_selected {
	}
	
	#admin_navi #admin_navi_selected a {
		padding-bottom: 2px;
		font-weight: bold;
		color: black;
		color: black;
		background: #fc0;
	}
	
	#admin_content {
		border-top: 2px solid white;
		background: #fc0;
		padding: 1em;
	}
	
	#admin_content p {
		margin: 0;
		padding: 1em;
		background: white;
	}
	
	h1 {
		font-size: 1.5em;
		color: #fc0;
	}
	
	</style>
	
<div class="admin_field">
<h1>Administrationsbereich</h1>

<div id="admin_navi">
<ul>
	 <li <?php if($displayData['subpage'] == 'rights') echo 'id="admin_navi_selected"' ?>> <a href="index.php?page=admin&amp;m=r">Rechteverwaltung</a></li><!-- 
	--><li <?php if($displayData['subpage'] == 'user') echo 'id="admin_navi_selected"' ?>> <a href="index.php?page=admin&amp;m=u">Benutzerverwaltung</a></li>
</ul>
</div>


<div id="admin_content">

<? 
// Rechteverwaltung  
?>

<?php if($displayData['subpage'] == 'rights'){?>

<div class="formular">
		<fieldset>	
<?php if($displayData['saveMsg']){?>
<p style="float:right;">&Auml;nderungen gespeichert!</p>
<?php } ?>
<form   action="?page=admin" method="post">
		<input type="hidden" name="save_rights" value="1">
		<h3>Rolle - Gast:</h3>
		Ein Gast hat immer folgende Rechte: keine<br><br>
		Zus&auml;tzliche Rechte erteilen:<br>
		<input type="checkbox" <?php echo $displayData['guest_view']; ?> name="guest_view" value="1">
		Bilder ansehen / Download<br>
		<input type="checkbox" <?php echo $displayData['guest_upload']; ?> name="guest_upload" value="1">
		Bilder Upload<br>
		<input type="checkbox" <?php echo $displayData['guest_comment']; ?> name="guest_comment" value="1">
		Bilder kommentieren<br>
		<h3>Rolle - User:</h3>
		Ein User hat immer folgende Rechte: Bilder ansehen / Download<br><br>
		Zus&auml;tzliche Rechte erteilen:<br>
		<input type="checkbox" <?php echo $displayData['user_upload']; ?> name="user_upload" value="1">
		Bilder Upload<br>
		<input type="checkbox" <?php echo $displayData['user_comment']; ?> name="user_comment" value="1">
		Bilder kommentieren<br>
		<input type="checkbox" <?php echo $displayData['user_delete']; ?> name="user_delete" value="1">
		Eigene Bilder und Kommentare l&ouml;schen<br>
		<h3>Rolle - vertrauensw&uuml;rdiger User:</h3>	
		Ein vertrauensw&uuml;rdiger  User hat immer folgende Rechte:<br> 
		Bilder ansehen / Download<br>
		Bilder Upload<br>
		Bilder kommentieren<br>
		Eigene Bilder und Kommentare l&ouml;schen<br><br>
		Zus&auml;tzliche Rechte erteilen:	<br>	
		<input type="checkbox" <?php echo $displayData['tuser_deleteall']; ?> name="tuser_deleteall" value="1">
		Bilder und Kommentare von anderen Usern l&ouml;schen<br><br>
		<input type="submit" name="save" value="Speichern">
</form>
	</fieldset>
</div>

<? 
// Benutzerverwaltung  
?>

<?php }elseif($displayData['subpage'] == 'user'){ ?>



<div class="formular">

<fieldset>	

<?php if($displayData['saveMsg']){?>
<p style="float:right;">&Auml;nderungen gespeichert!</p>
<?php } ?>

<form   action="?page=admin" method="post">

<table>
<tr>
<th>Id</th>
<th>Vorname</th>
<th>Nachname</th>
<th>Benutzername</th>
<th>E-Mail</th>
<th>Rolle</th>
<th>Status</th>
<th> </th><th> </th>
</tr>

<?php foreach($displayData['userlist'] as $sUser){?>

<tr>

<td><?php echo $sUser['Id'] ?></td>
<td><?php echo $sUser['FirstName'] ?></td>
<td><?php echo $sUser['LastName'] ?></td>
<td><?php echo $sUser['UserName'] ?></td>
<td><?php echo $sUser['EMailAdress'] ?></td>
<td><?php echo $sUser['Role'] ?></td>
<td><?php echo $sUser['UserState']?"aktiv":"gesperrt"; ?></td>
<td><a href="index.php?page=admin&amp;id=<?php echo $sUser['Id'] ?>">edit</a></td>
</tr>

<?php } // foreach ?>


</table>
</form>
</fieldset>
</div> 


<? 
// Benutzerverwaltung - einzelner Benutzer  
?>


<?php }elseif($displayData['subpage'] == 'user_edit'){ ?>
<form   action="?page=admin" method="post">
<h2>Benutzer editieren</h2>
<br>
ID: <?php echo $displayData["user"]['Id'] ?><br>
Vorname: <?php echo $displayData["user"]['FirstName'] ?><br>
Nachname: <?php echo $displayData["user"]['LastName'] ?><br>
Benutzername: <?php echo $displayData["user"]['UserName'] ?><br>
E-Mail: <?php echo $displayData["user"]['EMailAdress'] ?><br><br>

Status:<br>
<select name="state">
    <option <?php if($displayData["user"]['UserState'] != 0) echo 'selected="selected"'; ?> >aktiv</option>
    <option <?php if($displayData["user"]['UserState'] == 0) echo 'selected="selected"'; ?> >gesperrt</option>
 
</select><br><br>
Rolle:<br>
<select name="role">
<?php 
	$options = "";
	foreach ($displayData['rolelist'] as $role){
		$selected = "";
		if($role['Name'] == $displayData["user"]['Role']) $selected = 'selected="selected"';
		$options .= "<option ".$selected.">".$role['Name']."</option>";
	}
	echo $options;
 ?>
</select><br><br>
<input type="hidden" name="id" value="<?php echo $displayData["user"]['Id'] ?>">

<input type="submit" name="save_user" value="Speichern">
<input type="submit" name="delete_user" value="L&ouml;schen">
</form>
<?php } ?>
</div>
