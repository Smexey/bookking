<br><br> 
<div class="container" style="padding-top:20px;">
    <?php if(!empty($message)) echo "<h2>$message</h2>"; ?>
    <form action="<?php echo site_url("$controller/uspesna_kupovina"); ?>" method="POST">
       <button type="submit" class="btn btn-primary" style="width: 100%;">Nazad</button>
    </form>
</div>