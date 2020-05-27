

<body>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/adminp.css'); ?>">

    <div class="container">
        <br><br>
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">Pregled</li>
            </ol>
        </div><!--/.row-->

        
        
        <div class="row">
            <div class="col-sm-12 text-center">
                <h3>Dnevni izveštaj</h3>
            </div>
        </div><!--/.row-->
        
        <div class="panel panel-container">
            <div class="row">
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-teal panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-shopping-cart color-blue"></em>
                            <div class="large" id="kupovineDnevno"><?=$pregledi[0]->BrKupovina?></div>
                            <div class="text-muted">Kupovine</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-blue panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-book color-orange"></em>
                            <div class="large" id="oglasiDnevno"><?=$pregledi[0]->BrOglasa?></div>
                            <div class="text-muted">Oglasi</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-orange panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-users color-teal"></em>
                            <div class="large" id="korisniciDnevno"><?=$pregledi[0]->BrKorisnika?></div>
                            <div class="text-muted">Novi korisnici</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-red panel-widget ">
                        <div class="row no-padding"><em class="fa fa-xl fa-search color-red"></em>
                            <div class="large" id="logovanjaDnevno"><?=$pregledi[0]->BrLogovanja?></div>
                            <div class="text-muted">Logovanja</div>
                        </div>
                    </div>
                </div>
            </div><!--/.row-->
        </div>

        <br>
        <div class="row">
            <div class="col-sm-12 text-center">
                <h3>Sedmični pregled</h3>
            </div>
        </div><!--/.row-->
        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            
                            <ul class="pull-right panel-settings panel-button-tab-right">
							<li class="dropdown"><a class="pull-right dropdown-toggle" data-toggle="dropdown" href="#">
								<em class="fa fa-cogs"></em>
							</a>
								<ul class="dropdown-menu dropdown-menu-right">
									<li>
										<ul class="dropdown-settings">
                                            <li><a href="<?= site_url("$controller/admin_pregled") ?>"> 
												<em class="fa fa-cog"></em> Sve
											</a></li>
											<li class="divider"></li>
											<li><a href="<?= site_url("$controller/admin_pregled/0") ?>"> 
												<em class="fa fa-cog"></em> Kupovine
											</a></li>
											<li class="divider"></li>
											<li><a href="<?= site_url("$controller/admin_pregled/1") ?>"> 
												<em class="fa fa-cog"></em> Oglasi
											</a></li>
											<li class="divider"></li>
											<li><a href="<?= site_url("$controller/admin_pregled/2") ?>"> 
												<em class="fa fa-cog"></em> Korisnici
											</a></li>
                                            <li class="divider"></li>
											<li><a href="<?= site_url("$controller/admin_pregled/3") ?>"> 
												<em class="fa fa-cog"></em> Logovanja
											</a></li>
										</ul>
									</li>
								</ul>
							</li>
                        </ul>
                        </div>
                        <div class="panel-body">
                            <div class="canvas-wrapper">
                                <canvas class="main-chart" id="line-chart" height="200" width="600"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.row-->
            

        
        <br><br>
        
        <div class="row">
            <div class="col-sm-12 text-center">
                <h3>Jučerašnji izveštaj</h3>
            </div>
        </div><!--/.row-->

        <div class="panel panel-container">
            <div class="row ">
                <div class="col-sm-6 no-padding">
                    <div class="panel panel-teal panel-widget border-right">
                        <div class="row no-padding justify-content-center">
                            <?php 
                                $imgkupacsrc = base_url('/assets/images/nalog2.png');
                                $imgkupac = array(
                                    'src' => $imgkupacsrc,
                                    'alt' => 'Nalog',
                                    'style' => 'height: 50px'
                                );
                                if ($najKupacIdK != null){
                                    echo "<span id ='najKupacLink'>".anchor("$controller", img($imgkupac))."</span>";
                                }
                            ?>
                            <div class="text-muted"><br>&nbsp;Kupac dana</div>
                        </div>
                        <div class="color-gray" style="font-size: 26px; color: #373b45;" id="najKupacImejl"></div>
                    </div>
                </div>
                <div class="col-sm-6 no-padding">
                    <div class="panel panel-teal panel-widget">
                        <div class="row no-padding justify-content-center">
                            <?php 
                                $imgprodavacsrc = base_url('/assets/images/nalog3.png');
                                $imgprodavac = array(
                                    'src' => $imgprodavacsrc,
                                    'alt' => 'Nalog',
                                    'style' => 'height: 50px'
                                );
                                if ($najProdavacIdK != null){
                                    echo "<span id ='najProdavacLink'>".anchor("$controller", img($imgprodavac))."</span>";
                                }
                                
                            ?>
                            <div class="text-muted"><br>&nbsp;Prodavac dana</div>
                        </div>
                        <div class="color-gray" style="font-size: 26px; color: #373b45;" id="najProdavacImejl"></div>
                    </div>
                </div>
               
            </div><!--/.row-->
        </div>

        <br>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3>Zbirni izveštaj</h3>
            </div>
        </div><!--/.row-->
        
        <div class="panel panel-container">
            <div class="row">
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-teal panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-shopping-cart color-blue"></em>
                            <div class="large" id="kupovineZbirno"><?=$generalniPregled->BrKupovina?></div>
                            <div class="text-muted">Kupovine</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-blue panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-book color-orange"></em>
                            <div class="large" id="oglasiZbirno"><?=$generalniPregled->BrOglasa?></div>
                            <div class="text-muted">Oglasi</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-orange panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-users color-teal"></em>
                            <div class="large" id="korisniciZbirno"><?=$generalniPregled->BrKorisnika?></div>
                            <div class="text-muted">Svi korisnici</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-red panel-widget ">
                        <div class="row no-padding"><em class="fa fa-xl fa-search color-red"></em>
                            <div class="large" id="logovanjaZbirno"><?=$generalniPregled->BrLogovanja?></div>
                            <div class="text-muted">Logovanja</div>
                        </div>
                    </div>
                </div>
            </div><!--/.row-->
        </div>
        <br><br>
    </div>


    <?php 
        $N = 7;
        $datumi = []; $kupovine = []; $oglasi = []; $korisnici = []; $logovanja = [];
        foreach ($pregledi as $pregled) {
            $datum = (date('N', strtotime($pregled->Datum))) % $N;
            array_unshift($datumi, $datum);
            array_unshift($kupovine, $pregled->BrKupovina);
            array_unshift($oglasi, $pregled->BrOglasa);
            array_unshift($korisnici, $pregled->BrKorisnika);
            array_unshift($logovanja, $pregled->BrLogovanja);
        }
        for ($i = count($pregledi) ; $i < $N; $i++) { 
            $datumi[$i] = ($datumi[$i-1] + 1) % $N;
            $kupovine[$i] = 0; 
            $oglasi[$i] = 0;
            $korisnici[$i] = 0;
            $logovanja[$i] = 0;
        }

        $dani = ['Nedelja', 'Ponedeljak', 'Utorak', 'Sreda', 'Cetvrtak', 'Petak', 'Subota'];
        for ($i = 0; $i < $N; $i++){
            $datumi[$i] = $dani[$datumi[$i]];
        }
   
    ?>
    <script src="<?php echo base_url('/assets/js/chart.min.js'); ?>"></script>
    <script>
        var data0 = {
            label: "Kupovine",
            fillColor : "rgba(22, 105, 122, 0.2)",
            strokeColor : "rgba(22, 105, 122, 1)",
            pointColor : "rgba(22, 105, 122, 1)",
            pointStrokeColor : "#fff",
            pointHighlightFill : "#fff",
            pointHighlightStroke : "rgba(22, 105, 122, 1)",
            data : <?php echo json_encode($kupovine); ?>
        };
        var data1 = {
            label: "Oglasi",
            fillColor : "rgba(255, 166, 43, 0.2)",
            strokeColor : "rgba(255, 166, 43, 1)",
            pointColor : "rgba(255, 166, 43, 1)",
            pointStrokeColor : "#fff",
            pointHighlightFill : "#fff",
            pointHighlightStroke : "rgba(255, 166, 43, 1)",
            data : <?php echo json_encode($oglasi); ?>
        };
        var data2 = {
            label: "Novi korisnici",
            fillColor : "rgba(97, 242, 194, 0.2)",
            strokeColor : "rgba(97, 242, 194, 1)",
            pointColor : "rgba(97, 242, 194, 1)",
            pointStrokeColor : "#fff",
            pointHighlightFill : "#fff",
            pointHighlightStroke : "rgba(97, 242, 194, 1)",
            data : <?php echo json_encode($korisnici); ?>
        };
        var data3 = {
            label: "Logovanja",
            fillColor : "rgba(249, 36, 63, 0.2)",
            strokeColor : "rgba(249, 36, 63, 1)",
            pointColor : "rgba(249, 36, 63, 1)",
            pointStrokeColor : "#fff",
            pointHighlightFill : "#fff",
            pointHighlightStroke : "rgba(249, 36, 63, 1)",
            data : <?php echo json_encode($logovanja); ?>
        };
        var datas = [data0, data1, data2, data3];
        let toShow = datas;
        let prikaz = <?php echo json_encode($zaPrikaz); ?>;
        if (prikaz == null){
            toShow = datas;
        }
        else {
            toShow = [datas[prikaz]];
        }
        var lineChartData = {
            labels : <?php echo json_encode($datumi); ?>,
            datasets : toShow
	    }

        function update(){
            $.ajax({
                url: "<?php echo site_url('Admin/admin_pregled_azuiranje')?>",
                success: function(response){
                    let pregledi = response['pregledi'];
                    
                    let dnevniPregled = pregledi[0];
                    $("#kupovineDnevno").text(dnevniPregled['BrKupovina']);
                    $("#oglasiDnevno").text(dnevniPregled['BrOglasa']);
                    $("#korisniciDnevno").text(dnevniPregled['BrKorisnika']);
                    $("#logovanjaDnevno").text(dnevniPregled['BrLogovanja']);
                    
                    let najKupacIdK = response['najKupacIdK'];
                    if (najKupacIdK == null){
                        $("#najKupacLink").hide();
                    }
                    else {
                        najKupacIdK = String(response['najKupacIdK']);
                        $("#najKupacLink a").attr("href", "<?php echo site_url("$controller/nalog_pregled/")?>" + najKupacIdK);
                        $("#najKupacLink").show();
                    }
                    let najKupacImejl = response['najKupacImejl'];

                    $("#najKupacImejl").text(najKupacImejl);

                    let najProdavacIdK = response['najProdavacIdK'];
                    if (najProdavacIdK == null){
                        $("#najProdavacLink").hide();
                    }
                    else {
                        najProdavacIdK = String(response['najProdavacIdK']);
                        $("#najProdavacLink a").attr("href", "<?php echo site_url("$controller/nalog_pregled/")?>" + najProdavacIdK);
                        $("#najProdavacLink").show();
                    }
                    let najProdavacImejl = response['najProdavacImejl'];
                    
                    $("#najProdavacImejl").text(najProdavacImejl);

                    let zbirniPregled = response['generalniPregled'];
                    $("#kupovineZbirno").text(zbirniPregled['BrKupovina']);
                    $("#oglasiZbirno").text(zbirniPregled['BrOglasa']);
                    $("#korisniciZbirno").text(zbirniPregled['BrKorisnika']);
                    $("#logovanjaZbirno").text(zbirniPregled['BrLogovanja']);


                    lineChartData['datasets'] = toShow;
                    myLine = new Chart(chart1).Line(lineChartData, {
                        responsive: true,
                        scaleLineColor: "rgba(0,0,0,.2)",
                        scaleGridLineColor: "rgba(0,0,0,.05)",
                        scaleFontColor: "#c5c7cc"
                    });
                    console.log(myLine['datasets']);
                }
            });
        }
        var chart1;
        var myLine;
        $(document).ready(function () {
            chart1 = document.getElementById("line-chart").getContext("2d");
            myLine = new Chart(chart1).Line(lineChartData, {
                responsive: true,
                scaleLineColor: "rgba(0,0,0,.2)",
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleFontColor: "#c5c7cc"
            });
            console.log('az');
            
            //update();
            
            setInterval(update, 5000);
        });

    </script>
    
</body>