<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Online Polling System</title>
    <style>
        #resultsTable{
            background-color: rgba(255, 255, 255, 0.8);
        }
        h1{
            color: white;
        }
        body { 
            background-image: url('background.jpeg');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
            height: 100vh;
            width: 100vw;
        }
        .container { margin-top: 20px; }
        button { padding: 10px 20px; margin: 10px; cursor: pointer; }
        .hidden { display: none; }
        table { width: 60%; margin: auto; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        #eventindicator { display: block; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <h1 >Admin Panel</h1>
    <div class="container">
        <button onclick="showForm('addMemberForm')">Add Member</button>
        <button onclick="showForm('addEventForm')">Add Event</button>
        <button onclick="showForm('resultsTable'); fetchResults()">View Voting Results</button>
    </div>

    <!-- Add Member Form -->
    <div id="addMemberForm" class="hidden">
        <h2>Add Member</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Name" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <select name="role" required>
                <option value="Admin">Admin</option>
                <option value="Student">Student</option>
                <option value="Faculty">Faculty</option>
            </select><br><br>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Add Event Form (Only Visible When Clicked) -->
    <div id="addEventForm" class="hidden">
        <h2>Add Event</h2>
        <form id="eventForm">
            <input type="text" id="event_name" name="event_name" placeholder="Event Name" required><br><br>
            <textarea id="event_description" name="event_description" placeholder="Event Description" required></textarea><br><br>
            <button type="submit">Submit</button>
        </form>
        <label id="eventindicator"></label>
    </div>

    <!-- Voting Results Table -->
    <div id="resultsTable" class="hidden">
        <h2>Voting Results</h2>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody id="resultsBody">
                <!-- Results will be loaded here via AJAX -->
            </tbody>
        </table>
    </div>

    <script>
        // Function to toggle forms visibility and ensure only one section is shown at a time
        function showForm(formId) {
            let sections = ['addMemberForm', 'addEventForm', 'resultsTable'];
            sections.forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });
            document.getElementById(formId).classList.remove('hidden');
        }

        // Function to submit event form via AJAX
        document.getElementById('eventForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            let formData = new FormData(this); // Get form data

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "add_event.php", true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('eventindicator').innerText = this.responseText; // Show response
                } else {
                    document.getElementById('eventindicator').innerText = "❌ Error submitting event.";
                }
            };
            xhr.send(formData); // Send data via AJAX
        });

        // Function to fetch voting results
        function fetchResults() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_results.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            const results = JSON.parse(this.responseText);
            let output = '';
            results.forEach(function(result) {
                output += `<tr><td>${result.event_name}</td><td>${result.votes}</td></tr>`;
            });
            document.getElementById('resultsBody').innerHTML = output;
        }
    };
    xhr.send();
}

    </script>
</body>
</html>
