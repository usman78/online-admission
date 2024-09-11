@extends('template/template')

<!-- Page Content -->
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
    .btn-primary {
        background: #8A5796 !important;
        border-color: #8A5796 !important;
    }
    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
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
    <div class="row my-5">
        <div class="col-md-6 my-5">
            <h2>Online Admission Process</h2>
            <ul>
                <li>Don't have an account? <a href="https://online.afmdc.edu.pk/register/">Register now</a></li>
                <li>Please make sure to fill the form with valid information.</li>
                <li>Complete the application and submit.</li>
                <li>If you haven't received the email, please check your spam or junk folder.</li>
            </ul>
            <br>
            <h2><a href="https://afmdc.edu.pk/admission-process-3/">Click here to see the detailed steps involved in online admission process.</a></h2>
            <br>
            <h2>Eligibility Criteria</h2>
            <ul>
                <li>Must have passed MDCAT.</li>
                <li>Must score minimum 60% marks in FSc/Equivalent examination.</li>
            </ul>
            <h2>Admission Processing Fee</h2>
            <ul>
                <li>Application fee: Rs. 2000/-</li>
            </ul>
        </div>
        <div class="col-md-6 my-5">
            <h3>Reset Your Password</h3>
              <!-- Password Reset Form -->
              <form id="passwordResetForm">
                @csrf
                {{-- <div class="mb-3">
                    <label class="fw-bold" for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control my-2" id="email" name="email" placeholder="Enter your email">
                </div> --}}
                    <input type="hidden" name="email" value="{{ request('email') }}">

                <div class="mb-3">
                    <label class="fw-bold" for="token" class="form-label">Reset Token <span class="text-danger">*</span></label>
                    <input type="text" class="form-control my-2" id="token" name="token" placeholder="Enter the reset token">
                </div>
                <div class="mb-3">
                    <label class="fw-bold" for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control my-2" id="password" name="password" placeholder="Enter new password">
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
                    <label class="fw-bold" for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control my-2" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
            <!-- End of Password Reset Form -->
        </div>
    </div>
</div>

<script>
    // document.getElementById('togglePassword').addEventListener('click', function() {
    //     const passwordInput = document.getElementById('password');
    //     const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    //     passwordInput.setAttribute('type', type);
    //     this.querySelector('i').classList.toggle('fa-eye-slash');
    // });

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
        $('#passwordResetForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            
            var formData = $(this).serialize();
            
            $.ajax({
                url: "{{ route('password.update') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert(response.success || response.message);
                    // Optionally, redirect the user or perform another action
                    window.location.href = '/login'; // Example redirection
                },
                error: function(xhr) {
                    console.log('XHR Status:', xhr.status); // Log the HTTP status code
                    console.log('XHR Response:', xhr.responseText); // Log the response text
                    alert(xhr.responseJSON.error || 'An error occurred. Please try again.');
                }
            });
        });
    });
</script>
@endsection
