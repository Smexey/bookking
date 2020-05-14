<html>

<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/poruke.css'); ?>">
</head>

<body>
    <div class="container">

        <br><br>

        <div class="messaging">
            <div class="inbox_msg">
                <div class="inbox_people">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h4>Messages</h4>
                        </div>
                    </div>

                    <div class="inbox_chat">
                        <?php
                        // echo $konverzacije[0]->Korisnik2;
                        use App\Models\ModelKorisnik;

                        $modelKorisnik = new ModelKorisnik();

                        foreach ($konverzacije as $konv) {
                            $ret = "";
                            $ret .= "<form class='porform' action=" . site_url("$controller/otvoriKonverzaciju_action") . " method='POST'>";

                            $ret .= "<button class='chat_list";
                            if (isset($selected) && $konv->Korisnik2 == $selected) {
                                $ret .= " active_chat";
                            }
                            $ret .= "' type='submit'> ";

                            $ret .= " <div class='chat_people'> ";
                            $ret .= " <div class='chat_ib'> ";
                            $ret .= "<input type='hidden' name='korisnikPrimalac' value='" . $konv->Korisnik2 . "'>";

                            $ret .= $modelKorisnik->find($konv->Korisnik2)->Ime;
                            //<p>poslednja poruka ide ovde?</p>

                            $ret .= " </div> ";
                            $ret .= " </div> ";
                            $ret .= " </button> ";
                            $ret .= " </form> ";

                            echo $ret;
                        }
                        ?>
                    </div>
                </div>

                <div class="mesgs">
                    <div class="msg_history">

                        <?php
                        if (isset($currentPoruke)) {
                            foreach ($currentPoruke as $por) {
                                $ret = "";

                                if ($selected == $por->Korisnik2) {
                                    $ret .= "<div class='incoming_msg'>";
                                    $ret .= "<div class='received_withd_msg'>";
                                } else {
                                    $ret .= "<div class='outgoing_msg'>";
                                    $ret .= "<div class='sent_msg'>";
                                }

                                $ret .=  "<p>";
                                $ret .= $por->Tekst;
                                $ret .= "</p>";
                                $ret .= "</div>";
                                $ret .= "</div>";
                                echo $ret;
                            }
                        }

                        ?>
                    </div>

                    <form action="<?php echo site_url("$controller/posaljiPor_action"); ?>" method="POST">
                        <div class="type_msg">
                            <div class="input_msg_write">
                                <input type="text" name="text" class="write_msg" value="<?php if (isset($_POST['text'])) echo $_POST['text'] ?>" placeholder="Type a message" />

                                <button class="msg_send_btn" type="submit"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <br><br>

        </div>
    </div>
</body>

</html>