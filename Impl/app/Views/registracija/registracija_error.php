<div class="container">

        <br><br>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h1>Registruj se</h1>
            </div>
        </div>

        <div class="row">
            <div class="offset-sm-4 col-sm-4 text-center">
                <div class="alert alert-danger text-center"><?php echo $error_msg; ?> Proverite podatke koje ste uneli.</div>
            </div>
        </div>
    

        <form action="<?php echo site_url("$controller/registracija_action"); ?>" method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ime" value="<?php if(isset($_POST['ime'])) echo $_POST['ime'] ?>" placeholder="Ime">
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="prezime" value="<?php if(isset($_POST['prezime'])) echo $_POST['prezime'] ?>" placeholder="Prezime">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="imejl" value="<?php if(isset($_POST['imejl'])) echo $_POST['imejl'] ?>" placeholder="Imejl">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="sifra" value="<?php if(isset($_POST['sifra'])) echo $_POST['sifra'] ?>" placeholder="Šifra">
                </div>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="sifraPonovo" value="<?php if(isset($_POST['sifraPonovo'])) echo $_POST['sifraPonovo'] ?>" placeholder="Potvrdi šifru">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="adresa" value="<?php if(isset($_POST['adresa'])) echo $_POST['adresa'] ?>" placeholder="Adresa">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="grad" value="<?php if(isset($_POST['grad'])) echo $_POST['grad'] ?>" placeholder="Grad">
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="drzava" value="<?php if(isset($_POST['drzava'])) echo $_POST['drzava'] ?>" placeholder="Država">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="postBroj" value="<?php if(isset($_POST['postBroj'])) echo $_POST['postBroj'] ?>" placeholder="Poštanski broj">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-center">
                    <button  class="btn myDownloadButton">&nbsp;&nbsp;&nbsp;Registruj se&nbsp;&nbsp;&nbsp;</button>
                </div>
            </div>

        </form>

        <br>
        <br>
        <br>
        <br>
</div>