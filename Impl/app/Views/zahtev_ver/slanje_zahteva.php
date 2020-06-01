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
                    <h3>Prinesite dokaz za verifikaciju</h3>
                    <div class="row">
                        <div class="offset-sm-4 col-sm-4">
                        <input type="file" name="zahtevFajl" accept="application/pdf" class="filestyle text-center" data-placeholder="Nema fajla" data-btnClass="btn btn-primarly maringtop20" data-text="Odaberite fajl" data-buttonBefore="true">
                        </div>
                    </div>
                    
                    <br>
                    <button type="submit" class='btn btn-primarly'>&nbsp;&nbsp;&nbsp;Pošalji&nbsp;&nbsp;&nbsp;</button>
                </div>
                <br>
            

            </form>
        </div>
    </body>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js"> </script>
    <style>
        .maringtop20{
            margin-top: 20px;

        }
        .input-group-btn:focus{
            outline: 0;
        }
    </style>
</html>