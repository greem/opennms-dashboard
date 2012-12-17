<?php 
    $refreshvalue = 10; //value in seconds to refresh page
    $pagetitle = "OpenNMS Dashboard";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <title><? echo($pagetitle); ?></title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css" /
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
        ts = ts.replace(/\s+GMT.+/ig, "");
        ts = ts.replace(/\:\d+(?=$)/ig, "");
        $("#timestamp_wrap").empty().append("<div class=\"timestamp_drop\"></div><div class=\"timestamp_stamp\">" + ts +"</div>");
      }
      
      function updateopennmsData(block){
        $("#loading").fadeIn(200);
    		block.load("content.php", function(response){
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
