<html>
    <body>
        <div class="container text-center">
            
            <form action="<?php echo site_url("Korisnik/zahtev_ver_action"); ?>" method="POST" enctype="multipart/form-data">
                <br><br><br>
                <h1>Zahtev za verifikaciju</h1>

                <div class="justify-content-center">
                    <h4>Prinesite dokaz za verifikaciju</h4>
                    <input type="file" name="zahtevFajl">
                    <br><br>
                    <button type="submit" class='btn btn-primarly'>&nbsp;&nbsp;&nbsp;Pošalji&nbsp;&nbsp;&nbsp;</button>
                </div>
                <br>
            
                <br><br><br><br><br>
                <br><br><br><br><br>
                <br><br>

            </form>
        </div>
    </body>
</html>