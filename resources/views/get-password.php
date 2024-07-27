<?php
$con = mysqli_connect("localhost", "root", "", "exam");

// Function to send the verification email
function sendPassEmail($email, $username, $password, $first_name) {
  // Email sending code
  $to = $email;
  $subject = 'Password recovery From COC agency';

  $message = '<html>';
  $message .= '<body style="font-family: Arial, sans-serif;">';
  $message .= '<div style="border: 1px solid #ccc; padding: 20px; margin: 50px;">';
  $message .= '<h2 style="color: #333;">Welcome back to your account, ' . $first_name . '!</h2>';
  $message .= '<p style="color: #555;">find your password below:</p>';
  $message .= '<p style="font-weight: bold; color: #000;">Your password is: ' . $password . '</p>';
  $message .= '</div>';
  $message .= '</body>';
  $message .= '</html>';

  $headers = 'From: southethcoc@gmail.com' . "\r\n";
  $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

  // Send the email
  if (mail($to, $subject, $message, $headers)) {
	echo "success";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2'></script>";
    echo "<script>
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Your password has been sent! Please check your email.'
      }).then(function() {
        window.location.href = 'home.php';
      });
    </script>";
  } else {
	echo "unsuccess";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2'></script>";
    echo "<script>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Error occurred while sending email!'
      });
    </script>";
  }
}
  $username = $_GET['username']; // Assuming the username is submitted through a form field
  $query = "SELECT email, password, first_name FROM staff WHERE username = '$username'";
  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_assoc($result);
  $email = $row['email'];
  $password = $row['password'];
  $first_name = $row['first_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!$result || mysqli_num_rows($result) === 0) {
    // Username does not exist in the staff table
    $err = "Username not found in table.";
  } else {
    // Send the verification email
    sendPassEmail($email, $username, $password, $first_name);
  }
}

mysqli_close($con);
?>
<head>
    <style>
	  .pass{
	    border: 1px solid f4f5ff;
		border-radius:10px;
		background-color:#f4f5ff;
		height: 60%;
		width:50%;
		margin:60px 10px 0 300px;
		padding-left:20px;
		text-align:center;
	  }
	  h2{
		  padding-top:40px;
		  text-align:center;
		  font-size:25;
	  }
	  hr{
		 border: 2px solid f4f5ff;
          height: 1px;
          background-color: #ccc;
		  width:120px;
		  text-align: center;
	  }
	  h3 {
		  color:orange;
		  text-align:center;
		  font-size:25;
	  }
	  h3 span{
		  color:black;
	  }
	  .sub{
		background-color: #4CAF50;
	    color: white;
		margin:20px 170px;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
		}
	.sub:hover {
        background-color: red;
    }
	  
	  
	</style>
</head>

<form method="POST">
  <div class="pass">
    <h2>Send Password<hr></h2>
    <?php if (isset($first_name)) : ?>
      <h3>Hi <?php echo $first_name ?>, <span> <br>click the "Send Your Password" button to retrieve your password.</h3>
    <?php endif; ?>
    <input type="submit" name="submit" value="Send Your Password" class="sub">
  </div>
</form>
<?php include("footer.php");?>