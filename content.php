<? 
$dbh = pg_connect("host=localhost dbname=opennms user=opennms");
  if (!$dbh) {
    die("Error in connection: " . pg_last_error());
  }       
?>
<html>
<body>

<div class="left">
  <h2>Current Outages</h2>
  <div class="content">
  <?
	Function feedMe($feed) {
	// Use cURL to fetch text
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $feed);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_USERAGENT, $useragent);
	$rss = curl_exec($ch);
	curl_close($ch);

  $rss = ltrim($rss, "\n");
  
  // Manipulate string into object
	$rss = simplexml_load_string($rss);
    
  //$siteTitle = $rss->channel->title;
	//echo "<h1>".$siteTitle."</h1>";
	//echo "<hr />";

	$cnt = count($rss->channel->item);

	for($i=0; $i<$cnt; $i++)
	{
    if (strpos($rss->channel->item[$i]->title,'(resolved)') == false) {
		  $url = $rss->channel->item[$i]->link;
		  $title = str_replace(".fcnt.franklincollege.edu", "", $rss->channel->item[$i]->title);
		  $time = $rss->channel->item[$i]->pubDate;
		  echo '<div class="line status6"><b><a href="'.$url.'">'.$title.'</a></b><p>'.$time.'</p></div>';
    }
	}
  ?>
</div>
  <h2>Resolved Outages (Last 4)</h2>
  <div class="content">
  <?
  for($i=0; $i<4; $i++)
	{
    if (strpos($rss->channel->item[$i]->title,'(resolved)') !== false) {
		  $url = $rss->channel->item[$i]->link;
		  $title = str_replace(".fcnt.franklincollege.edu", "", $rss->channel->item[$i]->title);
		  $time = $rss->channel->item[$i]->pubDate;
		  echo '<div class="line status3"><b><a href="'.$url.'">'.$title.'</a></b><p>'.$time.'</p></div>';
    }
	}
  ?>
</div>
  <?
}

feedMe("http://localhost:8980/opennms/rss.jsp?feed=outage");
?>
</div>
<div class="right">
	<h2>Event Log</h2>
  <div class="content" id="events">
			<?
 $sql = "SELECT 
		events.eventdescr, 
		events.eventseverity, 
		events.eventtime,
		node.nodelabel
	FROM public.events, public.node
	WHERE node.nodeid = events.nodeid
	ORDER BY events.eventtime DESC
	LIMIT 10;";
 $result = pg_query($dbh, $sql);
 if (!$result) {
     die("Error in SQL query: " . pg_last_error());
 }       

		 		while ($row = pg_fetch_array($result)) {
     				print '<div class="line status'.$row[1].'">';
				print '<h4>'.$row[3].'</h4>';
				print $row[0];
				print '<div class="clear"></div></div>';
 				}
			?>
</div>
</div>

</body>
</html>

<?
 // free memory
 pg_free_result($result);       

 // close connection
 pg_close($dbh);
 ?>