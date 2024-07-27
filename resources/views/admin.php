<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="admin.css" />
</head>
    <div id="header">
    <h3>SNNPRS center of competence</h3>
    <ul>
	  <li><a href="admin.php">Home</a></li>
	  <li><a href="notification.php">notification</a><?php
  include("bell.php");
?></li>
      <li>
        <a href="#">Approve account</a>
        <ul class="sub-menus">
		  <li><a href="city.php">Insert city and institution</a></li><br>
          <li><a href="approve-new-center.php">Approve new center</a></li><br>
		  <li><a href="approve-center.php">Approve Center Lists</a></li><br>
          <li><a href="approveCandidates.php">Approve candidate lists</a></li><br>
		   <li><a href="approve-staff.php">Approve Staff</a></li><br>
		   <li><a href="approve-exam.php">Approve Exam</a></li><br>
        </ul>
      </li>
	  <li>
        <a href="#">View and insert account</a>
        <ul class="sub-menus">
		  <li><a href="insert_user.php">About staff</a></li><br>
		  <li><a href="insert_exam_date.php">Insert a desired exam date </a></li><br>
		  <li><a href="view-candidate.php">View candidate lists</a></li><br>
		  <li><a href="view-centers.php">View Center Lists</a></li><br>
		  <li><a href="commentOnExam.php">Comment on exam</a></li><br>
        </ul>
      </li>
      <li><a href="view-feedback.php">Feedback</a></li>
     
      <li class="account">
        <a href="#"><i class="fa fa-bars" onclick="toggleList(event)"></i></a>
        <ul class="sub-menus">
          <li><a href="NewUserAccount.php">Create Account</a></li><br>
          <li><a href="city.php">Insert New city and Department</a></li><br>
          <li><a href="NewUserAccount.php">Create Account</a></li><br>
          <li><a href="approveCandidates.php">Approve candidate lists</a></li><br>
        </ul>
      </li>
    </ul>
  </div>
  <!-- Body of admin -->
    <div id="container">
	<div class="list">
	   <h3> Welcome To Admin Page  </h3>
	   <h2> SNNPRS Occupational <br> Competency Accreditation Agency </h2>
	   <div class="but"><a href="">view comment -></a></div>
	</div>
  <div id="cont_list">
     <h2> SNNPRS COC Agency </h2>
	 <p> This is admin home page. please check the notifications. Check and approve the candidates and approve the exams, add the new centers  </p>
  </div>
  </div>
  <div id="last"></div>
  <script> 
	// Add this JavaScript code in a <script> tag or an external JavaScript file
	// Function to toggle the visibility of the list 
	function toggleList(event) { var list = event.target.parentNode.nextElementSibling; list.style.display = (list.style.display === 'none') ? 'block' : 'none'; } 
	
</script>
<?php
  include("footer.php");
?>
</body>
</html>


  