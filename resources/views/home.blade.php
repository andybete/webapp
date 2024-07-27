
@include("header");


<head>
  <link rel="stylesheet" type="text/css" href="styles.css" />
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/image_slide.js"></script>
</head>  
<body>
  
  <div id="site_content">			  
    <ul class="slideshow">
      <li><img width="870" height="250" src="images/images.jpg"/></li>
      <li><img width="870" height="250" src="images/inaction.jpg"/></li>
      <li><img width="870" height="250" src="images/inaction.jpg"/></li>
    </ul> <br>  
    
    <div class="login_container">
      <div class="login">
        <h2>Login</h2>
        <form method="post">
          User Name<br><input type="text" name="uname"/><br>
          Password<br><input type="password" name="pass" /><br>
          <?php echo "<font color='red'>$err </font>"; ?><br>
          <input type="submit" name="submit" value="Login"/>
          <a href="Registeration.php">Signup </a><br>
		  <a href="forget_pass.php">Forget password? </a>
        </form>
      </div>
    </div>
	
    <div class="content_item">
      <div class="content_image">
        <img src="images/moe.png" width="160" height="130"/>
      </div>
      <p align="justify">
        <font size="4"/><span class="style1">Message</span>:</br></br>
        Dear visitors,
        Welcome to the South National Regional State Center of Competence. It is a core institution established at the regional level to implement and facilitate National Occupational Assessment and Certification in the region. It is born from the ambition of the government to have a competent and self-reliant workforce that meets labor market demands, thus contributing to the economic and social development of the region as well as the country.
      </p>	
    </div>
  </div> 


  @include("footer");
 
</body>