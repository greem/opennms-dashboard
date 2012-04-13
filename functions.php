<?
Function feedMe($feed) {
	// Use cURL to fetch text
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $feed);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_USERAGENT, $useragent);
	$data = curl_exec($ch);
	curl_close($ch);
  
	$data = simplexml_load_string($data);
  return($data);
}

Function nodeInfo($id) {
  // Use cURL to fetch text
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost:8980/opennms/rest/nodes/".$id);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_USERAGENT, $useragent);
	$data = curl_exec($ch);
	curl_close($ch);
  
	$data = simplexml_load_string($data);
  
  $node = array();

  return($data->attributes()->label);
}

Function getOutages($options){
  $outageXML = feedMe("http://localhost:8980/opennms/rest/outages".$options);
  
	$cnt = count($outageXML->outage);
  $outages = array();
  for($i=0; $i<$cnt; $i++)
	{
    $id = $outageXML->outage[$i]->serviceLostEvent->nodeId;
    $outages["$id"]['label'] = nodeInfo($id);
    $outages["$id"]['ifLostService'] = $outageXML->outage[$i]->ifLostService;
    $outages["$id"]['ifRegainedService'] = $outageXML->outage[$i]->ifRegainedService;
    $outages["$id"]['ipAddress'] = $outageXML->outage[$i]->ipAddress;
    $outages["$id"]['serviceName'] = $outageXML->outage[$i]->monitoredService->serviceType->name;
  }
  return($outages);
}

Function getAlarms($options){
  $alarmXML = feedMe("http://localhost:8980/opennms/rest/alarms".$options);

	$cnt = count($alarmXML->alarm);
  $alarms = array();
  for($i=0; $i<$cnt; $i++)
	{
    $id = $alarmXML->alarm[$i]->nodeId;
    $alarms["$id"]['nodeLabel'] = $alarmXML->alarm[$i]->nodeLabel;
    $alarms["$id"]['description'] = $alarmXML->alarm[$i]->lastEvent->description;
    $alarms["$id"]['severity'] = $alarmXML->alarm[$i]->attributes()->severity;
  }
  return($alarms);
}
?>