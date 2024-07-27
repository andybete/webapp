<?php
//session_start(); // Add session_start() at the beginning

$con = mysqli_connect("localhost", "root", "", "exam");

// Fetch city
$cityQuery = "SELECT DISTINCT list_city FROM centers where approve=1";
$cityResult = $con->query($cityQuery);

$city = array();
if ($cityResult->num_rows > 0) {
    while ($cityRow = mysqli_fetch_assoc($cityResult)) {
        $cityName = $cityRow['list_city'];
        $city[$cityName] = $cityName;
    }
}

// Fetch institution for each city from the centers table
$listsQuery = "SELECT list_city, list_institution, list_department, level FROM centers where approve=1";
$listsResult = $con->query($listsQuery);

$institutionDepartments = array();
if ($listsResult->num_rows > 0) {
    while ($listsRow = mysqli_fetch_assoc($listsResult)) {
        $cityName = $listsRow['list_city'];
        $institutionName = $listsRow['list_institution'];
        $department = $listsRow['list_department'];
        $level = $listsRow['level'];

        if (!isset($institutionDepartments[$cityName])) {
            $institutionDepartments[$cityName] = array();
        }

        if (!isset($institutionDepartments[$cityName][$institutionName])) {
            $institutionDepartments[$cityName][$institutionName] = array();
        }

        if (!isset($institutionDepartments[$cityName][$institutionName][$department])) {
            $institutionDepartments[$cityName][$institutionName][$department] = array();
        }

        // Remove duplicates
        if (!in_array($level, $institutionDepartments[$cityName][$institutionName][$department], true)) {
            $institutionDepartments[$cityName][$institutionName][$department][] = $level;
        }
    }
}

// Set session variables if values are submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["selected_city"] = $_POST["list_city"];
    $_SESSION["selected_institution"] = $_POST["list_institution"];
    $_SESSION["selected_department"] = $_POST["list_department"];
    $_SESSION["selected_level"] = $_POST["list_level"];
}
?>

<form method="post"> <!-- Wrap your HTML code within a <form> tag -->
    <label>City:</label>
    <select name="list_city" onchange="populateInstitutions(this)">
        <option value="">Select city</option>
        <?php foreach ($city as $cityName): ?>
            <option value="<?php echo $cityName; ?>" <?php if(isset($_SESSION["selected_city"]) && $_SESSION["selected_city"] == $cityName) echo "selected"; ?>><?php echo $cityName; ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Institution:</label>
    <select name="list_institution" id="list_institution" onchange="populateSectors(this)">
        <option value="">Select institution</option>
        <?php if(isset($_SESSION["selected_city"]) && isset($_SESSION["selected_institution"])): ?>
            <?php $institutions = $institutionDepartments[$_SESSION["selected_city"]]; ?>
            <?php foreach ($institutions as $institution => $departments): ?>
                <option value="<?php echo $institution; ?>" <?php if($_SESSION["selected_institution"] == $institution) echo "selected"; ?>><?php echo $institution; ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select><br><br>

    <label>Department:</label>
    <select name="list_department" id="list_department" onchange="populateLevels(this)">
        <option value="">Select department</option>
        <?php if(isset($_SESSION["selected_city"]) && isset($_SESSION["selected_institution"]) && isset($_SESSION["selected_department"])): ?>
            <?php $departments = $institutionDepartments[$_SESSION["selected_city"]][$_SESSION["selected_institution"]]; ?>
            <?php foreach ($departments as $department => $levels): ?>
                <option value="<?php echo $department; ?>" <?php if($_SESSION["selected_department"] == $department) echo "selected"; ?>><?php echo $department; ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select><br><br>

    <label>Level:</label>
    <select name="list_level" id="list_level">
        <option value="">Select level</option>
        <?php if(isset($_SESSION["selected_city"]) && isset($_SESSION["selected_institution"]) && isset($_SESSION["selected_department"]) && isset($_SESSION["selected_level"])): ?>
            <?php $levels = $institutionDepartments[$_SESSION["selected_city"]][$_SESSION["selected_institution"]][$_SESSION["selected_department"]]; ?>
            <?php foreach ($levels as $level): ?>
                <option value="<?php echo $level; ?>" <?php if($_SESSION["selected_level"] == $level) echo "selected"; ?>><?php echo $level; ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select><br>
</form>

<script>
    function populateInstitutions(citySelect) {
        var city = citySelect.value;
        var institutions = <?php echo json_encode($institutionDepartments); ?>;

        var institutionSelect = document.getElementById("list_institution");
        institutionSelect.innerHTML = "<option value=''>Select institution</option>";

        if (city !== "") {
            var cityInstitutions = institutions[city];

            for (var institution in cityInstitutions) {
                var option = document.createElement("option");
                option.value = institution;
                option.text = institution;
                institutionSelect.appendChild(option);
            }
        }
    }

    function populateSectors(institutionSelect) {
        var city = document.querySelector("[name='list_city']").value;
        var institution = institutionSelect.value;
        var departments = <?php echo json_encode($institutionDepartments); ?>;

        var departmentSelect = document.getElementById("list_department");
        departmentSelect.innerHTML = "<option value=''>Select department</option>";

        if (city !== "" && institution !== "") {
            var institutionDepartments = departments[city][institution];

            for (var department in institutionDepartments) {
                var option = document.createElement("option");
                option.value = department;
                option.text = department;
                departmentSelect.appendChild(option);
            }
        }
    }

    function populateLevels(departmentSelect) {
        var city = document.querySelector("[name='list_city']").value;
        var institution = document.querySelector("[name='list_institution']").value;
        var department = departmentSelect.value;
        var levels = <?php echo json_encode($institutionDepartments); ?>;

        var levelSelect = document.getElementById("list_level");
        levelSelect.innerHTML = "<option value=''>Select level</option>";

        if (city !== "" && institution !== "" && department !== "") {
            var departmentLevels = levels[city][institution][department];

            for (var i = 0; i < departmentLevels.length; i++) {
                var option = document.createElement("option");
                option.value = departmentLevels[i];
                option.text = departmentLevels[i];
                levelSelect.appendChild(option);
            }
        }
    }
</script>