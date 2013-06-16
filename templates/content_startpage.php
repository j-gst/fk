<!-- autor: Maike Schröder -->
<div class="startpage_image">

<?php if(! $displayData['error']){?>
<?php
// es wird geprueft ob Bilder vorhanden sind, diese werden wenn vorhanden mit hilfe einer Schleife ausgegeben
if (isset($displayData['images'])) foreach ($displayData['images'] as $key => $img){?>
    <a href="<?php echo $img->imgLink ?>">
        <img src="<?php echo $img->thumbnail ?>">
    </a>

<?php } // ende foreach
 } else{ ?>
<h2>Kein Bild vorhanden</h2>
<?php }?>
</div>
