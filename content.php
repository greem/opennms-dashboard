<? 
function time_elapsed_string($ptime) {
    $etime = time() - strtotime($ptime);
    
    if ($etime < 1) {
        return '0 seconds';
    }
    
    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );
    
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '');
        }
    }
}

$dbh = pg_connect("host=localhost dbname=opennms user=opennms");
 if (!$dbh) {
     die("Error in connection: " . pg_last_error());
 }       
?>
<html>
<body>









<div class="services dash">
	<h2>Outages</h2>
	<div class="dash_wrapper">
<table>
			<?
 $sql = "SELECT DISTINCT ON
 (node,nodelabel)
  node.nodelabel, 
  outages.ipaddr, 
  outages.iflostservice
FROM 
  public.node, 
  public.outages 
WHERE 
  outages.nodeid = node.nodeid
  AND outages.ifregainedservice is NULL;";
 $result = pg_query($dbh, $sql);
 if (!$result) {
     die("Error in SQL query: " . pg_last_error());
 }       

				while ($row = pg_fetch_array($result)) {
     				print '<tr class="status6T">';
				print '<td>';
				$host ==  strstr($row[0],".",true) ;
				if ($host != ""){
					print $host;
				}else{
					print $row[0];
				}
				print '</td>';
				print '<td>';
				print $row[1];
				print '</td>';
				print '<td>';
				print time_elapsed_string($row[2]) ;
				print '</td>';
				print '</tr>';
 				}
			?>
</table>
	</div>
</div>
<div class="events hosts dash">
	<h2>Event Log ( last 5 events )</h2>
	<div class="dash_wrapper">
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
     				print '<div class="status'.$row[1].'">';
				print '<h4>'.$row[3].'</h4>';
				print strip_tags($row[0], '<a>') ;
				print '</div>';
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