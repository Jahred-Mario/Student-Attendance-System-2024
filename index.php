<!doctype html>
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
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userForm">New Attendance <i class="bi bi-plus"></i></button>
        </div>
        <div class="col d-flex justify-content-center">
            <a href="backup.php" class="btn btn-primary">Search Attendance</a>
        </div>
        <div class="col d-flex justify-content-end">
            <form id="pdfForm" action="generate_pdf.php" method="post">
                <input type="hidden" name="tableData" id="tableData">
                <button type="button" class="btn btn-primary" id="generatePDFBtn" onclick="generatePDF()">Generate PDF</button>
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
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
                </thead>
                <tbody id="data"></tbody>
            </table>

            <div id="paginationControls" class="d-flex justify-content-center align-items-center mt-3" style="display: none;">
                <button id="prevPage" class="btn btn-secondary" disabled>&lt;</button>
                <button id="nextPage" class="btn btn-secondary">&gt;</button>
            </div>
        </div>
    </div>
</section>

<!-- Modal Form -->
<div class="modal fade" id="userForm">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Attendance Form</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" id="myForm">
                    <div class="inputField">
                        <div class="mb-3">
                            <label for="bookingCode" class="form-label">Booking Code:</label>
                            <input type="text" class="form-control" id="bookingCode" required>
                            <button type="button" class="btn btn-primary mt-2" onclick="searchStudent()">Search</button>
                        </div>
                        <div id="studentInfo" style="display: none;">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="course" class="form-label">Course:</label>
                                <input type="text" class="form-control" id="course" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary submit" disabled>Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="app.js"></script>
</body>
</html>
