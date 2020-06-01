<?php
echo "<br/><br/>";
?>
<div class="container" style="padding-top:20px;">
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
    <?php endif;?>

    <div class="row">

        <div class="offset-sm-1 col-sm-5">
            <div class="row">
                <div class="col-sm-8">
                    <form action="<?php echo site_url("{$controller}/nova_vest"); ?>" method="POST" enctype="multipart/form-data">
                    <?php
                    echo "<br/>Naslovnica:<br/>";
                    ?>
                    
                        
                        <input type="file" name="naslovnica" accept="image/png, image/jpeg" class="filestyle" data-placeholder="Nema fajla" data-btnClass="btn btn-primarly maringtop20" data-text="Odaberite fajl" data-buttonBefore="true"> 
                
                    
                    <?php
                    echo "Naslov:<br/>";
                    echo form_input("naslov", set_value("naslov"));
                    echo "<br>Opis:<br/>";
                    echo form_textarea("opis", set_value("opis"), 'style="resize:none; width:100%;"');
                    ?>
                </div>
            </div>
        </div>
        <div class="offset-sm-1 col-sm-5 mt-2">
            <div class="row">
                    <div class="col-sm-8">
            <?php
            echo "<br>Autor:<br/>";
            echo form_input("autor", set_value("autor"));
            echo "<br>Cena:<br/>";
            echo form_input("cena", set_value("cena"));
            echo "<br>Tagovi:<br/>";
            echo form_textarea("tags", set_value("tags"), 'style="resize:none; width:100%;"');
            ?>
        </div>


        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <?php
            echo form_submit("dodaj", "Dodaj", [
                'class' => 'btn btn-primarly',
                'style' => 'width:100px; height:40px; margin-top:10px'
            ]);
            echo "<br/><br/> <br/><br/> <br/><br/>";
            ?>
        </div>
    </div>
    <?php
        echo form_close();
        ?>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js"> </script>
<style>
    .maringtop20{
        margin-top: 2px;
        margin-bottom: 2px;
    }
    .form-control{
        margin-top: 2px;
        margin-bottom: 2px;
    }
    .input-group-btn:focus{
        outline: 0;
    }
</style>