<br /><br /><br />
<div class="container" style="padding-top:20px;">
    <?php if (!empty($errors)) echo "<span style='color:red'>$errors</span>"; ?>
    <form name="dodavanje_oglasa" method="get" action="<?= site_url("$controller/nova_vest") ?>" class="form-inline mr-auto form-group" style="border: solid 2px green">
        <div class="col-sm-6">
            <label for="sklikaOglasa">Naslovnica</label>
            <input type="file" class="form-control-file" id="sklikaOglasa" class="d-none" name="naslovnica" value="<?= set_value('naslovnica') ?>">
            <span></span>
            <input class="form-control" type="text" placeholder="Naslov" aria-label="Naslov" style="width: 60%;" name="naslov" value="<?= set_value('naslov') ?>">
            <input class="form-control" type="text" placeholder="Opis" aria-label="Opis" style="width: 60%;height: 150" name="opis" value="<?= set_value('opis') ?>">
        </div>
        <div class="col-sm-6">
            <input class="form-control" type="text" placeholder="Autor" aria-label="Autor" style="width: 60%;" name="autor" value="<?= set_value('autor') ?>">
            <input class="form-control" type="text" placeholder="Cena" aria-label="Cena" style="width: 60%;" name="cena" value="<?= set_value('cena') ?>">
            <label for="textArea">Tagovi</label>
            <textarea style="resize:none" id="textArea" rows="4" cols="50" name="tags" value="<?= set_value('tags') ?>"></textarea>
        </div>
        <input class="btn btn-primarly" name="Potvrdi" type="submit" value="Potvrdi" style="width: 100%">
    </form>
</div>