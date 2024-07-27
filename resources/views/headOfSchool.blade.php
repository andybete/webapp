<?php

$institution = $_GET['institution'] ?? '';
$city = $_GET['city'] ?? '';
?>
<head>
<link rel="stylesheet" type="text/css" href="{{ asset('') }}styles.css') }}" />
</head>
<body>
    <div id="header">
     <img src="images/sam1.png">
	</div>
    <div id="navigation">
  <ul>
    <?php
      // Define the page hierarchy
      $pages = array(
          'Home' => 'school.php?institution=' . urlencode($_GET['institution']) . '&city=' . urlencode($_GET['city']),
          'Notification' => 'notification.php',
          'New city' => 'city.php',
          'register center' => 'register-center.php?institution=' . urlencode($_GET['institution']) . '&city=' . urlencode($_GET['city']),
          'approve candidate' => 'approveCandidates.php?city=' . urlencode($_GET['city']),
          'approve center' => 'approve-center.php',
          'View candidate lists' => 'school_view_candidate.php?institution=' . urlencode($_GET['institution']) . '&city=' . urlencode($_GET['city']),
          'View center lists' => 'view-centers.php',
          'Contact Us' => 'commentOnExam.php'
      );

      // Get the current page
      $current_page = basename($_SERVER['PHP_SELF']);

      // Generate the page hierarchy
      $page_hierarchy = '<li style="margin-left:50%;"><a href="' . $pages['Home'] . '">Home</a></li>';

      if ($current_page != 'school.php' && isset(array_flip($pages)[$current_page])) {
          $page_hierarchy .= '<li>> <a href="' . $pages[array_flip($pages)[$current_page]] . '">' . array_flip($pages)[$current_page] . '</a></li>';
      }

      // Output the page hierarchy
      echo $page_hierarchy;
    ?>
  </ul>
</div>
</body>