<!-- autor: maike schroeder -->
		
<?php if(! $displayData['error']){?> 	
<?php
// es wird geprueft ob Bilder vorhanden sind, diese werden wenn vorhanden mit hilfe einer Schleife ausgegeben
if(count($displayData['images']) > 0) foreach ($displayData['images'] as $key => $img){?>
<div class="startpage_image">
	<a href="<?php echo $img->imgLink ?>">
		<img src="<?php echo $img->thumbnail ?>">
	</a>			
</div>

<?php } // ende foreach?>
<?php } else{ ?> 
<h2>Kein Bild vorhanden</h2>
<?php }?> 