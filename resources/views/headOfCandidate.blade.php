<head>
  <link rel="stylesheet" type="text/css" href="{{ asset('candidate.css') }}" />
</head>
<body>
<div id="header">
    <img src="images/sam1.png">
</div>
<div id="navigations">
    <?php
    $username = $_GET['username'] ?? '';

    // Define the page hierarchy
    $pages = array(
        'Home' => 'candidate.php',
        'Take exam' => 'take_exam.php',
        'View Result' => 'view_result.php',
        'Registeration' => 'commentOnExam.php',
        'Change password' => 'change_password.php'
    );

    // Get the current page
    $current_page = basename($_SERVER['PHP_SELF']);

    // Generate the page hierarchy
    $page_hierarchy = '<ul>';

    // Output the "Home" page link
    $page_hierarchy .= '<li><a href="' . $pages['Home'] . '?username=' . urlencode($username) . '">' . 'Home' . '</a></li>';

    if ($current_page != 'candidate.php' && isset(array_flip($pages)[$current_page])) {
        $page_hierarchy .= '<li> >> <a href="' . $pages[array_flip($pages)[$current_page]] . '">' . array_flip($pages)[$current_page] . '</a></li>';
    }

    $page_hierarchy .= '</ul>';

    // Output the page hierarchy
    echo $page_hierarchy;
    ?>
</div>
</body>