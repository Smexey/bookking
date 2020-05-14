<br /><br /><br />
<div class="container" style="padding-top:20px;">
    <form action="<?php echo site_url("$controller/kupovina_dalje"); ?>" method="POST">     
        <table width = "100%" border = 0 cellpadding="10">
            <?php
                use App\Models\ModelKorisnik;
                use App\Models\ModelRola;
                $korisnikModel = new ModelKorisnik();
                $korisnik = $korisnikModel->find($oglas->IdK);
                $rolaModel = new ModelRola();
                $rola = $rolaModel->where('IdR', $korisnik->IdR)->first(); 
                if( $rola->Opis == "Verifikovani"){
                    echo '<tr><td>
                    <input type="radio" name="a" id="" value="sajt" checked> Preko sajta
                </td></tr>';
                }else{
                    echo '<tr><td>
                    <input type="radio" name="a" id="" value="middleman" checked> Preko middleman-a
                </td></tr>';
                }
            ?> 
            <tr><td>
                <input type="radio" name="a" id="" value="poruka"> Preko sistema poruka
            </td></tr>
            <tr>
                <td>
                    <button type="submit" class="btn btn-primary">Dalje</button>
                </td>
            </tr>
        </table> <hr /> <br />
    </form>
</div>