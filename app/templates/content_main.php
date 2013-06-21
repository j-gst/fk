<?php 
/**
 * Template fuer die die Haupt/Uebersichts-Seite
 * @author Gerrit Storm, Beate Gericke (Nebenautor), Maike Schroeder (Grundgeruest erste HTML-Version)
 */
?>

<!-- im Inhaltsfeld werden Bilder aus der Datenbank ausgegeben. Hier findet die Strukturierung des Inhaltes statt. -->
<div class="images">
	
	<!-- in der Schleife wird angeordnet, dass fuer jedes Bild das in der Datenbank liegt ein div class=imagearea angelegt wird 
		und die Daten aus der Datenbank dort strukturiert eingefuegt werden -->
	<?php foreach ($displayData['images'] as $key => $img){?>
	
	<div class="imagearea">
	    <a  id="<?php echo $img->id ?>">
	    <b style="display: inline; font-size:large;"><?php echo $img->titel ?></b>
		</a>
		<?php if($img->archive){ ?>
		<a  href="index.php?page=gallery&amp;id=<?php echo $img->archive ?>">
		(komplettes Archiv)
		</a>
		<?php } ?>
		<br>
		<div class="image">
		<a href="index.php?image=<?php echo $img->id ?>" > 
		<img alt="Bild" src="index.php?tn=1&amp;id=<?php echo $img->id ?>">
		</a>
		</div>
		<div class="imagetext">
    		Hochgeladen am: <?php echo $img->date ?><br>
    		User: <?php echo $img->user ?><br><br>
    		<?php echo $img->desc ?>
		</div>
			 <a href="index.php?page=imgdetails&amp;imgid=<?php echo $img->id ?>" > Details </a>
		<div class="comment">
	 
		<?php echo $img->commentsCount; ?> Kommentare 
		
		<?php if ($img->commentsCount > 0) { ?>
		(<a  id="showcomments_<?php echo $key;?>" onclick="showHideComments('commentsarea_<?php echo $key;?>','showcomments_<?php echo $key;?>' );return false;">SHOW</a>)
        <?php }// if ?>
		<a   id="showcommentinput_<?php echo $key;?>" onclick="showHideCommentInput('commentinput_<?php echo $key;?>','showcommentinput_<?php echo $key;?>' );return false;">COMMENT</a><br> 		
		
		</div>
		<div class="commentarea" id="commentsarea_<?php echo $key;?>">
			<?php  foreach($img->comments as $ckey => $comment){ ?>
			<div class="singlecomment">
			<b>Kommentar Nr.<?php echo ' ' , $ckey+1 , ' von ',  $comment['UserName'],
                            		   ' am ', $comment['date'];?></b> <br>
			<p><?php echo $comment['Comment']; ?></p>
			</div>
			<?php } // end foreach comments ?>
		</div>
			
		<?php 
		// Input Formular fuer Kommentare
		?>
		<div class="commentinput" id="commentinput_<?php echo $key;?>">
    		<form action="?p=<?php echo $displayData['p'],'#',$img->id ?>" method="post">
        		<input type="hidden" name="id" value="<?php echo $img->id;?>">
        		<textarea name="comment_text" rows="10" cols="60"></textarea>
        		<input type="submit" name="save_comment" value="save comment">
    		</form>
		</div>
	</div> <!-- imagearea -->

    <?php } // end foreach images?>


	<!-- Paginierung und die Moeglichkeit Seiten vor und zurueck zu blaettern oder zu springen -->
	<div class="next" >
		<?php for($i = 1 ; $i <= $displayData['pagination']; $i++){ ?>
		<a href="index.php?p=<?php echo $i ?>"><?php echo $i ?> </a>
		<?php } ?>
	</div>
</div> <!--  class=images  -->