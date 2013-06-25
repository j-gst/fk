<?php 
/**
 * Template Upload Formular
 * @author  Beate Gericke
 */
?>
		<div class="formular" >
			<form enctype="multipart/form-data" action="?page=upload&amp;action=save" method="post">
				<fieldset class="form_field">
					<legend>Bild-Upload</legend>
						<div class="left">
							<input type="hidden" name="max_file_size" value="9000000000">
								<p>Datei ausw&auml;hlen: </p><br><input size="80" type="file" name="Durchsuchen" value="Durchsuchen" />
								  <p class="form_error"> <?php  echo $displayData['uploaderror']  ?> </p>
								<p>Bildtitel:</p><br><input size="80" class="normal" type="text" name="Bildtitel" />
								<p>Bildbeschreibung:</p>
<textarea placeholder="Hier k&ouml;nne Sie Beschreibungen zu Ihrem Bild eingeben. Z.B. wo und wann die Aufnahme entstand, welche Kameraeinstellungen Sie verwendet haben und alles was Sie dazu zu sagen haben." name="Bildbeschreibung" rows="10" cols="60"></textarea>
						</div>
					<div class="checkbox-categories">
						<p>Kategorie/n:</p>
							<input type="checkbox" name="Kategorien" value="Landschaft">Landschaft<br>
							<input type="checkbox" name="Kategorien" value="Portraits">Portrais<br>
							<input type="checkbox" name="Kategorien" value="Kinder">Kinder<br>
							<input type="checkbox" name="Kategorien" value="Familie">Familie<br>
						
							<input type="submit" value="hochladen">
					</div>	
					<div class="info">
						<!--  <img alt="helpimg" src="../public/styles/img/icon_question.gif" id="helpWindow" onclick="openHelpWindow(uploadHelp.php);"/> -->
					</div>
					</fieldset>
				</form>
		</div>		