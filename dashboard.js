//Javascript for searching, filtering and sorting
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