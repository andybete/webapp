<head>
  <link rel="stylesheet" type="text/css" href="{{ asset('admin.css') }}" />
  <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css') }}">
</head>
<body>
  <div id="headers">
    <?php
    // Define the page hierarchy
    $pages = array(
        'Home' => 'admin.php',
        'Notification' => 'notification.php',
        'New city' => 'city.php',
        'insert new center' => 'Newcenter.php',
        'approve candidate' => 'approveCandidates.php',
        'approve center' => 'approve-center.php',
        'View candidate lists' => 'view-candidate.php',
        'View center lists' => 'view-centers.php',
        'Contact Us' => 'commentOnExam.php'
    );

    // Get the current page
    $current_page = basename($_SERVER['PHP_SELF']);

    // Generate the page hierarchy
    $page_hierarchy = '';

    if ($current_page != 'admin.php') {
        $page_hierarchy .= '<a href="' . $pages['Home'] . '"><i class="fas fa-arrow-left"></i></a>';
    }

    // Add the refresh icon
    $page_hierarchy .= ' <a href="' . $_SERVER['PHP_SELF'] . '"><i class="fas fa-sync-alt"></i></a>';

    // Add the home icon
   $page_hierarchy .= ' <a href="admin.php"><i class="fas fa-home"></i></a>';

    echo '<div style="display: flex; justify-content: flex-start;">';
// Add the current page path as an editable input
if (isset($_SERVER['HTTP_HOST'])) {
    $current_page_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Check if the form is submitted and a redirect URL is provided
    if (isset($_POST['redirect_url'])) {
        $redirect_url = $_POST['redirect_url'];
        header('Location: ' . $redirect_url);
        exit;
    }

    

    $page_hierarchy .= '<form method="POST">
                            <div style="display: flex;">
                                <input type="text" name="redirect_url" value="' . $current_page_url . '" style="margin-left: 20%; margin-top:20px; width: 700px;" />
                            </div>
                        </form>';
}
echo '</div>';
    // Output the page hierarchy
    echo $page_hierarchy;
    ?>
  </div>
</body>