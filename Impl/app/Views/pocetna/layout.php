<html>

    <head>
        <title>BookKing!</title>
        <link rel="stylesheet" type="text/css" href="http://localhost/project-bookking/assets/css/style.css">
        <link rel="stylesheet"  href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="http://localhost/project-bookking/assets/css/aos.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/project-bookking/assets/font-awesome.min.css">
        <!--CSS TEMPLATE-->
        <link rel="stylesheet" type="text/css" href="http://localhost/project-bookking/assets/css/tooplate-gymso-style.css">
        <style>
            body{
                width:100% !important;
            }

            .navbar{
                background-color: #16697a !important;    
            }

            .navbar a:hover{
                color:#ffa62b !important;    
            }
            .nav-item a:hover{
                color:#ffa62b !important;
            }

            #footer{
                background-color: #16697a !important;        
            }

            .dropdown-menu{
                background-color: #16697a !important;
            }
            
            .homePageSideMenu{
                background-color: #ffa62b !important;    
            }
            
            h1{
              color:#16697a;
            }

            h2{
              color:#30f2f2;
            }

            h3{
              color:#61f2c2;
            }

            h4{
              color:#91f291;
            }

            h5{
              color:#c2f261;
            }

            h6{
              color:#f2f230;
            }

            p{
              color:#16697a;
            }

            .btn{
                background-color: #16697a;
                color:white;
            }

            .btn:hover{
                color:#ffa62b;
            }

            a {
                color:#16697a;
            }

            a:hover{
                color:#ffa62b;
            }

        </style>
    </head>

    <body>
    
        <nav class="navbar navbar-expand-lg fixed-top  ">
            
    
                <a class="navbar-brand" href="<?php echo $_SERVER['PHP_SELF'];?>">BookKing!</a>
    
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="collapse navbar-collapse homePageMenu" id="navbarNav">
                    <ul class="navbar-nav ml-lg-auto">
    
                        <li class="nav-item dropdown">
                            <a href="" class="nav-link  dropdown-toggle" data-target="navbarDropdown" role="button" data-toggle="dropdown">Izbor</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="">izbor1</a>
                                <a class="dropdown-item" href="">izbor2</a>
                                <a class="dropdown-item" href="">izbor3</a>
                                <a class="dropdown-item" href="">izbor4</a>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo $_SERVER['PHP_SELF'];?>?controller=pretraga&akcija=pretraga" class="nav-link smoothScroll">Pretraga</a>
                        </li>
    
                        <li class="nav-item">
                            <a href="<?php echo $_SERVER['PHP_SELF'];?>?controller=nalog&akcija=nalog" class="nav-link smoothScroll">Moj nalog</a>
                        </li>
    
                        <li class="nav-item">
                            <a href="<?php echo $_SERVER['PHP_SELF'];?>?controller=login&akcija=login" class="nav-link smoothScroll">Uloguj se</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo $_SERVER['PHP_SELF'];?>?controller=o_nama&akcija=oNama" class="nav-link smoothScroll">O nama</a>
                        </li>
                    </ul>

                    <!-- Stavljeno zbog lepseg izgleda iako se ne pojavljuje-->
                    <ul class="social-icon ml-lg-3">
                        <li><a href="#" class=""></a></li>
                        <li><a href="#" class=""></a></li>
                        <li><a href="#" class=""></a></li>
                    </ul>
                    <!--
                    <ul class="social-icon ml-lg-3">
                        <li><a href="#" class="fa fa-facebook active"></a></li>
                        <li><a href="#" class="fa fa-twitter active"></a></li>
                        <li><a href="#" class="fa fa-instagram active"></a></li>
                    </ul>
                    -->
                </div>
        </nav>
        <br><br>
        
        <br><br><br><br><br>



             <!-- SCRIPTS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
