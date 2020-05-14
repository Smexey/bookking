<!-- Rade -->
<div class="container text-center">
<br><br>
    <div class="row">
        <div class="offset-sm-2 col-sm-8">
            <form name="pretragavesti" method="get"
                action="<?= site_url("$controller/pretraga") ?>" >
                Pretraga: <input type="text" name="pretraga"> 
                <input class='btn' name="Trazi" type="submit" value="Trazi"><br>
            </form>
            <?php 
                if(!empty($trazeno))
                    echo "<h3>Rezultati pretrage $trazeno:</h3>";
                else 
                    echo "<h3>Svi oglasi</h3>";
            ?> 
            <div class="myPager">
            <?php 
                $myPath = '/bookking/Impl/public//'.uri_string();
                $pager->setPath($myPath, 'oglasi');
            ?>
                <?= $pager->links('oglasi'); ?>
            </div>
            <?php if(count($oglasi)!=0) : ?>
                <table class="table table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>Naslovnica</th>
                            <th>Naslov</th> 
                            <th>Autor</th> 
                            <?php if($controller!=='Gost'): ?>
                                <th>Profil korisnika</th>
                            <?php endif;?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($oglasi as $oglas) {
                        // $korisnikModel = new \App\Models\ModelKorisnik();
                        // $podneo = $korisnikModel->find($zahtev->Podneo);
                        
                        echo "<tr><td>".anchor("$controller/oglas/{$oglas->IdO}", '<img src="data:image/jpeg;base64,'
                        .base64_encode($oglas->Naslovnica).'" height=100 width=100>')."</td>";  
                    
                        echo "<td style='display: table-cell;vertical-align: middle;'>{$oglas->Naslov}</td>";   
                        echo "<td style='display: table-cell;vertical-align: middle;'>{$oglas->Autor}</td>"; 
                        if($controller!=='Gost') {
                            $imgsrcProfil = base_url('/assets/images/zahtev_profil.png');
                            $imgProfil = array(
                                'src' => $imgsrcProfil,
                                'alt' => 'Profil',
                                'style' => 'height: 50px'
                            );        
                            echo "<td style='display: table-cell;vertical-align: middle;'>".
                            anchor("$controller/nalog_pregled/{$oglas->IdK}", img($imgProfil))."</td>";  
                        }
                        echo "</tr>";
                    } 
                    ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="row">
                    <div class="offset-sm-2 col-sm-8">
                        <div class="alert text-center alert-info">
                            Nema oglasa za traženu pretragu!
                        </div>
                    </div>
                </div>  
            <?php endif;?> 
                
        </div>

    </div>   





    
<br>
<br>
<br>
<br>  
</div>


