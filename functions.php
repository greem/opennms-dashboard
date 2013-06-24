<?

Function feedMe($feed) {

    include('auth.php'); // $opennms_url, $opennms_pass, $opennms_user

    // Use cURL to fetch text
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $opennms_url.$feed);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    #curl_setopt ($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_USERPWD, $opennms_user.':'.$opennms_pass);
    $data = curl_exec($ch);
    curl_close($ch);

    $data = simplexml_load_string($data);
    return($data);
}

Function nodeInfo($id) {
    $data = feedMe("/rest/nodes/".$id);
    return ($data->attributes()->label);
}

function humanTiming ($time)
{

    $time = strtotime($time);
    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}

Function getOutages($options){
    $outageXML = feedMe("/rest/outages".$options);
  
    $cnt = count($outageXML->outage);
    $outages = array();
    for($i=0; $i<$cnt; $i++)
    {
        $id = $outageXML->outage[$i]->serviceLostEvent->nodeId;
        $outages["$id"]['label'] = nodeInfo($id);
        $outages["$id"]['ifLostService'] = humanTiming($outageXML->outage[$i]->ifLostService);
        $outages["$id"]['ifRegainedService'] = humanTiming($outageXML->outage[$i]->ifRegainedService);
        $outages["$id"]['ipAddress'] = $outageXML->outage[$i]->ipAddress;
        $sid = $outageXML->outage[$i]->monitoredService->serviceType['id'];
        $outages["$id"]['services']['serviceType-'."$sid"]['serviceName'] = $outageXML->outage[$i]->monitoredService->serviceType->name;
    }
    return($outages);
}

Function getAlarms($options){
  $alarmXML = feedMe("/rest/alarms".$options);

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
