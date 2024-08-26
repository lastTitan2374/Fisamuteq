document.addEventListener("DOMContentLoaded", function() {
            var sliderItems = document.querySelectorAll(".slider__item");
            
            sliderItems.forEach(function(item) {
                item.addEventListener("click", function() {
                    var targetId = item.getAttribute("data-section");
                    document.getElementById(targetId).scrollIntoView({ behavior: "smooth" });
                });
            });
        });

        // Function to handle dropdown change for viewing details
        function loadAccountDetails() {
            var accountHolder = document.getElementById('view-account-holder').value;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        displayAccountDetails(response);
                    } else {
                        alert('Error fetching data');
                    }
                }
            };
            xhr.open('GET', 'fetch_data.php?account_holder=' + encodeURIComponent(accountHolder), true);
            xhr.send();
        }

        // Function to display account details in table for viewing
        function displayAccountDetails(data) {
            var tableBody = document.querySelector('#account-details tbody');
            tableBody.innerHTML = ''; // Clear previous entries

            data.forEach(function(entry) {
                var row = createTableRow(entry);
                tableBody.appendChild(row);
            });
        }

        // Function to handle dropdown change for editing details
        function loadAccountDetails2() {
            var accountHolder = document.getElementById('edit-account-holder').value;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        displayAccountDetails2(response);
                    } else {
                        alert('Error fetching data');
                    }
                }
            };
            xhr.open('GET', 'fetch_data2.php?account_holder=' + encodeURIComponent(accountHolder), true);
            xhr.send();
        }

        // Function to display account details in table for editing
        function displayAccountDetails2(data) {
            var tableBody = document.querySelector('#account-details2 tbody');
            tableBody.innerHTML = ''; // Clear previous entries

            data.forEach(function(entry) {
                var row = createTableRow(entry, true); // Pass true to enable edit/delete buttons
                tableBody.appendChild(row);
            });
        }

        // Function to create a table row for account details
function createTableRow(entry, withActions) {
    var row = document.createElement('tr');
    row.id = 'entry-' + entry.id; // Set row id based on entry id for deletion

    // Loop through each key in the entry object
    Object.keys(entry).forEach(function(key) {
        if (key !== 'id') { // Exclude ID field from displaying in the table
            var cell = document.createElement('td');
            cell.textContent = entry[key];
            row.appendChild(cell);
        }
    });

    // Add edit and delete buttons if required
    if (withActions) {
        var actionsCell = document.createElement('td');
        var editButton = document.createElement('button');
        editButton.textContent = 'Edit';
        editButton.onclick = function() {
            editEntry(entry); // Pass the whole entry object to edit function
        };
        var deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.onclick = function() {
            deleteEntry(entry.id); // Pass entry ID to delete function
        };
        actionsCell.appendChild(editButton);
        actionsCell.appendChild(deleteButton);
        row.appendChild(actionsCell);
    }

    return row;
}

// Function to edit an entry
function editEntry(entry) {
    // Populate the edit form with entry details
    document.getElementById('edit-entry-id').value = entry.id;
    document.getElementById('edit-account-holder').value = entry.account_holder;
    document.getElementById('edit-bank-name').value = entry.bank_name;
    document.getElementById('edit-deposit-no').value = entry.deposit_no;
    document.getElementById('edit-start-date').value = entry.start_date;
    document.getElementById('edit-end-date').value = entry.end_date;
    document.getElementById('edit-deposit-amount').value = entry.deposit_amount;
    document.getElementById('edit-payout-type').value = entry.payout_type;
    document.getElementById('edit-interest').value = entry.interest;
    document.getElementById('edit-remarks').value = entry.remarks;

    // Show the edit form
    document.getElementById('edit-form-container').style.display = 'block';
}

// Function to delete an entry
function deleteEntry(entryId) {
    if (confirm('Are you sure you want to delete this entry?')) {
        // Perform deletion via AJAX request
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Remove the entry row from the table
                    var entryRow = document.getElementById('entry-' + entryId);
                    entryRow.parentNode.removeChild(entryRow);
                } else {
                    alert('Error deleting entry');
                }
            }
        };
        xhr.open('POST', 'delete_entry.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('entry_id=' + encodeURIComponent(entryId));
    }
}

        // Function to handle form submission for editing
        function submitEditForm() {
            var editForm = document.getElementById('edit-form');
            var formData = new FormData(editForm);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Hide the edit form
                        document.getElementById('edit-form-container').style.display = 'none';

                        // Reload the account details for editing section
                        loadAccountDetails2();
                    } else {
                        alert('Error updating entry');
                    }
                }
            };
            xhr.open('POST', 'update_entry.php', true);
            xhr.send(formData);
        }
        document.addEventListener("DOMContentLoaded", function() {
    // Load the total report when the page is loaded
    loadTotalReport();
});
        // Function to load and display total report
        function loadTotalReport() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        displayTotalReport(response);
                    } else {
                        alert('Error fetching data');
                    }
                }
            };
            xhr.open('GET', 'fetch_total_report.php', true);
            xhr.send();
        }

        // Function to display total report in table
        function displayTotalReport(data) {
            var tableBody = document.querySelector('#total-report-details tbody');
            tableBody.innerHTML = ''; // Clear previous entries

            data.forEach(function(entry) {
                var row = document.createElement('tr');
                Object.keys(entry).forEach(function(key) {
                    var cell = document.createElement('td');
                    cell.textContent = entry[key];
                    row.appendChild(cell);
                });
                tableBody.appendChild(row);
            });
        }
        document.getElementById('hamburger').addEventListener('click', function () {
            document.getElementById('nav-links').classList.toggle('active');
        });
        loadAccountDetails2();