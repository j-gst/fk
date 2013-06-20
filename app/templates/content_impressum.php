<!-- @autor: Thies Schillhorn -->
<!-- Anzeige des aus der Konfigurationsdatei gelesenen Impressums -->
<div style="color:blue;" >
<h2>Impressum</h2>
<?php if (isset($displayData['impressum'])) foreach ($displayData['impressum'] as $key => $imp){ ?>
<h4><?php echo $imp ?></h4>
<?php } ?>
</div>