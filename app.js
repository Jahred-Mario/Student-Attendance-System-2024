document.addEventListener('DOMContentLoaded', () => {
    loadTableData();
});

const rowsPerPage = 15;
let currentPage = 1;

function searchStudent() {
    let bookingCode = document.getElementById('bookingCode').value.trim();

    // Remove any brackets and quotes from the booking code
    bookingCode = bookingCode.replace(/[\[\]"]/g, '');

    fetch(`get_student_info.php?booking_code=${encodeURIComponent(bookingCode)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const student = data.student;
                document.getElementById('name').value = `${student.first_name} ${student.middle_name} ${student.last_name}`;
                document.getElementById('course').value = student.course;
                document.getElementById('studentInfo').style.display = 'block';
                document.querySelector('.submit').disabled = false;

                // Store student number in localStorage for later use
                localStorage.setItem('studentNumber', student.studentnumber);
            } else {
                alert(data.message || 'Student not found!');
                document.querySelector('.submit').disabled = true;
            }
        })
        .catch(error => {
            console.error('Error fetching student info:', error);
            alert('Error fetching student info!');
        });
}

document.getElementById('myForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const name = document.getElementById('name').value;
    const course = document.getElementById('course').value;
    const date = new Date().toLocaleDateString();

    const currentTime = new Date();
    const currentTimeString = currentTime.toLocaleTimeString();
    
    // Calculate the time two hours ahead
    const twoHoursAhead = new Date(currentTime.getTime() + 2 * 60 * 60 * 1000);
    const twoHoursAheadString = twoHoursAhead.toLocaleTimeString();

    const studentNumber = localStorage.getItem('studentNumber'); // Get student number from localStorage

    const tableData = JSON.parse(localStorage.getItem('tableData')) || [];
    const row = [tableData.length + 1, studentNumber, name, course, date, currentTimeString, twoHoursAheadString];
    tableData.push(row);
    localStorage.setItem('tableData', JSON.stringify(tableData));
    loadTableData();
    document.querySelector('.btn-close').click();
});

function loadTableData() {
    const tableData = JSON.parse(localStorage.getItem('tableData')) || [];
    const dataContainer = document.getElementById('data');
    const totalRows = tableData.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = Math.min(startIndex + rowsPerPage, totalRows);

    dataContainer.innerHTML = '';

    for (let i = startIndex; i < endIndex; i++) {
        const row = tableData[i];
        const tr = document.createElement('tr');
        const cells = [
            i + 1,               // No.
            row[1],              // Student Number
            row[2],              // Name
            row[3],              // Course
            row[4],              // Date
            row[5],              // Time In
            row[6],              // Time Out
        ];
        cells.forEach(cell => {
            const td = document.createElement('td');
            td.textContent = cell;
            tr.appendChild(td);
        });
        dataContainer.appendChild(tr);
    }

    updatePaginationControls(totalPages, totalRows);
    updateGeneratePDFButton(totalRows);
}

function updatePaginationControls(totalPages, totalRows) {
    const paginationControls = document.getElementById('paginationControls');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    
    if (totalRows > rowsPerPage) {
        paginationControls.style.display = 'flex';
    } else {
        paginationControls.style.display = 'none';
    }

    prevPageBtn.disabled = (currentPage === 1);
    nextPageBtn.disabled = (currentPage === totalPages);

    prevPageBtn.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            loadTableData();
        }
    };

    nextPageBtn.onclick = () => {
        if (currentPage < totalPages) {
            currentPage++;
            loadTableData();
        }
    };
}

function updateGeneratePDFButton(totalRows) {
    const generatePDFBtn = document.getElementById('generatePDFBtn');
    generatePDFBtn.disabled = totalRows === 0;
}

function generatePDF() {
    const tableData = JSON.parse(localStorage.getItem('tableData')) || [];
    if (tableData.length === 0) {
        alert('No data to generate PDF!');
        return;
    }

    // Send the table data to create_daily_table.php to create a new table and insert data
    fetch('create_daily_table.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'tableData': JSON.stringify(tableData)
        })
    })
    .then(response => response.text())
    .then(message => {
        console.log(message); // Log the result of the table creation

        // Proceed with PDF generation
        const tableDataJson = JSON.stringify(tableData);
        document.getElementById('tableData').value = tableDataJson;
        document.getElementById('pdfForm').submit();

        // Clear local storage and table after generating PDF
        localStorage.removeItem('tableData');
        loadTableData();
    })
    .catch(error => {
        console.error('Error creating table:', error);
        alert('Error creating table. PDF generation aborted.');
    });
}