
<h3>PHP Test Code</h3>


<?php
$month = strtotime(date('Y-m-d') );
for ($i=1; $i <=12;$i++)
{
	     echo date('F-Y', $month) ;
	     echo "&nbsp;";
	     echo date('Y_m', $month) ;
	     echo "<br/>";
	          $month = strtotime("+1 month", $month);
	     }
?> 
