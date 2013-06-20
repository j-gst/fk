<?php 
/**
 * Template fuer die Darstellung einer Gallerie zu einem ZIP-Archiv
 * @author Gerrit Storm
 */
?>

<?php if(! $displayData['error']){
// Anzeige des Downloadbuttons fuer das ZIP-Archiv
?> 

<div class="gallery_header">
    <h3 style="display: inline; " >Archiv:  <?php echo $displayData['archive']->titel ;?> </h3>
    <form action="index.php?page=gallery" method="post" style="display: inline;">
    	<input type="submit" name="download" value="download als Zip">
    	<input type="hidden" name="id" value="<?php echo $displayData['id'] ;?>">
    </form><br><br> 

Datum: <?php echo $displayData['archive']->date  ;?><br> 
Benutzer: <?php echo $displayData['archive']->user  ;?><br> <br> 
	
<div class="gallery_desc">			
		<?php echo $displayData['archive']->desc  ;?>
</div>
		
</div> 
<div class="gallery">	

<?php 
// Anzeige der einzelnen Bilder der Gallerie
if(count($displayData['images']) > 0) foreach ($displayData['images'] as $key => $img){
?>

<div class="gallery_image">
     
	   
	   <a href="index.php?image=<?php echo $img->id ?>" > 
		<img src="index.php?pahe=main&amp;tn=1&amp;id=<?php echo $img->id ?>">
		</a>
		
	    <a href="index.php?page=imgdetails&amp;imgid=<?php echo $img->id ?>">
        <span class="gallery_image_text">Details</span>
	   </a>
				
</div>

<?php } // ende foreach?>

</div>

<?php } /*ende if*/ else{
// Anzeige Fehler: uebergebene ID gehoert zu keinem Bild oder Bild gehoert zu
// keinem Archiv
?> 
<div style="height:220px;color:red;">
<h2>Dieses Archiv existiert nicht!</h2>
</div>
<?php }// else?> 