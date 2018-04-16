<?php include "functions.php"; ?>
<?php require_once 'Net/IPv6.php';

function oline($sev, $label, $ip, $time) {

    $ip = Net_IPv6::checkIPv6($ip) ? Net_IPv6::compress($ip) : $ip;

    return "<div class=\"line $sev\"><b>$label ($ip)</b><p>$time ago</p><b>";
}

?>
<div class="container">
  <div class="row clearfix">
  <div class="col-lg-6 col-sm-12 col-xs-12">
      <h2>Current Outages</h2>
      <div class="content">
      <?php
      #$outages = getOutages("?ifRegainedService=null&orderBy=ifLostService&order=desc&limit=100");
      $outages = getOutages("?ifRegainedService=null&orderBy=id&order=desc&limit=100");
      foreach ($outages as $outage) {
        $services = "";
        foreach($outage['services'] as $service){
        $services .= '<span class="bubble">'.$service['serviceName'].'</span>';
        }
        if (strstr($services, 'ICMP')) {
            $severity = "CRITICAL";
        }else{
            $severity = "MAJOR";
        }
        echo oline($severity, $outage['label'], $outage['ipAddress'], $outage['ifLostService']);
        echo $services;
        echo '</b></br>';
        echo '</p><div class="clear"></div></div>';
      }
      ?>
      </div>
  </div>

  <div class="col-lg-6 col-sm-12 col-xs-12">
      <h2>Recent Outages</h2>
      <div class="content">
      <?php
      $outages = getOutages("?ifRegainedService=notnull&orderBy=ifRegainedService&order=desc");
      foreach ($outages as $outage) {
        $services = "";
        foreach($outage['services'] as $service){
        $services .= '<span class="bubble">'.$service['serviceName'].'</span>';
        }
        echo oline('NORMAL', $outage['label'], $outage['ipAddress'], $outage['ifLostService']);
        echo $services;
        echo '</b></br>';
        echo '</p><div class="clear"></div></div>';
      }
      ?>
      </div>
  </div>

  <div class="col-lg-6 col-sm-12 col-xs-12">
      <h2>Alarms</h2>
      <div class="content" id="alarms">
        <?php
        $alarms = getAlarms("?orderBy=firstEventTime&order=desc&limit=7");
        foreach ($alarms as $alarm) {
            print '<div class="line '.$alarm['severity'].'">';
                    print '<h4>'.$alarm['nodeLabel'].'</h4>';
                    print $alarm['description'];
                    print '<div class="clear"></div></div>';
            }
        ?>
      </div>
  </div>

  </div>

</div>
