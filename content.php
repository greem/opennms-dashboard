<? 
$con = mysql_connect("localhost", "user", "password") or die("<h3><font color=red>Could not connect to the database!</font></h3>");
$db = mysql_select_db("merlin", $con);

?>
<div class="dash_unhandled hosts dash">
    <h2>Unhandled Hosts down</h2>
    <div class="dash_wrapper">

    </div>
</div>
<div class="dash_unhandled_service_problems hosts dash">
    <h2>Unhandled service problems</h2>
    <div class="dash_wrapper">
       
    </div>
</div>
</body>
</html>
