@extends('template/template')
        <!-- Page Content  -->
 @section('space-work')


<style>
    ul li {
        font-family: "Roboto", Sans-serif;
        font-weight: bold;
    }
    h2 a {
        color: #8A5796 !important;
    font-family: "Roboto", Sans-serif;
    font-weight: 600;
    line-height: 24px;
    font-size:20px;
    text-decoration: none;
 
    }
    .btn-primary{
        background: #8A5796 !important;
        border-color: #8A5796 !important;
    }
    footer{
        position: fixed;
        bottom: 0;
        width:100%;
    }
    .invalid-feedback {
            display: none;
        }
        .invalid-feedback.active {
            display: block;
        }
</style>
<br>


<div class="container">
    <div class="row  my-5">
        <div class="col-md-6 my-5">
            <h2>Instructions</h2>
            <ul>
                <li>All fields are required.</li>
                <li>Verify the link on your provided email address to register.</li>
                <li>After verification login using your provided email adress & password.</li>
            </ul>
            <h2>Already Registered ? <a href="/">Login Here!</a></h2>
        </div>
        <div class="col-md-6 my-5">
            <h2>Register Now</h2>
            <!-- Login form -->
          
               
                        <form id="register">
                            @csrf
                            <div class="mb-3">
                                 <label class="fw-bold" for="user_name" class="form-label">Username<span class="text-danger">*</span></label>
                                <input type="text" class="form-control my-2" name="user_name" id="user_name" placeholder="Enter Username">
                            </div>
                            <div class="mb-3">
                                 <label class="fw-bold" for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control my-2" name="email" id="email" placeholder="Enter email">
                            </div>
                            <div class="mb-3">
                                 <label class="fw-bold" for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control my-2" name="password" id="password" placeholder="Enter password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="passwordRequirements" class="invalid-feedback">
                                <ul>
                                    <li id="lengthReq" class="text-danger">Must be at least 8 characters long</li>
                                    <li id="lowercaseReq" class="text-danger">Must contain at least one lowercase letter</li>
                                    <li id="uppercaseReq" class="text-danger">Must contain at least one uppercase letter</li>
                                    <li id="digitReq" class="text-danger">Must contain at least one digit</li>
                                    <li id="specialReq" class="text-danger">Must contain at least one special character (@$!%*?&)</li>
                                </ul>
                            </div>
                            <div class="mb-3">
                                 <label class="fw-bold" for="password_confirmation" class="form-label"> Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control my-2" name="password_confirmation" id="password_confirmation" placeholder="Enter password">
                                    <button class="btn btn-outline-secondary" type="button" id="confirmTogglePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                          
                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                   
           
            <!-- End of Login form -->
        </div>
    </div>
</div>


<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    document.getElementById('confirmTogglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_confirmation');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    $(document).ready(function() {
        const passwordField = $('#password');
        const passwordRequirements = $('#passwordRequirements');
        const lengthReq = $('#lengthReq');
        const lowercaseReq = $('#lowercaseReq');
        const uppercaseReq = $('#uppercaseReq');
        const digitReq = $('#digitReq');
        const specialReq = $('#specialReq');

        function validatePassword() {
            const password = passwordField.val();
            const lengthValid = password.length >= 8;
            const lowercaseValid = /[a-z]/.test(password);
            const uppercaseValid = /[A-Z]/.test(password);
            const digitValid = /\d/.test(password);
            const specialValid = /[@$!%*?&]/.test(password);

            lengthReq.toggleClass('text-success', lengthValid);
            lengthReq.toggleClass('text-danger', !lengthValid);
            lowercaseReq.toggleClass('text-success', lowercaseValid);
            lowercaseReq.toggleClass('text-danger', !lowercaseValid);
            uppercaseReq.toggleClass('text-success', uppercaseValid);
            uppercaseReq.toggleClass('text-danger', !uppercaseValid);
            digitReq.toggleClass('text-success', digitValid);
            digitReq.toggleClass('text-danger', !digitValid);
            specialReq.toggleClass('text-success', specialValid);
            specialReq.toggleClass('text-danger', !specialValid);

            passwordRequirements.toggleClass('active', !(lengthValid && lowercaseValid && uppercaseValid && digitValid && specialValid));
        }

        passwordField.on('input', validatePassword);
        $('#register').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting via the browser

        var formData = $(this).serialize(); // Serialize form data
         var givenEmail  = $("#email").val();
        $.ajax({
            url: "{{ route('user.register') }}",
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle success response
                alert(response.message);
                if(response.success === true){
                    window.location.href = "{{ route('confirmation.form') }}?email=" + encodeURIComponent(givenEmail);
                }
                // Optionally, you can redirect the user to another page or perform any other action
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        alert(value[0]); // Alert the first validation error
                        return false; // Exit loop after first error
                    });
                } else if (xhr.status === 500) {
                    // Handle general server error
                    alert('An internal server error occurred. Please try again later.');
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
    });
</script>
@endsection