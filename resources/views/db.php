<?php 

$con=mysqli_connect("localhost", "root", "","exam");
 if(!$con)
   {
    echo"unable to connect";
   }
   else
     {
	   echo "connect";
	   }
?>