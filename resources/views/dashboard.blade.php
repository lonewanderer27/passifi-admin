<?php

use Carbon\Carbon;

?>

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/dashboard/index.js') }}" defer></script>
    <title>Dashboard | Passifi</title>
</head>

<body>
@include('partials._header')
<section class="main">
    @include('partials._sidebar')
    <div class="main--content">
        <div class="overview">
            <div class="title">
                <h1 class="section--title">EVENTS</h1>
                <div class="events--right--btns">
                    <select name="date" id="date" class="dropdown">
                        <option value="today">Today</option>
                        <option value="future">Future</option>
                        <option value="past">Past</option>
                        <option value="alltime">All</option>
                    </select>

                    <button class="add" onclick="openModal()"><i class="ri-add-line"></i>Add Event</button>
                    <div class="modal" id="eventModal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal()">&times;</span>
                            <h1>Add Event</h1>
                            <form id="eventForm">
                                <input style="display: none;" type="text" name="user_id"
                                       value="{{ auth()->user()->id }}" required><br><br>

                                <label for="eventName">Event Name:</label>
                                <input type="text" id="eventName" name="title" required><br><br>

                                <label for="eventDate">Date:</label>
                                <input type="date" id="eventDate" name="date" pattern="\d{4}-\d{2}-\d{2}"
                                       required><br><br>

                                <label for="eventTime">Time:</label>
                                <input type="time" id="eventTime" name="time" required><br><br>

                                <label for="eventLocation">Location:</label>
                                <input type="text" id="eventLocation" name="location" required><br><br>

                                <label for="eventGuest">Guests to Passifi:</label>
                                <div class="emails-input">
                                    <input class="tag-input" type="text" id="email-input" placeholder="Add email"
                                           onkeydown="handleEmailInput(event)">
                                    <div class="tags" id="tags-container">
                                    </div>
                                </div>

                                <label for="eventCode">Set Invite Code:</label>
                                <input type="text" id="eventCode" name="invite_code" required><br><br>

                                <label for="eventImage">Set Event Photo:</label>
                                {{--                                <input type="file" id="eventImage" name="avatar" required><br><br>--}}
                                <input type="file" id="eventImage" name="avatar" accept=".png"><br><br>
                                <img id="previewImage" src="#" alt="Image Preview" style="display: none;"/><br>
                                <button type="button" id="removeImage" style="display: none;">Remove Image</button>
                                <br><br>

                                <hr> <!-- Line divider -->

                                <label for="organizerName">Name of Organizer:</label>
                                <input type="text" id="organizerName" name="organizer" required><br><br>

                                <label for="organizerEmail">Organizer Email:</label>
                                <input type="text" id="organizerEmail" name="organizer_email" required><br><br>

                                <div class="toggle">
                                    <label for="organizerApproval">Organizer Approval:</label>
                                    <label class="switch">
                                        <input type="checkbox" name="organizer_approval" id="organizerApproval">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <button type="submit" class="add">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cards">
                @foreach ($events as $event)
                    <div class="card card-1">
                        <div class="card--data">
                            <div class="card--content">
                                <div class=" h-25">
                                    <img src="{{ mix('images/conference-img.jpg') }}" alt=""
                                         class="card--img img-fluid"/>
                                </div>
                                <h1 class="card--title">{{ $event->title }}</h1>
                                <h5>ATTENDEES</h5>
                                <h1>
                                    {{ $event->attendees_count }} / {{ $event->guests_count }}
                                </h1>
                            </div>
                        </div>
                        <div class="card--stats">
                                <span><i class="ri-calendar-2-fill card--icon stat--icon"></i>
                                    {{ Carbon::parse($event->date)->format('Y-m-d')  }}
                                </span>
                            <span><i class="ri-time-fill card--icon stat--icon"></i>{{ $event->time }}</span>
                            <span><i
                                    class="ri-map-pin-2-fill card--icon map--pin"></i>{{ $event->location }}</span>
                        </div>
                        <div class="card--buttons">
                            <button class="scan-button"
                                    onclick="window.location.href = `{{ route('adminScan', ['id' => $event->id]) }}`">
                                Scan
                            </button>
                            <button type="button" class="scan-button" data-bs-toggle="modal"
                                    data-bs-target="#event-qrcode-{{ $event->id }}">
                                Show Invite QR
                            </button>
                            <button type="button" class="scan-button" data-bs-toggle="modal"
                                    data-bs-target="#admit-deny-event-{{ $event->id }}">
                                Admit and Deny
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @foreach($events as $event)
                <div class="modal fade" id="event-qrcode-{{ $event->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content qrcode">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $event->title }} QR Code</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="w-50 h-25">
                                    <img
                                        src="{{ route('event-qrcode', ['id' => $event->id]) }}"
                                        alt="{{ $event->invite_code }}"
                                        class="img-fluid qr-code"/>
                                    <a href="{{ route('event-qrcode', ['id' => $event->id]) }}" download
                                       class="btn btn-primary mt-3">Download Image</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @foreach($events as $event)
                <div class="modal fade" id="admit-deny-event-{{ $event->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content admin-deny-event">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $event->title }}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="admit-deny-table">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>Requester</th>
                                            <th></th>
                                            <th>Email</th>
                                            <th>Settings</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $pendingGuests = $event->guests->where('pending', true);
                                        @endphp
                                        @if($pendingGuests->count() > 0)
                                            @foreach($pendingGuests as $guest)
                                                <tr class="pending-guest" id="pending--guest--{{ $guest->id }}"
                                                    data-guest-id="{{ $guest->id }}" data-event-id="{{ $event->id }}">
                                                    <td class="profile-column">
                                                        <div class="profile-picture"
                                                             style="background-image: url('{{ mix('images/conference-img.jpg') }}');">
                                                            <!-- Empty container for background image -->
                                                        </div>
                                                    </td>
                                                    <td>{{ $guest->user->name }}</td>
                                                    <td>{{ $guest->user->email }}</td>
                                                    <td>
                                                        <button class="admit-button" data-guest-id="{{ $guest->id }}"
                                                                data-event-id="{{ $event->id }}">Admit
                                                        </button>
                                                        <button class="deny-button" data-guest-id="{{ $guest->id }}"
                                                                data-event-id="{{ $event->id }}">Deny
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">No pending guests.</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <button
                                        onclick="location.href = location.href.split('#')[0] + '#admit-deny-event-{{ $event->id }}'; location.reload();"
                                        class="btn btn-primary mt-3">Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
    </div>
</section>
<script>
    document.getElementById('avatar').addEventListener('change', function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('previewImage').style.display = 'block';
            document.getElementById('removeImage').style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    });

    document.getElementById('removeImage').addEventListener('click', function (e) {
        document.getElementById('previewImage').src = '#';
        document.getElementById('previewImage').style.display = 'none';
        document.getElementById('removeImage').style.display = 'none';
        document.getElementById('imageFile').value = '';
    });
</script>
<script>
    // Check if URL contains a hash followed by an id
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); // Get the substring after the hash
        var modal = document.getElementById(hash); // Get the modal with the id
        var bootstrapModal = new bootstrap.Modal(modal, {
            backdrop: 'static',
            keyboard: false
        }); // Create a new Bootstrap modal instance with no animation
        bootstrapModal.show(); // Show the modal

        // Add event listener for the hidden.bs.modal event
        modal.addEventListener('hidden.bs.modal', function () {
            // Remove the hash from the URL when the modal is hidden
            history.replaceState(null, null, ' ');
        });
    }
</script>
<script defer>
    // Create a blank emails array
    let emails = [];

    function openModal() {
        document.getElementById('eventModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('eventModal').style.display = 'none';
    }

    // Function to handle email input and tag creation
    function handleEmailInput(event) {
        const emailInput = document.getElementById('email-input');
        const tagsContainer = document.getElementById('tags-container');

        if (event.key === 'Enter') {
            const email = emailInput.value.trim();
            if (email) {
                // Check if the email already exists in the emails array
                if (emails.includes(email)) {
                    alert('This email has already been added!');
                } else {
                    const tag = createTag(email);
                    tagsContainer.appendChild(tag);
                    emails.push(email); // Add the email to the emails array
                    emailInput.value = '';
                }
            }
            event.preventDefault();
        }

        if (event.key === 'Backspace' && emailInput.value === '') {
            const tags = tagsContainer.querySelectorAll('.tag');
            if (tags.length > 0) {
                tags[tags.length - 1].remove();
                emails.pop(); // Remove the last email from the emails array
            }
        }
    }

    // Function to create a tag element
    function createTag(email) {
        const tag = document.createElement('div');
        tag.classList.add('tag');

        const emailSpan = document.createElement('span');
        emailSpan.classList.add('email');
        emailSpan.textContent = email;

        const removeBtn = document.createElement('span');
        removeBtn.classList.add('remove');
        removeBtn.textContent = 'x';
        removeBtn.addEventListener('click', () => tag.remove());

        tag.appendChild(emailSpan);
        tag.appendChild(removeBtn);

        return tag;
    }

    // Function to open the admit deny modal
    function openAdmitDenyModal(event_id) {
        // console.log("event_id: ", event_id);
        // console.log("events: ", events);
        // let eventData = events[0];
        // console.log("event: ", eventData);

        const overlay = document.getElementById('admitDenyOverlay');
        overlay.style.display = 'block';
    }

    // Function to close the admit deny modal
    function closeAdmitDenyModal() {
        const overlay = document.getElementById('admitDenyOverlay');
        overlay.style.display = 'none';
    }

    // Attach click event listeners to Admit and Deny buttons on each card
    const admitDenyButtons = document.querySelectorAll('.admit-deny-button');

    admitDenyButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const eventId = this.getAttribute('data-event_id');
            openAdmitDenyModal(eventId);
            // Add logic here to populate the table content dynamically based on the card clicked
            // For instance, you can use data attributes on buttons to identify the specific card and update the table accordingly
        });
    });

</script>
</body>

</html>
