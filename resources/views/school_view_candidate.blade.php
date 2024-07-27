<?php
$institution = $_GET['institution'] ?? '';
$city = $_GET['city'] ?? '';

//include("headOfSchool.php");
$con = mysqli_connect("localhost", "root", "", "exam");
?>

<head>
  <link rel="stylesheet" type="text/css" href="{{ asset('styles.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('admin.css') }}" />
</head>
<div id="header1">
     <img width="100%" height="80px" src="images/sam1.png">
	</div>
    <div id="navigation1">
	  <?php
	
    // Define the page hierarchy
    $pages = array(
        'Home' => 'school.php?institution=' . urlencode($_GET['institution']) . '&city=' . urlencode($_GET['city']),
        'approve candidate' => 'school_approve_candidate.php?institution=' . urlencode($_GET['institution']) . '&city=' . urlencode($_GET['city']),
    );

    // Get the current page
    $current_page = basename($_SERVER['PHP_SELF']);

    // Generate the page hierarchy
    $page_hierarchy = '<a href="' . $pages['Home'] . '">Home</a>';

    if ($current_page != 'school.php' && isset(array_flip($pages)[$current_page])) {
        $page_hierarchy .= ' >> <a href="' . $pages[array_flip($pages)[$current_page]] . '">' . array_flip($pages)[$current_page] . '</a>';
    }

    // Output the page hierarchy
    echo $page_hierarchy;
    ?>
	</div>
   
   <div id="site_content1">
    <div class="list">
    <?php if (!isset($_POST['year']) || !isset($_POST['month'])): ?>
	  <div class="list-info">
        <form method="POST" action="">
            <label for="year">Year:</label>
            <select name="year" id="year">
			    <option value="">Select Year</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <!-- Add more options for years as needed -->
            </select><br><br>
            <label for="month">Month:</label>
            <select name="month" id="month">
			    <option value="">Select month</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
                <!-- Add more options for months as needed -->
            </select><br><br>
            <?php
            // Fetch options for city, institution, and level from the centers table
            $optionsQuery = "SELECT DISTINCT list_city, list_institution, list_department, level FROM centers";
            $optionsResult = mysqli_query($con, $optionsQuery);

            $uniqueCities = [];
            $uniqueInstitutions = [];
			$uniqueDepartments = [];
            $uniqueLevels = [];

            while ($row = mysqli_fetch_assoc($optionsResult)) {
                $uniqueCities[] = $row['list_city'];
                $uniqueInstitutions[] = $row['list_institution'];
				$uniqueDepartments[] = $row['list_department'];
                $uniqueLevels[] = $row['level'];
            }
            ?>

            <label for="city">City:</label>
            <select name="city" id="city">
			    <option value="">Select city</option>
                <?php foreach (array_unique($uniqueCities) as $city): ?>
                    <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="institution">Institution:</label>
            <select name="institution" id="institution">
			    <option value="">Select Institution</option>
                <?php foreach (array_unique($uniqueInstitutions) as $institution): ?>
                    <option value="<?php echo $institution; ?>"><?php echo $institution; ?></option>
                <?php endforeach; ?>
            </select><br><br>
			
			<label for="department">Department:</label>
            <select name="department" id="department">
			    <option value="">Select Department</option>
                <?php foreach (array_unique($uniqueDepartments) as $department): ?>
                    <option value="<?php echo $department; ?>"><?php echo $department; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="level">Level:</label>
            <select name="level" id="level">
			    <option value="">Select Level</option>
                <?php foreach (array_unique($uniqueLevels) as $level): ?>
                    <option value="<?php echo $level; ?>"><?php echo $level; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <input type="submit" value="View lists" class="submit-button">
		   </div>
        </form>
		</div>
    
	    
		<div id="cont_list">
		   <h2> SNNPRS COC Agency </h2>
		   <p> This page display the candidate lists.<br> To view candidate lists first please select the lists you want to view cndidate lists, then click view lists button</P>
		</div>
		<?php else: ?>
        <?php
        $selectedYear = $_POST['year'];
        $selectedMonth = $_POST['month'];
        $selectedCity = $_POST['city'];
        $selectedInstitution = $_POST['institution'];
		$selectedDepartment = $_POST['department'];
        $selectedLevel = $_POST['level'];

        // Query to fetch data from the approvecandidate table based on the selected year, month, city, institution, and level
        // Base query to fetch data from the approvecandidate table
   // $query = "SELECT * FROM approvecandidate WHERE 1 = 1";
	$query = "SELECT * FROM approvecandidate WHERE city = '$city' AND institution = '$institution'";

    // Append conditions to the query based on the selected filters
    if (!empty($selectedYear)) {
        $query .= " AND YEAR(approved_date) = $selectedYear";
    }

    if (!empty($selectedMonth)) {
        $query .= " AND MONTH(approved_date) = $selectedMonth";
    }

    if (!empty($selectedCity)) {
        $query .= " AND city = '$selectedCity'";
    }

    if (!empty($selectedInstitution)) {
        $query .= " AND institution = '$selectedInstitution'";
    }
	
	if (!empty($selectedDepartment)) {
        $query .= " AND department = '$selectedDepartment'";
    }

    if (!empty($selectedLevel)) {
        $query .= " AND level = '$selectedLevel'";
    }

        $result = mysqli_query($con, $query);
		if (!$result) {
            die('Query Error: ' . mysqli_error($con));
        }
        $count = 1;
        ?>
        <h2 align="center"> VIEW CANDIDATE LISTS <hr></h2>
		       
			   <fieldset>
        <legend>Informations</legend>
        <?php if (isset($_POST['city']) && isset($_POST['institution']) && isset($_POST['department']) && isset($_POST['level'])): ?>
            <?php $selectedCity = $_POST['city']; ?>
            <?php $selectedInstitution = $_POST['institution']; ?>
            <?php $selectedDepartment = $_POST['department']; ?>
            <?php $selectedLevel = $_POST['level']; ?>
            <?php $selectedYear = $_POST['year']; ?>
         <div class="infos">
            <span>City:</span><?php echo $selectedCity; ?><br>
            <span>Institution:</span><?php echo $selectedInstitution; ?><br>
            <span>Department:</span><?php echo $selectedDepartment ?><br>
            <span>Level:</span><?php echo $selectedLevel; ?><br>
            <span>Approved Year:</span><?php echo $selectedYear ?><br>
		 </div>
        <?php endif; ?>
    </fieldset>
				
        <form method="POST">
            <table>
                <thead>
				  <?php if (mysqli_num_rows($result) === 0): ?>
                            <h1>No candidates found</h1>
                    <?php else: ?>
                    <tr>
                        <th>no</th>
						<th>Username</th>
                        <th>password</th>
                        <th>Name</th>
                        <th>father_name</th>
                        <th>last_name</th>
                        <th>gender</th>
                        <th>phone_number</th>
                        <th>email</th>
                        <th>date_of_birth</th>
                        <th>age</th>
						<th>City</th>
                        <th>institution</th>
                        <th>department</th>
                        <th>level</th>
                        <th>gc_year</th>
                        <th>grade_report</th>
                        <th>address</th>
                    </tr>
                </thead>
                <tbody>
                    
					  <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
							<td><?php echo $row['username']; ?></td>
							<td><?php echo $row['password']; ?></td>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['father_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['phone_number']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['Date_Of_Birth']; ?></td>
                            <td><?php echo $row['age']; ?></td>
							<td><?php echo $row['city']; ?></td>
                            <td><?php echo $row['institution']; ?></td>
                            <td><?php echo $row['department']; ?></td>
                            <td><?php echo $row['level']; ?></td>
                            <td><?php echo $row['gc_year']; ?></td>
							<td><?php echo "<a href='" . $row['grade_report'] . "'>View photo</a>"; ?></td>
                            <td><?php echo $row['address']; ?></td>
                        </tr>
                      <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    <?php endif; ?>
</div>