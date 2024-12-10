$(document).ready(function() {
    // Registration Form Submission
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();

        // Collect form data
        let formData = {
            fullName: $('#fullName').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            age: $('#age').val(),
            gender: $('#gender').val()
        };

        // AJAX call to PHP backend
        $.ajax({
            type: 'POST',
            url: 'process.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayResult(response.data);
                    clearForm();
                } else {
                    alert('Registration failed: ' + response.message);
                }
            },
            error: function() {
                alert('Error processing registration');
            }
        });
    });

    // Fetch Users Button
    $('#fetchUsersBtn').on('click', function() {
        $.ajax({
            type: 'GET',
            url: 'fetch_users.php',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayUsersModal(response.users);
                } else {
                    alert('Error fetching users: ' + response.message);
                }
            },
            error: function() {
                alert('Error fetching users');
            }
        });
    });

    // Modal Close Button
    $('.close').on('click', function() {
        $('#usersModal').hide();
    });

    // Close modal if clicked outside
    $(window).on('click', function(event) {
        if (event.target.id === 'usersModal') {
            $('#usersModal').hide();
        }
    });

    function displayResult(data) {
        let resultHtml = `
            <h3>Registration Successful</h3>
            <p><strong>Name:</strong> ${data.fullName}</p>
            <p><strong>Email:</strong> ${data.email}</p>
            <p><strong>Phone:</strong> ${data.phone}</p>
            <p><strong>Age:</strong> ${data.age}</p>
            <p><strong>Gender:</strong> ${data.gender}</p>
        `;
        $('#resultContainer').html(resultHtml);
    }

    function displayUsersModal(users) {
        if (users.length === 0) {
            $('#usersList').html('<p>No users registered yet.</p>');
        } else {
            let usersHtml = users.map(user => `
                <div class="user-item">
                    <p><strong>Name:</strong> ${user.fullName}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Phone:</strong> ${user.phone}</p>
                    <p><strong>Age:</strong> ${user.age}</p>
                    <p><strong>Gender:</strong> ${user.gender}</p>
                </div>
            `).join('');
            
            $('#usersList').html(usersHtml);
        }
        
        $('#usersModal').show();
    }

    function clearForm() {
        $('#fullName, #email, #phone, #age').val('');
        $('#gender').prop('selectedIndex', 0);
    }
});
