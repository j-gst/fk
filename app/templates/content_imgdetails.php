<?php 
/**
 * die Bilddetail-Seite
 * Template fuer die generische Fehlerseite
 * @author Thies Schillhorn, 20130524
 */
?>


<!-- im Inhaltsfeld werden die Details zu dem selektierten Bild aus der Datenbank ausgegeben. 
Hier findet die Strukturierung des Inhaltes statt. -->
<div
	class="images">
	<!-- in der Schleife wird angeordnet, dass fuer jedes Bild das in der Datenbank liegt ein div class=imagearea angelegt wird 
		und die Daten aus der Datenbank dort strukturiert eingefuegt werden -->
	<?php if (isset($displayData['imgdetails'])) foreach ($displayData['imgdetails'] as $key => $img){?>

	<div class="imagearea">
		<a id="<?php echo $img->id ?>">
			<h2> <!--  Titel des Bildes -->
				<?php echo $img->titel ?>
			</h2>
			<div class="image"> <!-- Thumbnail des Bildes;  wird es geklickt, wird das Bild in originalgröße dargestellt -->
				<a href="index.php?image=<?php echo $img->id ?>" > 
				<img src="index.php?tn=1&amp;id=<?php echo $img->id ?>">
				</a>
			</div>
			<div class="imagetext"> <!--  Angaben des hochladenden Users zu diesem Bild -->
				Hochgeladen am:
				<?php echo $img->date ?>
				<br> User:
				<?php echo $img->user ?>
				<br> <br>
				<?php echo $img->desc ?>
			</div>
			<div class="comment">

				<?php echo $img->commentsCount; ?>
				Kommentare



			</div> <!--  class=comment-->
			
			<div  > <!-- Kommentare anzeigen -->
				<?php  foreach($img->comments as $ckey => $comment){ ?>
				<div class="singlecomment">
					<b>Kommentar Nr.<?php echo ' ' , $ckey+1 , ' von ',  $comment['UserName'],
					' am ', $comment['date'];?>
					</b> <br>
					<p>
						<?php echo $comment['Comment']; ?>
					</p>
				</div>
  			    <?php } // end foreach comments ?>
			</div>

			<div > <!--  Kommentar eingeben -->
				<form action="?p=<?php echo $displayData['p'],'#',$img->id ?>"
					method="post">
					<input type="hidden" name="detailpage" value="1">
					<input type="hidden" name="id" value="<?php echo $img->id;?>">
					<textarea name="comment_text" rows="10" cols="60"></textarea>
					<input type="submit" name="save_comment" value="save comment">
				</form>
			</div>
	</div>
	<?php } // end foreach images?>
</div>
