<head>
<link rel="stylesheet" type="text/css" href="{{ asset('styles.css') }}" />
</head>
<body>
    <div id="header">
     <img  src="images/sam1.png">
	</div>
    <div id="navigation">
	  <ul>
	     <li><a href="eps.php">Home</a></li>
		 <li>
        <a href="#">Exam</a>
        <ul class="sub-menus">
		  <li><a href="create_exam.php">Create theory exam</a></li><br>
          <li><a href="create_practical_exam.php">Create Practical exam</a></li><br>
		  <li><a href="edit_question.php">Edit Exam</a></li><br>
        </ul>
      </li>
	     <li><a href="eps_notification.php">notification</a><?php include("epsbell.php");?></li>
	     <li><a href="logout.php">Log out</a></li>
	  </ul>
    </div>
	<!-- body of eps -->
	<div id="site_content">	
	 <h1>Message from exam preparation sections </h1> 
    
	      <p>Message:</p>
		   <div class="content_item">
		     <div class="content_image">
		    <img src="images/EC.jpg"width="159" height="162"/></div><p align="justify">
        <font size="3"/></br>
Welcome to the south National Regional State Center of Competence is a core institution established, at regional level, to implement and facilitate National Occupational Assessment and Certification in the region. It is born from the ambition of government to have competent and self-reliant workforce that meet labor market demands; thus contributing to the economic and social development of the region as well as country.<br><br></p>	
	</div>
	</div>
</body>
<?php
include("footer.php");?>