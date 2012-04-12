<?php
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
  
  $siteTitle = $rss->channel->title;
	echo "<h1>".$siteTitle."</h1>";
	echo "<hr />";

	$cnt = count($rss->channel->item);

	for($i=0; $i<$cnt; $i++)
	{
    if (strpos($rss->channel->item[$i]->title,'(resolved)') == false) {
		  $url = $rss->channel->item[$i]->link;
		  $title = $rss->channel->item[$i]->title;
		  $desc = $rss->channel->item[$i]->description;
		  echo '<h3><a href="'.$url.'">'.$title.'</a></h3>'.$desc.'';
    }
	}
}

feedMe("http://10.10.32.25:8980/opennms/rss.jsp?feed=outage");
?>