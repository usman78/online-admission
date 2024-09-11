


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
<script>
    function startCountdown(expiryTime) {
        const countdownElement = document.getElementById('countdown');
        const expiryDate = new Date(expiryTime).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = expiryDate - now;

            if (timeLeft <= 0) {
                countdownElement.innerHTML = "Your confirmation code has expired.";
                document.getElementById('confirm_form').style.display = 'none'; // Hide form when expired
                return;
            }

            const minutes = Math.floor(timeLeft / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            countdownElement.innerHTML = `Time remaining: ${minutes}m ${seconds}s`;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000); // Update every second
    }
</script>
<br>


<div class="container">
    <div class="row  my-5">
        <div class="col-md-6">
            <h2>Instructions</h2>
            <ul>
                <li>All fields are required.</li>
                <li>Verify the link on your provided email address to register.</li>
                <li>After verification login using your provided email adress & password.</li>
            </ul>
            <h2>Already Registered ? <a href="/">Login Here!</a></h2>
        </div>
        <div class="col-md-6">
            <h2>Confirm Your Email</h2>
            <!-- Login form -->
          
               
            @if(isset($error))
            <div class="alert alert-danger">{{ $error }}</div>
        @endif
    
        @if(!isset($error))
            <div id="countdown" class="text-danger"></div>
            <form id="confirm_form">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <div>
                    <label for="confirmation_code" style="font-weight: bold;" class="my-2">Confirmation Code</label>
                    <input type="text" id="confirmation_code" name="confirmation_code" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary my-3">Confirm</button>
            </form>
    
          
        @endif
           
            <!-- End of Login form -->
        </div>
    </div>
</div>



     <script>
            @if(isset($expiresAt))
                startCountdown('{{ $expiresAt }}');
            @endif
            $(document).ready(function() {
        $('#confirm_form').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            
            var formData = $(this).serialize();
            
            $.ajax({
                url: "{{ route('confirmation.submit') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert(response.success);
                        if (response.redirect) {
                            // Redirect to home page
                            window.location.href = response.redirect;
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        alert(xhr.responseJSON.error);
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

      
    }); 
        </script>

@endsection