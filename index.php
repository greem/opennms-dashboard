<?php 
    $refreshvalue = 10; //value in seconds to refresh page
    $pagetitle = "OpenNMS Dashboard";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title><? echo($pagetitle); ?></title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js">
        </script>
        <style type="text/css">
            * {
                margin: 0;
                padding: 0;
            }
            
            body {
                font-family: sans-serif;
                line-height: 1.4em;
        		overflow-x: hidden;
                background: #404040;
                padding: .5em 1em;
            }
            
            h1 {
                display: inline-block;
                margin-left: 10px;
            }
            h2 {
                margin: 0 0 .2em 0;
                color: white;
                text-shadow: 1px 1px 0 #000;
                font-size: 1em;
            }
            .clear {
                clear: both;
            }
            
            .date {
                white-space: nowrap;
            }
            .left {
              float:left;
              width:49%;
            }
            .right {
              float:right;
              width:49%;
            }
            .content {
              background-color:white;
              -moz-border-radius: 1em;
              border-radius: 1em;
              margin-bottom:.5em;
              padding:.5em;
              overflow:auto;
              white-space:nowrap;
              font-size: 1.2em;
              font-weight:bold;
            }
            #events {
              position:absolute;
              top:2em;
              bottom:2.5em;
              margin-right:1em;           
              font-size: 1em;
              font-weight:normal;
              white-space:normal;
            }
	    #events p {
		float:none;
	    }
            p {
              float:right;
            }
            b {
              float:left;
            }
            .line {
              -moz-border-radius: 1em;
              border-radius: 1em;
              padding:.25em 1em .25em 1em;
              margin-bottom:.25em;
            }
            .status1{
              background-color:#EBEBCD;
            }
            .status3{
              background-color:#D7E1CD;
            }
            .status4{
              background-color:#FFF5CD;
            }
            .status5{
              background-color:blanchedAlmond;
            }
            .status6{
              background-color:#FFD7CD;
            }
            .opennms_statusbar {
                background: -moz-linear-gradient(top center, #6a6a6a, #464646);
                position: fixed;
                bottom: 0;
                width: 100%;
                margin: 0 0 0 -1em;
                height: 40px;
                text-align: right;
                border-top: 1px solid #818181;
                opacity: .9;
            }
            .opennms_statusbar_item {
                border-left: 2px groove #000;
                height: 40px;
                line-height: 40px;
                padding: 0 1em;
                color: white;
                text-shadow: 1px 1px 0 black;
                position: relative;
                float: right;
            }
            
            #opennms_placeholder {
            }
            #loading {
                background: transparent url(throbber.gif) no-repeat center center;
                width: 24px;
                height: 40px;
                position: absolute;
            }
            #refreshing {
                padding-left: 35px;
            }
            #refreshing_countdown {
            }
            #timestamp_wrap {
                cursor: default;
                font-size: 2em;
            }
            .timestamp_stamp {
            }
        </style>
    </head>
    <body>
        <script type="text/javascript">

            var placeHolder,
            refreshValue = <?php print $refreshvalue; ?>;
            
            $().ready(function(){
                placeHolder = $("#opennms_placeholder");
                updateopennmsData(placeHolder);
                window.setInterval(updateCountDown, 1000);
            });
            
            
            
            // timestamp stuff
            
            function createTimeStamp() {
                // create timestamp
                var ts = new Date();
                ts = ts.toTimeString();
                //ts = ts.replace(/\s+GMT.+/ig, "");
                ts = ts.replace(/\:\d+(?=$)/ig, "");
                $("#timestamp_wrap").empty().append("<div class=\"timestamp_drop\"></div><div class=\"timestamp_stamp\">" + ts +"</div>");
            }
            
            function updateopennmsData(block){
                $("#loading").fadeIn(200);
    			block.load("./content.php", function(response){
                    $(this).html(response);
                    $("#loading").fadeOut(200);
                    createTimeStamp();
                });
            }
            
            function updateCountDown(){
                var countdown = $("#refreshing_countdown"); 
                var remaining = parseInt(countdown.text());
                if(remaining == 1){
                    updateopennmsData(placeHolder);
                    countdown.text(remaining - 1);
                }
                else if(remaining == 0){
                    countdown.text(refreshValue);
                }
                else {
                    countdown.text(remaining - 1);
                }
            }
            
        </script>
	<div id="opennms_placeholder"></div>
    <div class="opennms_statusbar">
        <div class="opennms_statusbar_item">
            <div id="timestamp_wrap"></div>
        </div>
        <div class="opennms_statusbar_item">
            <div id="loading"></div>
            <p id="refreshing">Refresh in <span id="refreshing_countdown"><?php print $refreshvalue; ?></span> seconds</p>
        </div>
    </div>
    </body>
</html>
