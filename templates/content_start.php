<!-- autor: maike schroeder -->
		
<div class="gallery">	
<?php
// print_r($displayData);
// es wird geprueft ob Bilder vorhanden sind, diese werden wenn vorhanden mit hilfe einer Schleife ausgegeben
	if (isset($displayData['images'])) foreach ($displayData['images'] as $key => $img){?>
<div class="startpage_image">
	<a href="<?php echo $img->imgLink ?>">
		<img src="<?php echo $img->thumbnail ?>">
	</a>			
</div>

<?php } // ende foreach?>
