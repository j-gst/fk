<!-- die Start/Übersichts-Seite -->

<!-- im Inhaltsfeld werden Bilder aus der Datenbank ausgegeben. Hier findet die Strukturierung des Inhaltes statt. -->
<div class="images">
	
	<!-- in der Schleife wird angeordnet, dass für jedes Bild das in der Datenbank liegt ein div class=imagearea angelegt wird 
		und die Daten aus der Datenbak dort strukturiert eingefügt werden -->
	<?php foreach ($displayData['images'] as $img){?>
	<div class="imagearea">
	    <h2><?php echo $img->titel ?></h2>
		<div class="image">
		<a href="<?php echo $img->imgLink ?>">
		<img src="<?php echo $img->thumbnail ?>">
		</a>
		</div>
		<div class="imagetext">
		Hochgeladen am: <?php echo $img->date ?><br>
		User: <?php echo $img->user ?><br><br>
		<?php echo $img->desc ?>
		</div>
		<div class="comment">
			<!-- Kommentare und Bewertungen noch funktionslos -->
		<!-- 29 Bewertungen: 4.3/5<a href=""> jetzt bewerten</a>-->
		6 Kommentare<a href=""> lesen</a>
		</div>
	</div>
    <?php } // end foreach ?>



	<!-- im div class=next ist die Paginierung und die Möglichkeit Seiten vor und zurück zu blättern oder zu springen untergebracht -->
	<!-- ToDo: Einfügen, dass bei steigender Seitenzahl nicht alle eitenzahlen angezeigt werden -->
	<div class="next" >


		<a href="index.php">first </a>
		<?php for($i = 1 ; $i <= $displayData['pagination']; $i++){ ?>
		<a href="index.php?offset=<?php echo $i ?>"><?php echo $i ?> </a>
		<?php } ?>
		<a href=""> next</a>
		<a href=""> last</a>

	</div>
</div>