
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
            font-size: 20px;
            text-decoration: none;

        }

        .btn-primary {
            background: #8A5796 !important;
            border-color: #8A5796 !important;
        }

        footer {

            width: 100%;
        }

        .readonly {
            cursor: not-allowed;
            /* Change cursor to indicate read-only */
            background-color: #eee;
            /* Set background color to light gray */
            border: 1px solid #ccc;
            /* Optional: Add a border to make it clear it's read-only */
            color: #666;
            /* Optional: Change text color to indicate disabled state */
            pointer-events: none;
            /* Disable interaction with the element */
            user-select: none;
            /* Prevent text selection */
        }
    </style>



    <div class="container">
        <div class="row">
            <div class="col-md-12 p-2 bg-white rounded-3">
                {{ $user }}
                <form class="row bg-light" id="applyOnline" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 p-3 text-center">
                        <img class="img-account-profile rounded-circle mb-2"
                            src="http://110.39.174.204/dist/images/male-profile.png" id="imagePreview"
                            style="width:250px;height:250px">
                        <!-- Profile picture help block-->
                        <div class="small font-italic text-muted mb-2">Upload JPG and no larger than 2 MB</div>
                        <div class="text-danger mb-2"> All fields marked with * are Mandatory</div>
                        <input type="file" class="btn btn-primary" style="width:250px;" title="Upload Profile Picture"
                            name="image" accept="image/*" id="image" onchange="previewImageProfile(event)">
                        <p class="text-danger" id="image_error"></p>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">

                            <label for="Name" class="fw-bold my-2">Name of Applicant <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control readonly"
                                value={{ $user->name }} min="3" max="15" readonly>

                            <span class="text-danger" id="name_error"></span>
                        </div>

                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Father/Guardian Name<span
                                    class="text-danger">*</span></label>
                            <input placeholder="Write Your Father Name" type="text" name="father_name" id="father_name"
                                class="form-control">
                            <span class="text-danger" id="father_name_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Student CNIC <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="CNIC" name="student_cnic"
                                id="student_cnic">
                            <span class="text-danger" id="student_cnic_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Father CNIC <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="CNIC" name="father_cnic"
                                id="father_cnic">
                            <span class="text-danger" id="father_cnic_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Date Of Birth <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" placeholder="Date Of Birth" name="date_of_birth"
                                id="date_of_birth">
                            <span class="text-danger" id="date_of_birth_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Gender <span class="text-danger">*</span></label>
                            <select class="form-control" id="gender" name="gender">
                                <option>Select Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Fe Male</option>
                            </select>
                            <span class="text-danger" id="gender_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Nationality <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Nationality" name="nationality"
                                id="nationality">
                            <span class="text-danger" id="nationality_error"></span>
                        </div>
                    </div>

                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Religion<span class="text-danger">*</span></label>

                            <input placeholder="Write Your Religion" type="text" name="religion" id="religion"
                                class="form-control" maxlength="25">

                            <span class="text-danger" id="religion_error"></span>

                        </div>
                    </div>


                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">City <span class="text-danger">*</span></label>
                            <select name="city" id="city" class="form-control">
                                <option>Select City</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->cityname }}">{{ $city->cityname }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="city_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Postal Address <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="postal_address" class="form-control" id="postal_address"
                                rows="2" placeholder="Write Your postal Adddress Here" />
                            <span class="text-danger" id="postal_address_error"></span>
                        </div>
                    </div>

                    {{-- <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="Name" class="fw-bold my-2">Email <span class="text-danger">*</span></label>
                            <input placeholder="Write Your E-mail" type="email" name="email" id="email"
                                class="form-control" maxlength="25">
                            <span class="text-danger" id="email_error"></span>
                        </div>
                    </div> --}}
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="MobileNumber" class="fw-bold my-2">Student Mobile Number <span
                                    class="text-danger">*</span></label>
                            <div class="form-group">
                                <div style="display: flex;">
                                    <input name="stCountryDialCode" type="text" id="CountryDialCode"
                                        placeholder="+92" size="3" class="form-control" style="width:45px;">
                                    <input name="stMobilePhone" type="tel" id="stMobilePhone"
                                        placeholder="3217654321" maxlength="10" class="form-control">
                                </div>
                                <span class="text-danger" id="stMobilePhone_error"></span>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="MobileNumber" class="fw-bold my-2">Father Mobile Number <span
                                    class="text-danger">*</span></label>
                            <div class="form-group">
                                <div style="display: flex;">
                                    <input name="frCountryDialCode" type="text" id="CountryDialCode"
                                        placeholder="+92" size="3" class="form-control" style="width:45px;">
                                    <input name="frMobilePhone" type="tel" id="frMobilePhone"
                                        placeholder="3217654321" maxlength="10" class="form-control">
                                </div>
                                <span class="text-danger" id="frMobilePhone_error"></span>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="MobileNumber" class="fw-bold my-2">Hostel Accommodation <span
                                    class="text-danger">*</span></label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="accommodationYes"
                                    name="accommodation" value="Y">
                                <label class="form-check-label" for="accommodationYes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="accommodationNo" name="accommodation"
                                    value="N">
                                <label class="form-check-label" for="accommodationNo">No</label>
                            </div>

                            <span class="text-danger" id="accommodation_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">
                            <label for="cnicFront" class="fw-bold my-2">CNIC Front Side (JPEG, max 2MB)</label>
                            <input type="file" class="form-control  btn btn-primary" id="cnicFront" name="cnicFront"
                                accept="image/jpeg" required>
                            <img id="cnicFrontPreview" class="preview-img d-none">
                        </div>
                    </div>
                    <div class="col-md-6 p-3">

                        <!-- CNIC Back Side -->
                        <div class="form-group">
                            <label for="cnicBack" class="fw-bold my-2">CNIC Back Side (JPEG, max 2MB)</label>
                            <input type="file" class="form-control btn btn-primary" id="cnicBack" name="cnicBack"
                                accept="image/jpeg" required>
                            <img id="cnicBackPreview" class="preview-img d-none">
                        </div>

                    </div>
                    <h2>Emergency Contact Information</h2>
                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="Name" class="fw-bold my-2">Contact Person Name <span
                                    class="text-danger">*</span> </label>
                            <input placeholder="Write Your Name" type="text" name="emg_cont_pname"
                                id="emg_cont_pname" class="form-control" min="3" max="15">

                            <span class="text-danger" id="emg_cont_pname_error"></span>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="Name" class="fw-bold my-2">Mobile # <span class="text-danger">*</span>
                            </label>
                            <div style="display: flex;">
                                <input name="CountryDialCode" type="text" id="CountryDialCode" placeholder="+92"
                                    size="3" class="form-control" style="width:45px;">
                                <input placeholder="3217654321" type="text" name="emg_cont_mno" id="emg_cont_mno"
                                    class="form-control" min="3" max="15">
                            </div>
                            <span class="text-danger" id="emg_cont_mno_error"></span>
                        </div>

                    </div>
                    <div class="col-md-6 p-3">
                        <div class="form-group">

                            <label for="Name" class="fw-bold my-2">Relation <span class="text-danger">*</span>
                            </label>
                            <input placeholder="Write Your Name" type="text" name="relation" id="relation"
                                class="form-control" min="3" max="15">

                            <span class="text-danger" id="relation_error"></span>
                        </div>

                    </div>

                    <h2>Educational Information</h2>

                    <div class="container mt-4">
                        <div class="mb-3">
                            <label for="degreeSelect" class="form-label">Degree</label>
                            <select id="degreeSelect" class="form-select">
                                <option value="">Select Degree</option>
                                <option value="matric">Matric</option>
                                <option value="fsc">F.Sc</option>
                                
                            </select>
                            <button id="addRow" class="btn btn-primary mt-2">Add Row</button>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr#</th>
                                    <th>Qualification</th>
                                    <th>Roll #</th>
                                    <th>Institution</th>
                                    <th>Obt. Marks</th>
                                    <th>Total Marks</th>
                                    <th>Percentage (%)</th>
                                    <th>Documents</th>
                                </tr>
                            </thead>
                            <tbody class="qualifications">
                                <!-- Rows will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>

                </form>
                <div class="text-end mt-4">
                    <input class="btn px-4 py-3 btn-outline-dark" type="submit" value="Sumbit">
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function previewImageProfile(event) {
            var file = event.target.files[0];
            if (file.type === 'image/jpeg') {
                if (file.size <= 2 * 1024 * 1024) { // 2MB in bytes
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var imagePreview = document.getElementById('imagePreview');

                        imagePreview.src = e.target.result;


                    }
                    reader.readAsDataURL(file);

                } else {
                    alert('File size exceeds 2MB. Please select a smaller file.');
                    event.target.value = null;
                }
            } else {
                alert('Please select a JPG.');
                event.target.value = null;
            }
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(previewId).attr('src', e.target.result);
                    $(previewId).removeClass('d-none'); // Show the preview image
                }

                reader.readAsDataURL(input.files[0]); // Convert to base64 string
            }
        }

        // Call previewImage function when file input changes
        $('#profilePicture').change(function() {
            previewImage(this, '#profilePreview');
        });

        $('#cnicFront').change(function() {
            previewImage(this, '#cnicFrontPreview');
        });

        $('#cnicBack').change(function() {
            previewImage(this, '#cnicBackPreview');
        });

        $('#matricDocument').change(function() {
            previewImage(this, '#matricDocumentPreview');
        });

        $('#fscDocument').change(function() {
            previewImage(this, '#fscDocumentPreview');
        });
        $(document).ready(function() {
            var rowCount = 0;
            var addedDegrees = {}; // Object to keep track of added degrees

            $('#addRow').click(function() {
                var degree = $('#degreeSelect').val();

                if (degree === '') {
                    alert('Please select a degree type.');
                    return;
                }

                // Check if the degree type has already been added
                if (addedDegrees[degree]) {
                    alert('A row for ' + degree + ' degree type has already been added.');
                    return;
                }

                // Proceed to add the row
                rowCount++;
                var qualificationText = degree === 'matric' ? 'Matric or Equivalent Certificate' :
                    'F. Sc or Equivalent Certificate';
                var fileInputId = degree === 'matric' ? 'matricDocument' : 'fscDocument';

                var rowHtml = `<tr>
            <td class="sr-no">${rowCount}</td>
            <td class="qualification">${qualificationText}</td>
            <td class="roll-no" contenteditable="true"></td>
            <td class="institute" contenteditable="true"></td>
            <td class="obt-marks" contenteditable="true"></td>
            <td class="total-marks" contenteditable="true"></td>
            <td class="marks-percentage"></td>
            <td class="edu-documents">
                <input type="file" class="btn btn-primary" style="width:250px;" id="${fileInputId}" name="${fileInputId}" accept="image/jpeg">
            </td>
        </tr>`;

                $('.qualifications').append(rowHtml);
                $('#degreeSelect').val(''); // Reset dropdown
                addedDegrees[degree] = true; // Mark degree as added
            });

            $('#applyOnline').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                // Collect qualifications data
                var qualifications = [];

                $('.qualifications tr').each(function() {
                    var qualificationData = {
                        sr_no: $(this).find('.sr-no').text().trim(),
                        qualification: $(this).find('.qualification').text().trim(),
                        roll_no: $(this).find('.roll-no').text().trim(),
                        institute: $(this).find('.institute').text().trim(),
                        obt_marks: $(this).find('.obt-marks').text().trim(),
                        total_marks: $(this).find('.total-marks').text().trim(),
                        document_path: $(this).find('.edu-documents input[type=file]').val()
                            .trim()
                    };

                    qualifications.push(qualificationData);
                });

                var formData = new FormData();
                formData.append('qualifications', JSON.stringify(qualifications));

                // Collect experiences data if applicable

                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                $.ajax({
                    url: "{{ route('submitAdmissionForm') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data.msg)
                        console.log(data.errors);
                        if (data.success) {
                            console.log("Success response received");
                            alert("Form submitted successfully!");
                            // Optionally redirect after success
                            // window.location.href = 'http://110.39.174.204/careers';
                        } else {
                            alert(data.msg ? data.msg : "Submission failed");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX Error: ", error);
                        alert("An error occurred during submission.");
                    }
                });
            });

        });
    </script>
@endsection
*/
