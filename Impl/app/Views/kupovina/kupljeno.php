<br><br> 
<br>
<br>
<br>
<div class="container text-center" style="padding-top:20px;">
    <?php if(!empty($message)) echo "<h2>$message</h2>"; ?>
    <form action="<?php echo site_url("$controller/uspesna_kupovina"); ?>" method="POST">
       <button type="submit" class="btn btn-primarly">&nbsp;&nbsp;Nazad&nbsp;&nbsp;</button>
    </form>
</div>