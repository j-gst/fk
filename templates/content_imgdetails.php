<!-- die Bilddetail-Seite / autor Thies Schillhorn, 20130524 -->

<!-- im Inhaltsfeld werden die Details zu dem selektierten Bild aus der Datenbank ausgegeben. 
Hier findet die Strukturierung des Inhaltes statt. -->
<div
	class="images">
	<!-- in der Schleife wird angeordnet, dass fuer jedes Bild das in der Datenbank liegt ein div class=imagearea angelegt wird 
		und die Daten aus der Datenbank dort strukturiert eingefuegt werden -->
	<?php foreach ($displayData['imgdetails'] as $key => $img){?>

	<div class="imagearea">
		<a name="<?php echo $img->id ?>">
			<h2> <!--  Titel des Bildes -->
				<?php echo $img->titel ?>
			</h2>
			<div class="image"> <!-- Thumbnail des Bildes;  wird es geklickt, wird das Bild in originalgröße dargestellt -->
				<a href="<?php echo $img->imgLink ?>"> 
				<img src="<?php echo $img->thumbnail ?>">
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
			<div class="comment"> <!-- Vorhandene Kommentare ein- und ausblendbar, sowie evtl. neuen Kommentar eingeben -->

				<?php echo $img->commentsCount; ?>
				Kommentare

				<?php if ($img->commentsCount > 0) { ?>
				(<a id="showcomments_<?php echo $key;?>"
					onclick="showHideComments('commentsarea_<?php echo $key;?>','showcomments_<?php echo $key;?>' );return true;">SHOW</a>)
				<?php } ?>

				<a id="showcommentinput_<?php echo $key;?>"
					onclick="showHideCommentInput('commentinput_<?php echo $key;?>','showcommentinput_<?php echo $key;?>' );return false;">COMMENT</a><br>

			</div> <!--  class=comment-->
			
			<div class="commentarea" id="commentsarea_<?php echo $key;?>"> <!-- Kommentare anzeigen -->
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

			<div class="commentinput" id="commentinput_<?php echo $key;?>"> <!--  Kommentar eingeben -->
				<form action="?p=<?php echo $displayData['p'],'#',$img->id ?>"
					method="post">
					<input type="hidden" name="id" value="<?php echo $img->id;?>">
					<textarea name="comment_text" rows="10" cols="60"></textarea>
					<input type="submit" name="save_comment" value="save comment">
				</form>
			</div>
	</div>
	<?php } // end foreach images?>
</div>
