<?php
echo "<br/><br/>";
?>
<div class="container" style="padding-top:20px;">
    <?php if (!empty($errors)) echo "<span style='color:red'>$errors</span>"; ?>
    <div class="row">

        <div class="col-sm-6">
            <form action="<?php echo site_url("Korisnik/nova_vest"); ?>" method="POST" enctype="multipart/form-data">

                <?php
                echo "<br/>Naslovnica:<br/>";
                echo form_input("naslovnica", set_value("naslovnica"), [
                    'accept' => "image/png, image/jpeg"
                ], "file");
                echo "<br>Naslov:<br/>";
                echo form_input("naslov", set_value("naslov"));
                echo "<br>Opis:<br/>";
                echo form_textarea("opis", set_value("opis"), 'style="resize:none"');
                ?>
        </div>
        <div class="col-sm-6">
            <?php
            echo "<br>Autor:<br/>";
            echo form_input("autor", set_value("autor"));
            echo "<br>Cena:<br/>";
            echo form_input("cena", set_value("cena"));
            echo "<br>Tagovi:<br/>";
            echo form_textarea("tags", set_value("tags"), 'style="resize:none"');
            ?>
        </div>
        <?php
        echo form_submit("dodaj", "Dodaj", [
            'class' => 'btn btn-primarly',
            'style' => 'width:100%; height:50px;
                                                            margin-top:10px'
        ]);
        echo "<br/><br/> <br/><br/> <br/><br/>";
        ?>
        <?php
        echo form_close();
        ?>
    </div>
</div>