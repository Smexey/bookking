<br><br>

<?php 
echo "<h2>".anchor_popup("$controller/prikaz_zahtev_fajl/{$zahtev->IdZ}", 'File').
"</h2><h3>{$zahtev->Podneo}</h3>{$zahtev->Stanje}";

?>