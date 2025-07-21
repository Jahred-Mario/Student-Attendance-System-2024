<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Library Attendance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="p-3">
    <div class="row mb-3 align-items-center">
        <div class="col d-flex justify-content-start">
            <a class="btn btn-primary" href="index.php">Back</a>
        </div>

        <div class="col d-flex justify-content-end">
            <form action="backup.php" method="post">
                <input type="text" placeholder="M/DD/YYYY" class="textbox" name="SearchDate" />
                <button type="submit" class="btn btn-primary">Search Date</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-hover text-center table-bordered" id="attendanceTable">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Student Number</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Timed In</th>
                    <th>Timed Out</th>
                </tr>
                </thead>
                <tbody id="data">
                    <?php 
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            include("connect.php");

                            $DateSearch = mysqli_real_escape_string($conn1, $_POST["SearchDate"]);

                            // Check if table exists
                            $checkTableQuery = "SHOW TABLES LIKE '$DateSearch'";
                            $checkTableResult = mysqli_query($conn1, $checkTableQuery);

                            if (mysqli_num_rows($checkTableResult) > 0) {
                                $SQLSearchDate = "SELECT * FROM `$DateSearch` ORDER BY time_in";
                                $resultSQLSearchDate = mysqli_query($conn1, $SQLSearchDate) or die ("Error in query: " . mysqli_error($conn1));
                                $num_resultSQLSearchDate = mysqli_num_rows($resultSQLSearchDate);

                                if ($num_resultSQLSearchDate > 0) {
                                    while ($rowSearchDate = mysqli_fetch_array($resultSQLSearchDate, MYSQLI_ASSOC)) {
                                        $a = $rowSearchDate["id"];
                                        $b = $rowSearchDate["student_number"];
                                        $c = $rowSearchDate["name"];
                                        $d = $rowSearchDate["course"];
                                        $e = $rowSearchDate["time_in"];
                                        $f = $rowSearchDate["time_out"];

                                        echo "
                                            <tr>
                                                <td>$a</td>
                                                <td>$b</td>
                                                <td>$c</td>
                                                <td>$d</td>
                                                <td>$e</td>
                                                <td>$f</td>
                                            </tr>
                                        ";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No data found for the specified date.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Date doesn't exist.</td></tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>

            <div id="paginationControls" class="d-flex justify-content-center align-items-center mt-3" style="display: none;">
                <button id="prevPage" class="btn btn-secondary" disabled>&lt;</button>
                <button id="nextPage" class="btn btn-secondary">&gt;</button>
            </div>
        </div>
    </div>
</section>

</body>
</html>
