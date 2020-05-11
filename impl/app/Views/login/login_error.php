<html>
    <body>
        <div class="container text-center">
            
            <form action="<?php echo site_url("Gost/login_action"); ?>" method="POST">
                <br><br><br>
                <h1>Login</h1>

                <br>

                <div class="row">
                    <div class="offset-sm-4 col-sm-4">
                        <div class="alert text-center alert-danger">
                            Login nije uspeo
                        </div>
                    </div>
                </div>

                <br>

                <div class="offset-sm-5">        
                    <table class="text-center">
                        <tr class="text-center">
                            <td class="text-center"><input type="text" placeholder="Imejl" name="imejl"></td>
                        </tr>
                        <tr>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td><input type="password" placeholder="Šifra" name="sifra"></td>
                        </tr>
                        <tr>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td><button type="submit" class='btn btn-primarly'>&nbsp;&nbsp;&nbsp;Prijavi se&nbsp;&nbsp;&nbsp;</button></td>
                        </tr> 
                    </table>
                </div>
                <br>
                
                <a href="<?php echo site_url("Gost/registracija"); ?>">Nemate nalog?</a>
                <a href="<?php echo site_url("Gost/oporavak"); ?>">Zaboravili ste šifru?</a>
            
                <br><br><br><br><br>
                <br><br><br>

            </form>
        </div>
    </body>
</html>