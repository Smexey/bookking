<br><br> 
<div class="container" style="padding-top:20px;">
    <?php if(!empty($message)) echo "<script type='text/javascript'>alert('$message');</script>"; ?>
    <?php if(!empty($errors)) echo "<span style='color:red'>$errors</span>"; ?>
    <form action="<?php echo site_url("$controller/provera"); ?>" method="POST">
        <table width = "100%" border = 0>
            <tr><td></td>
                <font size="6">Cena: 
                    <?php
                    echo $oglas->Cena
                    ?> dinara</font>
                <br /><br />
                <p>Odaberite način plaćanja:</p>
                <input type="radio" id="Kartica" name="placanje" value="Kartica">
                <label for="Kartica">&nbsp;Kartica</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" id="Pouzecem" name="placanje" value="Pouzecem" checked>
                <label for="Pouzecem">&nbsp;Pouzećem</label><br>
                <input class="form-control" type="text" placeholder="Ime i prezime" 
                aria-label="Ime" style="width: 40%;" name='cardholder' value="<?php echo set_value('cardholder')?>">
                <input class="form-control" type="text" placeholder="Broj kartice" 
                aria-label="Broj kartice" style="width: 40%;" name='brK' value="<?php echo set_value('brK')?>">
                <input class="form-control" type="text" placeholder="Validnost" 
                aria-label="Validnost" style="width: 20%;" name='validThu' value="<?php echo set_value('validThu')?>">
                <input class="form-control" type="text" placeholder="CVV/CVC" 
                aria-label="CVV" style="width: 20%;" name='cvv' value="<?php echo set_value('cvv')?>">
                <br />
            <div class = "row">
                <div class="col" align = "center">
                    <button type="submit" class="btn btn-primary" style="width: 20%;">Potvrdi</button>
                </div> 
            </div>
            </td></tr>
        </table> <hr /> <br />
    </form>
</div>