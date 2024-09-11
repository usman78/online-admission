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
</style>
<br>


<div class="container">
    <div class="row  my-5">
        <div class="col-md-6 my-5">
            <h2>Online Admission Process</h2>
            <ul>
                <li> Don't have an account? <a href="/register/">Register now</a></li>
                <li>Please make sure to fill the form with valid information.</li>
                <li>Complete the application and submit.</li>
                <li>If you haven't received the email, please check your spam or junk folder.</li>
            </ul>
            <br>
            <h2><a href="https://afmdc.edu.pk/admission-process-3/">Click here to see the detailed steps involved in online admission process.</a></h2>
            <br>
            <h2>Eligibility Criteria</h2>
            <ul>
                <li> Must have passed MDCAT.</li>
                <li> Must score minimum 60% marks in FSc/Equivalent examination.</li>
            </ul>
            <h2>Admission Processing Fee</h2>
            <ul>
                <li>Application fee: Rs. 2000/-</li>
            </ul>
        </div>
        <div class="col-md-6 my-5">
            <h3>Login Now</h3>
         
            <h3>Fresh Candidates can apply here!  <a class="btn btn-primary mx-3 text-white" href="/register">Create an account</a></h3>
            
           
            
            <!-- Login form -->
          
               
            <form id="loginForm">
                @csrf
                <div class="mb-3">
                    <label class="fw-bold" for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control my-2" id="email" name="email" placeholder="Enter email">
                </div>
                <div class="mb-3">
                    <label class="fw-bold" for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" class="form-control my-2" id="password" name="password" placeholder="Enter password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="fw-bold" class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <div class="mt-3">
                    <a href="/password/email" class="text-decoration-none">Forgot Password?</a>
                </div>
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
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            
            var formData = $(this).serialize();
            
            $.ajax({
                url: "{{ route('user.login') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert(response.success || response.message);
                    // Optionally, redirect the user or perform another action
                    window.location.href = '/'; // Example redirection
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        alert(xhr.responseJSON.error);
                        if (xhr.responseJSON.redirect) {
                            // Redirect to email verification page
                            window.location.href = xhr.responseJSON.redirect;
                        }
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>
@endsection