<!-- Rade -->
<div class="container">
    <div class="row">
    <?php 	
        use App\Models\ModelStanje;
        use App\Models\ModelKorisnik; 
        use App\Models\ModelTag;
        use App\Models\ModelOglasTag; 
        use App\Models\ModelRola;

        echo "<h1>{$oglas->Naslov}</h1>";
    ?>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <?php 	 
                echo '<img src="data:image/jpeg;base64,'
                .base64_encode($oglas->Naslovnica).'" height=200 width=200 style="margin-left:50">';
                ?>
                </div>
                <div class="col-sm-8">
                <?php 	 
                echo "<h2><span>{$oglas->Autor}</span></h2>";
                $tagModel = new ModelTag();
                $oglastagModel = new ModelOglasTag();
                $stanjeModel = new ModelStanje();
                $stanje = $stanjeModel->find($oglas->IdS);
                $korisnikModel = new ModelKorisnik();
                $korisnik = $korisnikModel->find($oglas->IdK); 
                $tagovi = $oglastagModel->where('IdO',$oglas->IdO)->findAll(); 
                echo "<ul>
                <li>Korisnik: {$korisnik->Ime} {$korisnik->Prezime}</li>
                <li>Stanje: {$stanje->Opis}</li>
                <li>Opis: {$oglas->Opis}</li>
                <li>Cena: {$oglas->Cena} din.</li>";
                if(!empty($tagovi)){
                    echo "<li>Tagovi:</li>    
                    <ul type='circle'>"; 
                        foreach ($tagovi as $tag)   
                            echo "<li>{$tagModel->find($tag->IdT)->Opis}</li>"; 
                    echo "</ul>";
                } 
                if(isset($trenutni_korisnik)){
                    $rolaModel = new ModelRola();
                    $rola = $rolaModel->where('IdR', $trenutni_korisnik->IdR)->first(); 
                    if( $rola->Opis == "Admin" ||
                        $rola->Opis == "Moderator"){ //provera mod ili admin
                        $db = \Config\Database::connect();
                        $query = $db->query("select count(*) as cnt from
                                            Prijava where IdO = ?",[$oglas->IdO]);
                        $row = $query->getResult('array')[0];
                        echo "<li>Broj prijava: {$row['cnt']}</li>";  
                    }
                }
                echo "</ul>";   
            ?> 
        </div>
    </div>
       
    <form name="kupovina" method="get"
        action="<?= site_url("$controller/kupovina") ?>" > 
        <?php  
        if(isset($trenutni_korisnik)){
            $rolaModel = new ModelRola();
            $rola = $rolaModel->where('IdR', $trenutni_korisnik->IdR)->first(); 
            if( ($rola->Opis == "Korisnik" ||
                $rola->Opis == "Verifikovani") &&
                $trenutni_korisnik->IdK != $oglas->IdK) //provera korisnik
                echo '<input class="btn" name="Kupi" type="submit" 
                        value="Kupi" style="margin-left:100">'; 
        } 
        ?>
    </form>
    <form name="obisanje_oglasa" method="get"
        action="<?= site_url("$controller/obisanje_oglasa/$oglas->IdO") ?>" >  
        <?php
        if(isset($trenutni_korisnik)){
            $rolaModel = new ModelRola();
            $rola = $rolaModel->where('IdR', $trenutni_korisnik->IdR)->first(); 
            if( $rola->Opis == "Admin" ||
                $rola->Opis == "Moderator" ||
                $trenutni_korisnik->IdK == $oglas->IdK ) //provera mod ili admin
                echo '<input class="btn" name="Obrisi" type="submit" 
                        value="Obrisi" style="margin-left:100">';  
        }
        ?>
    </form>
</div> <!-- end of content right -->