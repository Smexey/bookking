<style>
    
    .blueColor{
        color:#16697a;
        font-weight:bold;
    }

</style>
<div class="container text-center">

        <br><br>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h1>Izmena Naloga</h1>
            </div>
        </div>

        <form action="<?php echo site_url("$controller/nalog_izmena_action"); ?>" method="POST">               
        <div class="row">
            <div class="offset-sm-4 col-sm-4">
                <table>
                    <tr>
                        <td class="blueColor">Ime:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="text-center"><input name="ime" value="<?php echo $ime; ?>"></td>
                    </tr>
                    <tr>
                        <td class="blueColor">Prezime:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="text-center"><input name="prezime" value="<?php echo $prezime; ?>"></td>
                    </tr>
                    <tr>
                        <td class="blueColor">Šifra:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="text-center"><input name="sifra" value="<?php echo $sifra; ?>"></td>
                    </tr>
                    <tr>
                        <td class="blueColor">Adresa:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="text-center"><input name="adresa" value="<?php echo $adresa; ?>"></td>
                    </tr>
                    <tr>
                        <td class="blueColor">Grad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="text-center"><input name="grad" value="<?php echo $grad; ?>"></td>
                    </tr>
                    <tr>
                        <td class="blueColor">Poštanski broj:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="text-center"><input name="postBroj" value="<?php echo $postBroj; ?>"></td>
                    </tr>
                    <tr>
                        <td class="blueColor">Država:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="text-center"><input name="drzava" value="<?php echo $drzava; ?>"></td>
                    </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td colspan='2' class="text-center">
                             <button class="btn btn-primarly">Sačuvaj izmene</button>        
                        </td>
                    </tr>
                </table>

                </form>
            </div>
        </div>
</div>