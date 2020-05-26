<br><br>
<div class="container">

    <div class="row">
        <div class="col-sm-12 text-center">
            <h1>Prijava oglasa</h1>
        </div>
    </div>
    <?php if(!empty($errors)): ?>
        <div class="row">
            <div class="offset-sm-3 col-sm-6 text-center">
                <div class="alert alert-danger">
                <?php foreach ($errors as $error) : ?>
                    <?= esc($error) ?><br>
                <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
            <div class="offset-sm-3 col-sm-6 text-center">
    <form name="prijava2" method="post"
        action="<?= site_url("$controller/prijava") ?>" > 
        <textarea style="resize:none" name="opisPrijave" id="opis" cols="40" rows="8" placeholder="Opis"></textarea><br>
        <button class="btn" name="Prijavi" type="submit" 
                style="margin-top:20">Prijavi</button>
    </form>
    </div>
        </div>
</div>