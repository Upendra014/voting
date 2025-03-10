<?php
session_start();
include('db_connect.php');

// Ensure only students can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: login.php");
    exit();
}
?>

<html>

    <head>
        <title>Polling Page</title>
    </head>
    

    <body>

        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <h2>Select Your Favorite Events</h2>

        <form id="voteForm">
            <div id="eventList" class="event-container">
                <!-- Events will be loaded here dynamically -->
            </div>
            <br>
            <button type="submit">Submit Vote</button>
        </form>

        <p id="voteMessage"></p>

        <script>
            // Fetch and display events dynamically
            function loadEvents() {
                fetch("fetch_results.php")
                    .then(response => response.json())
                    .then(data => {
                        let eventList = document.getElementById("eventList");
                        eventList.innerHTML = "";

                        data.forEach(event => {
                            let eventDiv = document.createElement("div");

                            let checkbox = document.createElement("input");
                            checkbox.type = "checkbox";
                            checkbox.name = "events[]";
                            checkbox.value = event.event_name;
                            checkbox.classList.add("event-checkbox");

                            let label = document.createElement("label");
                            label.textContent = event.event_name;
                            label.title = `Description: ${event.event_description} | Votes: ${event.votes}`; // Show description & votes on hover

                            eventDiv.appendChild(checkbox);
                            eventDiv.appendChild(label);
                            eventList.appendChild(eventDiv);
                        });
                    });
            }

            // Submit votes via AJAX
            document.getElementById("voteForm").addEventListener("submit", function(event) {
                event.preventDefault();

                let selectedEvents = document.querySelectorAll(".event-checkbox:checked");

                if (selectedEvents.length < 1 || selectedEvents.length > 10) {
                    alert("Invalid voting process! You must select between 1 and 10 events.");
                    return;
                }

                let selectedValues = [];
                selectedEvents.forEach(cb => selectedValues.push(cb.value));

                fetch("vote.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            events: selectedValues
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("voteMessage").textContent = data.message;
                        loadEvents(); // Refresh the event list with updated votes
                    });
            });

            window.onload = loadEvents;
        </script>
                <style>
            body {
                background-image: url('background2.jpeg');
                font-family: Arial, sans-serif;
                text-align: center;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                height: 100vh;
                width: 100vw;
            }

            .event-container {
                width: 50%;
                margin: auto;
                text-align: left;
            }

            .event-container label:hover {
                cursor: pointer;
                text-decoration: underline;
            }
        </style>

    </body>

</html>