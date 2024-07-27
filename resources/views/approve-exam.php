<?php
@include("headOfAdmin");
$con = mysqli_connect("localhost", "root", "", "exam");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve']) && isset($_POST['check'])) {
        $selectedques = $_POST['check'];

        foreach ($selectedques as $key => $status) {
            $data = explode('_', $key);
            $department = $data[0];
            $level = $data[1];
            $ques_no = $data[2];

            if ($status === 'checked') {
                $updateSql = "UPDATE question SET status = 'approved' WHERE department = '$department' AND level = '$level' AND ques_no = '$ques_no'";
                mysqli_query($con, $updateSql);
            }
        }
    }
}

$sql = "SELECT * FROM question WHERE status != 'approved'";
$result = mysqli_query($con, $sql);
?>
<body>
    <div id="containers">
    <?php if (mysqli_num_rows($result) > 0) : ?>
        <h2>NEW EXAM REQUESTS</h2>
        <hr>
        <?php
        $groupedData = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $department = $row['department'];
            $level = $row['level'];
            $groupedData[$department][$level][] = $row;
        }
        ?>

        <form method="POST">
            <?php foreach ($groupedData as $department => $sectorData) : ?>
                <h3>Department: <?php echo $department; ?></h3>
                <?php foreach ($sectorData as $level => $levelData) : ?>
                    <h3>Level: <?php echo $level; ?></h3>
                    <hr>
                    <table>
                        <thead>
                            <tr>
                                <th>ques_no</th>
                                <th>ques</th>
                                <th>txtA</th>
                                <th>txtB</th>
                                <th>txtC</th>
                                <th>txtD</th>
                                <th>answer</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($levelData as $row) : ?>
                                <?php
                                $rowClass = '';
                                $ques_no = $row['ques_no'];
                                $checkboxName = $department . '_' . $level . '_' . $ques_no;
                                if (isset($_POST['check'][$checkboxName]) && $_POST['check'][$checkboxName] === 'checked') {
                                    $rowClass = 'selected-row';
                                }
                                ?>
                                <tr class="<?php echo $rowClass; ?>">
                                    <td><?= $ques_no; ?></td>
                                    <td><?= $row['ques']; ?></td>
                                    <td><?= $row['txtA']; ?></td>
                                    <td><?= $row['txtB']; ?></td>
                                    <td><?= $row['txtC']; ?></td>
                                    <td><?= $row['txtD']; ?></td>
                                    <td><?= $row['answer']; ?></td>
                                    <td><input type="checkbox" name="check[<?= $checkboxName ?>]" value="checked"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
               <?php endforeach; ?>
            <?php endforeach; ?>
            <button type="submit" name="approve" class="submit-button">Approve Exam</button>
        </form>
    <?php else :
        echo '<p class="message">No new exam.</p>';
    endif; ?>
    <?php mysqli_close($con); ?>
	</div>
</body>
<?php
  include("footer.php");
?>