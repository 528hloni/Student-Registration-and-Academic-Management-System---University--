<?php
include('connection.php');
session_start();

//session check: only admin is allowed here
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: login.php");
    exit();
}


try{
    // fetch data to display in table
$sql = "SELECT student_id, name, surname, enrollment_date, course FROM students";
$stmt = $pdo->query($sql);

//store all students in an array
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

//button action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action ==='Register New Student'){
        header('Location: register.php');
        exit();
        

    }

    if ($action ==='Logout'){
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
} catch (Exception $e) {
    // Handle general errors
    echo "Error: " . $e->getMessage();
}






?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="container">

    <h1> Student List & Management Dashboard </h1>
    <br>
    <form method="POST">
    <input type="submit" name="action" value="Register New Student">
    
    <input type="submit" name="action" value="Logout">

     
    <br><br>
    <input type="text" id="search_input" name="search_input" placeholder="Search Student...">
    <select id="filter" name="filter">
            <option value="" >Filter By Course</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Engineering">Engineering</option>
            <option value="Business">Business</option>
            <option value="Medicine">Medicine</option>
            <option value="Law">Law</option>
            <option value="Arts">Arts</option>
            <option value="Science">Science</option>
            <option value="Education">Education</option>
        </select>
    <select id="sort" name="sort">
        <option value="" >Sort</option>
        <option value="name_asc">Name A-Z</option>
        <option value="name_desc">Name Z-A</option>
        <option value="enrolled_asc">Enrolled Date Ascending</option>
        <option value="enrolled_desc">Enrolled Date Descending</option>
        <option value="course_asc">Course A-Z</option>
        <option value="course_desc">Course Z-A</option>
    </select>    
    <br>
    
    <br><br><br>
</form>

<table id="studentsTable" border="1">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Enrollment Date</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        
        <?php foreach ($students as $row): ?>
        <tr>
            <td><?php echo htmlentities($row['student_id']); ?></td>
            <td><?php echo htmlentities($row['name']); ?></td>
            <td><?php echo htmlentities($row['surname']); ?></td>
            <td><?php echo htmlentities($row['enrollment_date']); ?></td>
            <td><?php echo htmlentities($row['course']); ?></td>
            <td>
                <a href="profile.php?student_id=<?= $row['student_id'] ?>">View</a> | 
                <a href="update.php?student_id=<?= $row['student_id'] ?>">Update</a> | 
                
                <a href="delete.php?id=<?php echo $row['student_id']; // attaches the students ID?>"  
                onclick="return confirm('Are you sure you want to delete this student?');">
                Delete</a>

                       
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>


<script> //Javascript for searching, filtering and sorting
const searchInput = document.getElementById('search_input');
const filterSelect = document.getElementById('filter');
const sortSelect = document.getElementById('sort');
const tableBody = document.getElementById('studentsTable').getElementsByTagName('tbody')[0]; // will be working on the rows(<tbody>) not the header

// fucntion for searching, filtering and sorting
function applyFiltersAndSort() {

    const searchValue = searchInput.value.toLowerCase();
    const selectedCourse = filterSelect.value.toLowerCase();
    const sortOption = sortSelect.value; 

    let rows = Array.from(tableBody.getElementsByTagName('tr')); // grab all <tr> rows inside <tbody> and convert the result to a real array so we can use .forEach, .filter, .sort

    
    rows.forEach(row => { //loop over every row
        const name = row.cells[1].textContent.toLowerCase();
        const surname = row.cells[2].textContent.toLowerCase();
        const course = row.cells[4].textContent.toLowerCase();

        const matchesSearch = !searchValue || name.includes(searchValue) || surname.includes(searchValue);
        const matchesFilter = !selectedCourse || course === selectedCourse;

        if (matchesSearch && matchesFilter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Sorting
    rows = rows.filter(row => row.style.display !== 'none'); // only visible rows

    rows.sort((a, b) => {
        let aVal, bVal;

        switch (sortOption) {
            case "name_asc":
                aVal = a.cells[1].textContent.toLowerCase();
                bVal = b.cells[1].textContent.toLowerCase();
                return aVal.localeCompare(bVal);

            case "name_desc":
                aVal = a.cells[1].textContent.toLowerCase();
                bVal = b.cells[1].textContent.toLowerCase();
                return bVal.localeCompare(aVal);

            case "enrolled_asc":
                aVal = new Date(a.cells[3].textContent);
                bVal = new Date(b.cells[3].textContent);
                return aVal - bVal;

            case "enrolled_desc":
                aVal = new Date(a.cells[3].textContent);
                bVal = new Date(b.cells[3].textContent);
                return bVal - aVal;

            case "course_asc":
                aVal = a.cells[4].textContent.toLowerCase();
                bVal = b.cells[4].textContent.toLowerCase();
                return aVal.localeCompare(bVal);

            case "course_desc":
                aVal = a.cells[4].textContent.toLowerCase();
                bVal = b.cells[4].textContent.toLowerCase();
                return bVal.localeCompare(aVal);

            default:
                return 0; 
        }
    });

    
    rows.forEach(row => tableBody.appendChild(row));
}

// attach event listeners
searchInput.addEventListener('keyup', applyFiltersAndSort);
filterSelect.addEventListener('change', applyFiltersAndSort);
sortSelect.addEventListener('change', applyFiltersAndSort);
</script>



    
</body>
</html>








    
         


