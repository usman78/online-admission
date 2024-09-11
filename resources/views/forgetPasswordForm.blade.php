@extends('template/template')

@section('space-work')
<style>
    /* Include your styles here */
</style>

<div class="container">
    <div class="row my-5">
        <div class="col-md-6 my-5">
            <h2>Forgot Your Password?</h2>

            <!-- Request Reset Token Form -->
            <form id="passwordRequestForm">
                @csrf
                <div class="mb-3">
                    <label class="fw-bold" for="email" class="form-label">Email address <span
                            class="text-danger">*</span></label>
                    <input type="email" class="form-control my-2" id="email" name="email" placeholder="Enter your email"
                        required>
                </div>
                <button type="submit" class="btn btn-primary">Send Reset Token</button>
            </form>
            <!-- End of Request Reset Token Form -->

            <div id="responseMessage" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#passwordRequestForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var formData = $(this).serialize();
            var email = $("#email").val();
            $.ajax({
                url: "{{ route('password.email') }}",
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success === true) {
                        // Display the success message
                        $('#responseMessage').html('<div class="alert alert-success">' + response.msg + '</div>');

                        // Wait for 2 seconds before redirecting
                        setTimeout(function () {
                            window.location.href = '/password/reset/?email='+email;
                        }, 2000); // 2000 milliseconds = 2 seconds
                    }else {
                        $('#responseMessage').html('<div class="alert alert-danger">' + response.error + '</div>');
                    }
                },
                error: function (xhr) {
            var errorHtml = '<div class="alert alert-danger">';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                // Handle custom error messages from Laravel
                errorHtml += '<p>' + xhr.responseJSON.error + '</p>';
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Handle validation errors
                $.each(xhr.responseJSON.errors, function (key, value) {
                    errorHtml += '<p>' + value[0] + '</p>';
                });
            } else {
                // Fallback error message
                errorHtml += '<p>Something went wrong.</p>';
            }
            errorHtml += '</div>';
            $('#responseMessage').html(errorHtml);
        }
            });

        });
    });
</script>
@endsection