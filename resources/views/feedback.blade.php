<?php
@include("header");
session_start();
// $con = mysqli_connect("localhost", "root", "", "exam");
// $err1 = $err2 = $err3 = "";

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// 	if (isset($_POST['name'])) {
//         $name = $_POST['name'];
//         $_SESSION['name'] = $name;
//     }
//     $email = $_SESSION['email'] = $_POST['email'];
//     $comment = $_SESSION['comment'] = $_POST['comment'];

//     if (empty($email)) {
//         $err1 = "Email is required";
//     } else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
//         $err1 = "Invalid email format";
//     }
//     if (empty($comment)) {
//         $err3 = "Comment is required";
//     }
// 	if(!empty($email) && !empty($comment))
// 	{
// 		     // Set the desired timezone
//                        $timezone = new DateTimeZone('UTC'); // Replace 'UTC' with the desired timezone
// 		     // Create a new DateTime object with the current date and the desired timezone
//                        $currentDateTime = new DateTime('now', $timezone);

//                        // Get the current year, month, and day
//                        $currentYear = $currentDateTime->format('Y');
//                        $currentMonth = $currentDateTime->format('m');
//                        $currentDay = $currentDateTime->format('d');
					   
// 		$sql = "INSERT INTO feedback (name, email, comment, date) VALUES ('$name', '$email', '$comment', '$currentYear-$currentMonth-$currentDay')";
// 		$res = mysqli_query($con, $sql);
// 		 if(!$res){
// 			   echo"failed to insert your comment";
// 		   }
// 		 else {
// 			 echo "<script>alert('Thanks for your comment!!!Your feedback is valuable to us.');</script>";
            
            
//          }
// 	}
// }
?>
<head>
  <link rel="stylesheet" type="text/css" href="{{ asset('styles.css') }}" />  
  <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/image_slide.js') }}"></script>
</head>  
<body>
  <div id="site_content">			  
    <ul class="slideshow">
      <li><img width="870" height="250" src="{{ asset('images/images.jpg') }}"/></li>
      <li><img width="870" height="250" src="{{ asset('images/inaction.jpg') }}"/></li>
      <li><img width="870" height="250" src="{{ asset('images/inaction.jpg') }}"/></li>
    </ul> <br>  
	<h1>Welcome to south ethiopia coc information System </h1>

<div id="feedback">
<fieldset><legend>detail</legend>
<form method="post" action="">
				<label for="name">Name:</label>
				<input name="name" type="text" placeholder="Full name(optional)" value="<?php echo $_SESSION['name']; ?>"><br>
 
				<label for="email">Email:</label>
				<input name="email" id="email" type="text" value="<?php echo $_SESSION['email']; ?>" placeholder="Enter Your Email ex.abc@gmail.com" oninput="validateEmail()">
				<span id="emailError" style="color: red;"></span> <?php echo "<font color='red'>$err1 </font>"; ?><br>
  
				<label for="comment">Comment:</label>
				<textarea name="comment" id="comment" placeholder="Write your comment here" cols="34" rows="7" required><?php echo $_SESSION['comment']; ?></textarea>
	            <?php echo "<font color='red'>$err3 </font>"; ?><br>
				<input value="send" name="submit" type="Submit">
			</form>
</fieldset>
</div></div>
<script>
/* automatically validate email*/
function validateEmail() {
  var emailInput = document.getElementById("email");
  var emailError = document.getElementById("emailError");
  var email = emailInput.value.trim();

  // Validate email format
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === "") {
    emailError.textContent = "";
  } else if (!emailPattern.test(email)) {
    emailError.textContent = "Invalid email format";
  } else {
    emailError.textContent = "";
  }
}
</script>
<?php
session_destroy();
include("footer.php");
?>