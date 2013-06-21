<?php 
/**
 * die Suchergebnisseite
 * @author  Thies Schillhorn, 20130526 
 */
?>
<!-- im Inhaltsfeld werden Bilder aus der Datenbank ausgegeben. Hier findet die Strukturierung des Inhaltes statt. -->
<div
    class="images">

    <!-- in der Schleife wird angeordnet, dass für jedes Bild das in der Datenbank liegt ein div class=imagearea angelegt wird
        und die Daten aus der Datenbank dort strukturiert eingefügt werden -->
    <?php if (isset($displayData['search'])) foreach ($displayData['search'] as $key => $img){?>

<div class="imagearea">
<a name="<?php echo $img->id ?>">
<h2>
<!-- Titel des Bildes -->
<?php echo $img->titel ?>
</h2>
<div class="image">
<a href="index.php?image=<?php echo $img->id ?>" > 
<img src="index.php?tn=1&amp;id=<?php echo $img->id ?>">
</a>
</div>
<div class="imagetext">
<!-- Angaben des hochladenden Users zu diesem Bild -->
Hochgeladen am:
<?php echo $img->date ?>
<br> User:
<?php echo $img->user ?>
<br> <br>
<?php echo $img->desc ?>
</div>
<a href="index.php?page=imgdetails&amp;imgid=<?php echo $img->id ?>" > Details </a>
<div class="comment">

<?php echo $img->commentsCount; ?>
Kommentare

<?php if ($img->commentsCount > 0) { ?>
(<a id="showcomments_<?php echo $key;?>"
onclick="showHideComments('commentsarea_<?php echo $key;?>','showcomments_<?php echo $key;?>' );return false;">SHOW</a>)
<?php } ?>

<a id="showcommentinput_<?php echo $key;?>"
onclick="showHideCommentInput('commentinput_<?php echo $key;?>','showcommentinput_<?php echo $key;?>' );return false;">COMMENT</a><br>

</div> <!-- class=comment-->
<div class="commentarea" id="commentsarea_<?php echo $key;?>">


<?php foreach($img->comments as $ckey => $comment){ ?>
<div class="singlecomment">
<b>Kommentar Nr.<?php echo ' ' , $ckey+1 , ' von ', $comment['UserName'],
                    ' am ', $comment['date'];?>
</b> <br>
<p>
<?php echo $comment['Comment']; ?>
</p>

</div>
<?php } // end foreach comments ?>

            </div>

            <div class="commentinput" id="commentinput_<?php echo $key;?>">
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