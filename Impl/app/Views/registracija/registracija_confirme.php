<html>
    <body>
        <div class="container text-center">
            
            <form action="<?php echo site_url("Gost/registracija_confirme_action");?>" method="POST">
                <br><br><br>
                <h1>Potvrdite nalog</h1>
                <br><br>
                <p>Kod koji treba da potvrdite je poslat na Vaš imejl.</p>

                <input type='hidden' name="confirmeCode" value="<?php echo $_POST['confirmeCode']; ?>">
                <input type='hidden' name='ime' value="<?php echo $_POST['ime']; ?>">
                <input type='hidden' name='sifra' value="<?php echo $_POST['sifra']; ?>">
                <input type='hidden' name='prezime' value="<?php echo $_POST['prezime']; ?>">
                <input type='hidden' name='adresa' value="<?php echo $_POST['adresa']; ?>">
                <input type='hidden' name='grad' value="<?php echo $_POST['grad']; ?>">
                <input type='hidden' name='drzava' value="<?php echo $_POST['drzava']; ?>">
                <input type='hidden' name='postBroj' value="<?php echo $_POST['postBroj']; ?>">
                <input type='hidden' name='imejl' value="<?php echo $_POST['imejl']; ?>">
                

                <div class="offset-sm-5">        
                    <table class="text-center">
                        <tr class="text-center">
                            <td class="text-center"><input type="text" placeholder="Kod potvrde" name="kod"></td>
                        </tr>
                        <tr>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td><button type="submit" class='btn btn-primarly'>&nbsp;&nbsp;&nbsp;Pošalji&nbsp;&nbsp;&nbsp;</button></td>
                        </tr> 
                    </table>
                </div>
                <br>

                <br><br><br><br><br>
                <br><br><br><br><br>
                <br><br>

            </form>
        </div>
    </body>
</html>