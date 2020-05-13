<div class="container text-center">
            
            
<br><br>

<div class="row"  style="height: 100%">
    <div class="offset-sm-2 col-sm-8">
        <br>
        <h3>Podneti zahtevi za verifikaciju</h3>
        <div class="myPager">
        <?php 
            $myPath = '/bookking/Impl/public//'.uri_string();
            $pager->setPath($myPath, 'zahtevi');
        ?>
            <?= $pager->links('zahtevi'); ?>
        </div>

        <?php if(count($zahtevi) > 0) : ?>
        <table class="table table-striped text-center" style="vertical-align: middle;">
            <thead class="thead-dark">
                <tr>
                    <th>Podnosilac zahteva</th>
                    <th>Pregled profila</th> 
                    <th>Detaljniji prikaz zahteva</th> 
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($zahtevi as $zahtev) {
                    $korisnikModel = new \App\Models\ModelKorisnik();
                    $podneo = $korisnikModel->find($zahtev->Podneo);

                    helper('html');

                    $imgsrcProfil = base_url('/assets/images/zahtev_profil.png');
                    $imgProfil = array(
                        'src' => $imgsrcProfil,
                        'alt' => 'Profil',
                        'style' => 'height: 50px'
                    );

                    $imgsrcDetalji = base_url('/assets/images/zahtev_detalji.png');
                    $imgDetalji = array(
                        'src' => $imgsrcDetalji,
                        'alt' => 'Detalji',
                        'style' => 'height: 50px'
                    );

                    echo "<tr><td style='display: table-cell;vertical-align: middle;'>{$podneo->Imejl}</td>";
                    echo "<td>".anchor("$controller/prikaz_profil/{$podneo->IdK}", img($imgProfil))."</td>";  
                    echo "<td>".anchor("$controller/prikaz_zahtev/{$zahtev->IdZ}", img($imgDetalji))."</td></tr>";  
                }
                ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="row">
                <div class="offset-sm-2 col-sm-8">
                    <div class="alert text-center alert-info">
                        Nema podnetih zahteva za verifikaciju!
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>






<br>
<br>
<br>
<br>
            
</div>

