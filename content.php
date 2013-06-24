<?
include "functions.php";
?>
<div class="left">
  <h2>Current Outages</h2>
  <div class="content">
  <?		  
  $outages = getOutages("?ifRegainedService=null&orderBy=ifLostService&order=desc&limit=100");
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
    echo '<div class="line '.$severity.'"><b>'.$outage["label"].'('.$outage["ipAddress"].')</b><p>'.$outage["ifLostService"].' ago</p><b>';
    echo $services;
    echo '</b></br>';
    echo '</p><div class="clear"></div></div>';
  }
  ?>
  </div>
  <h2>Recent Outages</h2>
  <div class="content">
  <?		  
  $outages = getOutages("?ifRegainedService=notnull&orderBy=ifRegainedService&order=desc");
  foreach ($outages as $outage) {
    $services = "";
    foreach($outage['services'] as $service){
	$services .= '<span class="bubble">'.$service['serviceName'].'</span>';
    }
    echo '<div class="line NORMAL"><b>'.$outage["label"].'('.$outage["ipAddress"].')</b><p>'.$outage["ifLostService"].' ago</p><b>';
    echo $services;
    echo '</b></br>';
    echo '</p><div class="clear"></div></div>';
  }
  ?>
  </div>
</div>

<div class="right">
	<h2>Alarms</h2>
  <div class="content" id="alarms">
	<?
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
