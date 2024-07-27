<?php
$city = $_GET['city'];
$institution = $_GET['institution'];
?>
<head>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <div id="header">
     <img  src="images/sam1.png">
	</div>
    <div id="navigation">
	  <ul>
	     <li><a href="school.php?city=<?php echo urlencode($city); ?>&institution=<?php echo urlencode($institution); ?>">Home</a></li>
		 <li><a href="register-center.php?city=<?php echo urlencode($city); ?>&institution=<?php echo urlencode($institution); ?>">register centers</a></li>
		 <li><a href="school_notification.php?city=<?php echo urlencode($city); ?>&institution=<?php echo urlencode($institution); ?>">Notification</a><?php
  include("school_bell.php");
?></li>
	     
		 <li>
        <a href="#">User account</a>
        <ul class="sub-menus">
          <li><a href="school_view_candidate.php?city=<?php echo urlencode($city); ?>&institution=<?php echo urlencode($institution); ?>">View Candidate</a></li><br>
		  <li><a href="school_view_centers.php?city=<?php echo urlencode($city); ?>&institution=<?php echo urlencode($institution); ?>">View Center</a></li><br>
          <li><a href="school_approve_candidate.php?city=<?php echo urlencode($city); ?>&institution=<?php echo urlencode($institution); ?>">Approve candidate lists</a></li><br>
        </ul>
      </li>
	     <li><a href="logout.php">Log out</a></li>
	  </ul>
    </div>
	<div id="site_content">	
	
	  
	</div>