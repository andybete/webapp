<?php
@include("headOfAdmin.php");
$con = mysqli_connect("localhost", "root", "", "exam");

// Create an empty array to store the cities
$cities = array();

// Fetch the data from the centers table, including only approved centers
$sql = "SELECT * FROM centers WHERE approve = 1";
$result = mysqli_query($con, $sql);

// Store the data grouped by city and institution
while ($row = mysqli_fetch_assoc($result)) {
  $city = $row['list_city'];
  $institution = $row['list_institution'];
  
  // Generate a unique key for each city and institution combination
  $key = $city . '-' . $institution;
  
  if (!isset($cities[$key])) {
    $cities[$key] = array(
      'city' => $city,
      'institution' => $institution,
      'data' => array()
    );
  }
  
  $cities[$key]['data'][] = $row;
}
?>

<head>
  <link rel="stylesheet" type="text/css" href="admin.css" />
</head>

<div id="container">
  <form method="POST">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>City</th>
          <th>Institution</th>
          <th>Department</th>
          <th>Level</th>
          <th>Phone Number</th>
          <th>Email</th>
          <th>Address</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $count = 1;
        foreach ($cities as $cityData):
          $city = $cityData['city'];
          $institution = $cityData['institution'];
          $cityCount = count($cityData['data']);
        ?>
          <?php $institutionRowSpan = true; ?>
          <?php foreach ($cityData['data'] as $index => $row): ?>
            <tr>
              <?php if ($index === 0): ?>
                <td rowspan="<?php echo $cityCount; ?>"><?php echo $count++; ?></td>
                <td rowspan="<?php echo $cityCount; ?>"><?php echo $city; ?></td>
              <?php endif; ?>
              <?php if ($institutionRowSpan): ?>
                <td rowspan="<?php echo $cityCount; ?>"><?php echo $institution; ?></td>
                <?php $institutionRowSpan = false; ?>
              <?php endif; ?>
              <td><?php echo $row['list_department']; ?></td>
              <td><?php echo $row['level']; ?></td>
              <td><?php echo $row['phone_number']; ?></td>
              <td><?php echo $row['email']; ?></td>
              <td><?php echo $row['address']; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </form>
</div>