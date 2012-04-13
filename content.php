<?
include "functions.php";
?>
<div class="left">
  <h2>Current Outages</h2>
  <div class="content">
  <?		  
  $outages = getOutages("?ifRegainedService=null&orderBy=ifRegainedService&order=desc");
  foreach ($outages as $outage) {
    echo '<div class="line MAJOR"><b>'.$outage["label"].'</b><p>'.$outage["serviceName"].'</p></br>';
    echo '<b>'.$outage["ipAddress"].'</b><p>'.$outage["ifLostService"].'</p><div class="clear"></div></div>';
  }  
  ?>
  </div>
  <h2>Recent Outages</h2>
  <div class="content">
  <?		  
  $outages = getOutages("?ifRegainedService=notnull&orderBy=ifRegainedService&order=desc");
  foreach ($outages as $outage) {
    echo '<div class="line NORMAL"><b>'.$outage["label"].'</b><p>'.$outage["serviceName"].'</p></br>';
    echo '<b>'.$outage["ipAddress"].'</b><p>'.$outage["ifRegainedService"].'</p><div class="clear"></div></div>';
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