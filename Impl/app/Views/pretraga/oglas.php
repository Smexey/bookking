<!-- Rade -->
<div class="container">
    <div class="row">
        <?php

        use App\Models\ModelStanje;
        use App\Models\ModelKorisnik;
        use App\Models\ModelTag;
        use App\Models\ModelOglasTag;
        use App\Models\ModelRola;
        use App\Models\ModelPrijava;

        if (isset($trenutni_korisnik)) {
            $rolaModel = new ModelRola();
            $rola = $rolaModel->where('IdR', $trenutni_korisnik->IdR)->first();
        }

        echo "<h1>{$oglas->Naslov}</h1>";
        ?>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <?php
            echo '<img src="data:image/jpeg;base64,'
                . base64_encode($oglas->Naslovnica) . '" height=200 width=200 style="margin-left:50">';
            ?>
        </div>
        <div class="col-sm-8">
            <?php
            echo "<h3>{$oglas->Autor}</h3>";
            $tagModel = new ModelTag();
            $oglastagModel = new ModelOglasTag();
            $stanjeModel = new ModelStanje();
            $stanje = $stanjeModel->find($oglas->IdS);
            $korisnikModel = new ModelKorisnik();
            $korisnik = $korisnikModel->find($oglas->IdK);
            $tagovi = $oglastagModel->where('IdO', $oglas->IdO)->findAll();
            echo "<ul>
                <li>Korisnik: {$korisnik->Ime} {$korisnik->Prezime}</li>";
                if (isset($trenutni_korisnik) && $rola->Opis == "Admin")
                    echo "<li>Stanje: {$stanje->Opis}</li>";
            echo 
                "<li>Opis: {$oglas->Opis}</li>
                <li>Cena: {$oglas->Cena} din.</li>";
            if (!empty($tagovi)) {
                echo "<li>Tagovi:</li>    
                    <ul type='circle'>";
                foreach ($tagovi as $tag)
                    echo "<li>{$tagModel->find($tag->IdT)->Opis}</li>";
                echo "</ul>";
            }
            if (isset($trenutni_korisnik)) {
                if (
                    $rola->Opis == "Admin" ||
                    $rola->Opis == "Moderator"
                ) { //provera mod ili admin
                    /*$db = \Config\Database::connect();
                    $query = $db->query("select count(*) as cnt from
                                            Prijava where IdO = ?", [$oglas->IdO]);
                    $row = $query->getResult('array')[0];
                    echo "<li>Broj prijava: {$row['cnt']}</li>";*/
                    $prijavaModel = new ModelPrijava();
                    $brojPrijava = $prijavaModel->brojPrijavaZaOglas($oglas->IdO);
                    echo "<li>Broj prijava: {$brojPrijava}</li>";


                }
            }
            echo "</ul>";
            ?>
        </div>
    </div>

    <form name="kupovina" method="get" action="<?= site_url("$controller/kupovina") ?>">
        <?php
        if (isset($trenutni_korisnik)) {
            if (($rola->Opis == "Korisnik" ||
                    $rola->Opis == "Verifikovani") &&
                $trenutni_korisnik->IdK != $oglas->IdK
            ) //provera korisnik
                echo '<input class="btn btn-primarly" type="submit" 
                        value=\'Kupi\' style="margin-left:100">';
        }
        ?>
    </form>
    <form name="prijava" method="get" action="<?= site_url("$controller/prijava_forma") ?>">
        <?php
        if (isset($trenutni_korisnik)) {
            if (($rola->Opis == "Korisnik" ||
                    $rola->Opis == "Verifikovani") &&
                $trenutni_korisnik->IdK != $oglas->IdK
            ) //provera korisnik
                echo '<input class="btn btn-primarly" type="submit" 
                        value=\'Prijavi\' style="margin-left:100">';
        }
        ?>
    </form>
    <form name="obisanje_oglasa" method="get" action="<?= site_url("$controller/brisanje_oglasa") ?>">
        <?php
        if (isset($trenutni_korisnik)) {
            if (
                (($rola->Opis == "Admin" || $rola->Opis == "Moderator") 
                && $oglas->IdS == $stanjeModel->where('Opis', 'Okacen')->first()->IdS) 
                || $trenutni_korisnik->IdK == $oglas->IdK
            ) //provera mod ili admin
                echo '<input class="btn btn-primarly" type="submit" 
                        value=\'Ukloni\' style="margin-left:100">';
        }
        ?>
    </form>

    <!-- Janko -->
    <?php if(isset($trenutni_korisnik) && 
    ($rola->Opis == "Admin" || $rola->Opis == "Moderator")
    && $brojPrijava > 0):?>
        <div class="row">
            <div class="offset-sm-4 col-sm-4">
                <h3>Prijave</h3>
                <div class="myPager">
                    <?php
                        $prijave = $prijavaModel->where('IdO', $oglas->IdO)->paginate(4, 'prijave');
                        $pager = $prijavaModel->pager;


                        $myPath = '/bookking/Impl/public//' . uri_string();
                        $pager->setPath($myPath, 'prijave');
                    ?>
                    <?= $pager->links('prijave'); ?>

                </div>
                <div>
                    <table class="table table-striped">
                                <tbody>
                                    <?php
                                    foreach ($prijave as $prijava) {
                                        $korisnikModel = new ModelKorisnik();
                                        $korisnik = $korisnikModel->find($prijava->IdK); 
                                        echo "<tr><td>{$korisnik->Imejl}:<br>";
                                        echo "<br>{$prijava->Opis}</td></tr>";
                                        //echo "<td style='display: table-cell;vertical-align: middle;'>{$prijava->IdK}</td>";
                                        //echo "<td style='display: table-cell;vertical-align: middle;'>{$prijava->Opis}</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                    <br><br><br><br>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
</div> <!-- end of content right -->