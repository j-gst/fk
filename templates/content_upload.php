



		<div class="formular">
			<form enctype="multipart/form-data" action="?page=upload&action=save" method="post">
				<fieldset>
					<legend>Bild-Upload</legend>
						<div class="left">
							<input type="hidden" name="max_file_size" value="9000000000">
								<p>Datei ausw&auml;hlen: <input type="file" name="Durchsuchen" value="Durchsuchen" /></p>
								<p>Bildtitel:<input class="normal" type="text" name="Bildtitel" /></p>
								<p>Bildbeschreibung:</p>
									<textarea name="Bildbeschreibung" rows="10" cols="60">Hier k&ouml;nne Sie Beschreibungen zu Ihrem Bild eingeben. 
									Z.B. wo und wann die Aufnahme entstand, 
									welche Kameraeinstellungen Sie verwendet haben und alles was Sie dazu zu sagen haben.
									</textarea>
						</div>
					<div class="checkbox-categories">
						<p>Kategorie/n:</p>
							<lablel><input type="checkbox" name="Kategorien" value="Landschaft">Landschaft</lablel>
							<label><input type="checkbox" name="Kategorien" value="Portraits">Portrais</label>
							<label><input type="checkbox" name="Kategorien" value="Kinder">Kinder</label>
							<label><input type="checkbox" name="Kategorien" value="Familie">Familie</label>
							<p></p>
							<input type="submit" value="hochladen">
					</div>	
					</fieldset>
				</form>
		</div>		