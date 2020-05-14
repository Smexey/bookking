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
                            <h4>Recent</h4>
                        </div>

                        <!-- <div class="srch_bar">
                            <div class="stylish-input-group">
                                <input type="text" class="search-bar" placeholder="Search">
                                <span class="input-group-addon">
                                    <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                </span> </div>
                        </div> -->

                    </div>
                    <div class="inbox_chat">

                        <div class="chat_list active_chat">
                            <div class="chat_people">
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.</p>
                                </div>
                            </div>
                        </div>
                        <div class="chat_list">
                            <div class="chat_people">
                                <div class="chat_ib">
                                    <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                                    <p>Test, which is a new approach to have all solutions
                                        astrology under one roof.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="mesgs">
                    <div class="msg_history">
                        <!-- <div class="incoming_msg">
                            <div class="received_msg">
                                <div class="received_withd_msg">
                                    <p>Test which is a new approach to have all
                                        solutions</p>
                                    <span class="time_date"> 11:01 AM | June 9</span>
                                </div>
                            </div>
                        </div>
                        <div class="outgoing_msg">
                            <div class="sent_msg">
                                <p>Test which is a new approach to have all
                                    solutions</p>
                                <span class="time_date"> 11:01 AM | June 9</span>
                            </div>
                        </div> -->
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
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        </div>
    </div>
</body>

</html>