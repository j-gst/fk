<!-- die Start/Übersichts-Seite -->

<!-- im Inhaltsfeld werden Bilder aus der Datenbank ausgegeben. Hier findet die Strukturierung des Inhaltes statt. -->
<div class="images">
	
	<!-- in der Schleife wird angeordnet, dass für jedes Bild das in der Datenbank liegt ein div class=imagearea angelegt wird 
		und die Daten aus der Datenbak dort strukturiert eingefügt werden -->
	<?php foreach ($displayData['images'] as $key => $img){?>
	
	<div class="imagearea">
	    <a  name="<?php echo $img->id ?>">
	    <h2 style="display: inline;"><?php echo $img->titel ?></h2>
		</a>
		
		<?php if($img->archive){ ?>
		<a  href="index.php?page=gallery&id=<?php echo $img->archive ?>">
		(komplettes Archiv)
		</a>
		<?php } ?>
		<br>
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
	
		<?php echo $img->commentsCount; ?> Kommentare 
		
		<?php if ($img->commentsCount > 0) { ?>
		(<a  id="showcomments_<?php echo $key;?>" onclick="showHideComments('commentsarea_<?php echo $key;?>','showcomments_<?php echo $key;?>' );return false;">SHOW</a>)
        <?php } ?>
		
		<a   id="showcommentinput_<?php echo $key;?>" onclick="showHideCommentInput('commentinput_<?php echo $key;?>','showcommentinput_<?php echo $key;?>' );return false;">COMMENT</a><br> 		
		
		</div> <!--  class=comment-->
		<div class="commentarea" id="commentsarea_<?php echo $key;?>">
			
	
			<?php  foreach($img->comments as $ckey => $comment){ ?>
			<div class="singlecomment">
			<b>Kommentar Nr.<?php echo ' ' , $ckey+1 , ' von ',  $comment['UserName'],
                            		   ' am ', $comment['date'];?></b> <br>
			<p><?php echo $comment['Comment']; ?></p>

			</div>
			<?php } // end foreach comments ?>
			
			</div>
			
		
		
		
		<div class="commentinput" id="commentinput_<?php echo $key;?>">
		<form action="?p=<?php echo $displayData['p'],'#',$img->id ?>" method="post">
		<input type="hidden" name="id" value="<?php echo $img->id;?>">
		<textarea name="comment_text" rows="10" cols="60"></textarea>
		<input type="submit" name="save_comment" value="save comment">
		</form>
		</div>
		
		
		
	</div> <!-- imagearea -->

    <?php } // end foreach images?>



	<!-- im div class=next ist die Paginierung und die Möglichkeit Seiten vor und zurück zu blättern oder zu springen untergebracht -->
	<!-- ToDo: Einfügen, dass bei steigender Seitenzahl nicht alle eitenzahlen angezeigt werden -->
	<div class="next" >


		<!-- <a href="index.php">first </a> -->
		<?php for($i = 1 ; $i <= $displayData['pagination']; $i++){ ?>
		<a href="index.php?p=<?php echo $i ?>"><?php echo $i ?> </a>
		<?php } ?>
		<!--
		<a href=""> next</a>
		<a href=""> last</a>
		-->

	</div>
</div>