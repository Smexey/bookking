<html>
    <body>
        <div class="container text-center">
            
            <form>
                <br><br><br>

                <div class="justify-content-center">
                    <?php 
                        $korisnikModel = new \App\Models\ModelKorisnik();
                        $podneo = $korisnikModel->find($zahtev->Podneo);
                        echo "<h3>Podnosilac zahteva za verifikaciju:<br>{$podneo->Imejl}</h3><br>";
                    ?>
                    <div class="row">
                        <div class="offset-sm-4 col-sm-4">
                            <div class="alert text-center alert-success">
                                <?php echo "Zahtev je uspešno ".$stanje."!"; ?>
                            </div>
                        </div>
                    </div>

               </div>

            </form>
        </div>
    </body>
</html>