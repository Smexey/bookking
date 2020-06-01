<!-- Rade -->
<div class="container text-center">
    <br><br>
    <div class="row">
        <div class="offset-sm-2 col-sm-8">
            <?php if ($mojiOglasi == true):?>
                <form name="pretragavesti" method="get" action="<?= site_url("$controller/moji_oglasi") ?>">
                    Pretraga: <input type="text" name="pretraga"> 
                    <input class='btn btn-primarly' value='Traži' type="submit" style="margin-top: -5px"><br>
                </form>
            <?php else: ?>
                <form name="pretragavesti" method="get" action="<?= site_url("$controller/pretraga") ?>">
                    Pretraga: <input type="text" name="pretraga">
                    <input class='btn btn-primarly' value='Traži' type="submit" style="margin-top: -5px;"><br>          
                <?php if (count($stanja) > 0): ?>
                    <div style="margin-top: 10px"></div>
                    Stanje oglasa:
                    <select name='stanje'>
                        <option></option>
                        <?php
                            foreach ($stanja as $stanje) {
                                echo "<option value={$stanje->IdS}>".$stanje->Opis."</option>";
                            }
                        ?>
                    </select>
                <?php endif; ?>
                </form>
            <?php endif; ?>
            <?php
            if (!empty($trazeno))
                echo "<h3>Rezultati pretrage $trazeno:</h3>";
            else
                echo "<h3>Svi oglasi</h3>";
            ?>
            <div class="myPager">
                <?php
                $myPath = '/bookking/Impl/public//' . uri_string();
                $pager->setPath($myPath, 'oglasi');
                ?>
                <?= $pager->links('oglasi'); ?>
            </div>
            <?php if (count($oglasi) != 0) : ?>
                <table class="table table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>Naslovnica</th>
                            <th>Naslov</th>
                            <th>Autor</th>
                            <?php if ($controller !== 'Gost') : ?>
                                <th>Nalog prodavca</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($oglasi as $oglas) {
                            // $korisnikModel = new \App\Models\ModelKorisnik();
                            // $podneo = $korisnikModel->find($zahtev->Podneo);
                            $korisnikModel = new App\Models\ModelKorisnik();
                            $korisnik = $korisnikModel->find($oglas->IdK);
                            echo "<tr><td>" . anchor("$controller/oglas/{$oglas->IdO}", '<img src="data:image/jpeg;base64,'
                                . base64_encode($oglas->Naslovnica) . '" height=100 width=80>') . "</td>";

                            echo "<td style='display: table-cell;vertical-align: middle;'>{$oglas->Naslov}</td>";
                            echo "<td style='display: table-cell;vertical-align: middle;'>{$oglas->Autor}</td>";
                            if ($controller !== 'Gost') {
                                $imgsrcProfil = base_url('/assets/images/nalog'.$korisnik->IdA.'.png');
                                $imgProfil = array(
                                    'src' => $imgsrcProfil,
                                    'alt' => 'Profil',
                                    'style' => 'height: 50px'
                                );
                                echo "<td style='display: table-cell;vertical-align: middle;'>" .
                                    anchor("$controller/nalog_pregled/{$oglas->IdK}", img($imgProfil)) . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="row">
                    <div class="offset-sm-2 col-sm-8">
                        <div class="alert text-center alert-info">
                            Nema oglasa za traženu pretragu!
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