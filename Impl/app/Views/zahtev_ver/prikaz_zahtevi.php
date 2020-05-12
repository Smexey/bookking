<div class="container text-center">
            
            
<br><br>

<div class="row">
    <div class="offset-sm-2 col-sm-8">
          
        <h3>Podneti zahtevi za verifikaciju</h3>
        <div class="myPager">
        <?php 
            $myPath = '/bookking/Impl/public//'.uri_string();
            $pager->setPath($myPath, 'zahtevi');
        ?>
            <?= $pager->links('zahtevi'); ?>
        </div>

        <table class="table table-striped text-center">
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
                    echo "<tr><td>{$podneo->Imejl}</td>";
                    echo "<td>".anchor("$controller/prikaz_profil/{$podneo->IdK}", "Profil")."</td>";  
                    echo "<td>".anchor("$controller/prikaz_zahtev/{$zahtev->IdZ}", "Zahtev")."</td></tr>";  
                }
                ?>
            </tbody>
        </table>
    </div>

</div>






<br>
<br>
<br>
<br>
            
</div>


