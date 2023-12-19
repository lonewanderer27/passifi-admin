import axios from "axios";

let dayjs = require("dayjs");

let menu = document.querySelectorAll(".menu");
let sidebar = document.querySelector(".sidebar");
let mainContent = document.querySelector(".main--content");

menu.forEach((menu) => {
    menu.addEventListener("click", () => {
        sidebar.classList.toggle("active");
        mainContent.classList.toggle("active");
    });
});

// assign onclick listeners on all card element class
let cardTitle = document.querySelectorAll(".card--title");
cardTitle.forEach((card) => {
    card.addEventListener("click", () => {
        // navigate to the event page
        window.location.href = "/event";

        // make the cursor a pointer when hovering on the card
        card.style.cursor = "pointer";
    });
});

// Optional: Handle form submission
document
    .getElementById("eventForm")
    .addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission for now, you can handle it as needed
        console.log("Form submitted");

        // get the form data
        const formData = new FormData(event.target);

        const imageInput = document.getElementById('eventImage');
        const image = imageInput.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            // e.target.result contains the Base64-encoded string
            const base64String = e.target.result;

            // Do something with the base64String, for example, display it
            console.log("Event Avatar as Base64 String:", base64String);

            const avatar = formData.get('avatar');
            const user_id = formData.get("user_id");
            const title = formData.get("title");
            const date = formData.get("date");
            const time = formData.get("time");
            const location = formData.get("location");
            const invite_code = formData.get("invite_code");
            const organizer = formData.get("organizer");
            const organizer_email = formData.get("organizer_email");
            const organizer_approval =
                document.getElementById("organizerApproval").checked;

            // Get all elements with the class name "email"
            const emailInputs = document.getElementsByClassName("email");

            // Convert the HTMLCollection to an array for easier manipulation (if needed)
            const emailArray = Array.from(emailInputs);

            // Create a blank emails array
            let emails = [];

            // Iterate through the array of email inputs
            emailArray.forEach(function (emailInput) {
                const emailAddress = emailInput.textContent;
                emails.push(emailAddress);
                console.log(emailAddress);
            });

            // construct event data
            let eventData = {
                title,
                date: dayjs(date).format("YYYY-MM-DD"),
                time,
                location,
                invite_code,
                organizer,
                organizer_email,
                organizer_approval,
                user_id: user_id,
                avatar: base64String
            };

            // Send a POST request
            axios
                .post("/_api/events", eventData)
                .then((response) => {
                    console.log(response.data);

                    // check if emailArray is empty
                    if (emailArray.length === 0) {
                        alert("Event has been created!");

                        // immediately reload the window
                        return window.location.reload();
                    }

                    // otherwise create guestsData
                    const guestsData = {
                        emails: emails,
                    };

                    // log guestsData
                    console.log(guestsData);

                    // fetch the event id from response.data
                    const eventId = response.data.event.id;

                    // Send a POST request
                    axios
                        .post(`/_api/guests/event/id/${eventId}`, guestsData)
                        .then((response) => {
                            console.log(response.data);

                            alert("Guests to the event has been invited!");

                            // immediately reload the window
                            return window.location.reload();
                        })
                        .catch((error) => {
                            console.error(error.response);

                            // Get the error message from error.response.data.errors
                            const errorMessages = Object.values(
                                error.response.data.errors
                            );
                            const errorMessage = errorMessages.flat().join("\n");

                            // Display the error message to the user
                            alert(errorMessage);
                        });
                })
                .catch((error) => {
                    console.error(error.response);

                    // Get the error message from error.response.data.errors
                    const errorMessages = Object.values(error.response.data.errors);
                    const errorMessage = errorMessages.flat().join("\n");

                    // Display the error message to the user
                    alert(errorMessage);
                });
        }

        reader.readAsDataURL(image)
    });

// Function to approve a guest
export function approveGuest(eventId, guestId, rowId) {
    axios.patch(`/_api/events/${eventId}/guests/${guestId}/approve`)
        .then(response => {
            // Handle the success response here
            console.log('Guest approved successfully');
            // You may want to update the UI or perform other actions on success

            // Example: Hide the row after approval
            document.getElementById(rowId).style.display = 'none';
        })
        .catch(error => {
            // Handle the error response here
            console.error('Error approving guest:', error);
            // You may want to display an error message or perform other actions on error
        });
}

// Function to deny a guest
export function denyGuest(eventId, guestId, rowId) {
    axios.patch(`/_api/events/${eventId}/guests/${guestId}/deny`)
        .then(response => {
            // Handle the success response here
            console.log('Guest denied successfully');
            // You may want to update the UI or perform other actions on success

            // Example: Hide the row after denial
            document.getElementById(rowId).style.display = 'none';
        })
        .catch(error => {
            // Handle the error response here
            console.error('Error denying guest:', error);
            // You may want to display an error message or perform other actions on error
        });
}

function assignApprovalListeners() {
    const approveButtons = document.querySelectorAll('.admit-button');
    const denyButtons = document.querySelectorAll('.deny-button');

    // Assign onclick listener to each approve button
    approveButtons.forEach(approveButton => {
        approveButton.addEventListener('click', function () {
            const eventId = this.getAttribute('data-event-id');
            const guestId = this.getAttribute('data-guest-id');
            const rowId = this.parentElement.parentElement.id;

            approveGuest(eventId, guestId, rowId);
        });
    });

    // Assign onclick listener to each deny button
    denyButtons.forEach(denyButton => {
        denyButton.addEventListener('click', function () {
            const eventId = this.getAttribute('data-event-id');
            const guestId = this.getAttribute('data-guest-id');
            const rowId = this.parentElement.parentElement.id;

            denyGuest(eventId, guestId, rowId);
        });
    });
}

// Call the function to assign listeners
assignApprovalListeners();
