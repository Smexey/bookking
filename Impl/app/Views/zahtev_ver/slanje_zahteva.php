<html>
    <body>
        <div class="container text-center">
            
            <form action="<?php echo site_url("Korisnik/zahtev_ver_action"); ?>" method="POST" enctype="multipart/form-data">
                <br><br><br>
                <h1>Zahtev za verifikaciju</h1>

                <?php if($zahtevNeuspesan!=='') : ?>
                    <div class="row">
                        <div class="offset-sm-4 col-sm-4">
                            <div class="alert text-center alert-danger">
                                <?php echo $zahtevNeuspesan; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="justify-content-center">
                    <h4>Prinesite dokaz za verifikaciju</h4>
                    <input type="file" name="zahtevFajl" accept="application/pdf">
                    <br><br>
                    <button type="submit" class='btn btn-primarly'>&nbsp;&nbsp;&nbsp;Pošalji&nbsp;&nbsp;&nbsp;</button>
                </div>
                <br>
            

            </form>
        </div>
    </body>
</html>