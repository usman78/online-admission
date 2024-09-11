<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\AdmissionMst;
use App\Models\AdmissionDtl;
use Illuminate\Support\Facades\Auth;
class Admission extends Controller
{
    //
    public function admissionFormLoad()
    {
        // Ensure the user is logged in and has verified their email
        // Ensure the user is logged in and has verified their email
        if (!Auth::check() || !Auth::user()->is_email_verify) {
            // Redirect to login page if user is not logged in
            return redirect()->route('login')->withErrors('Please login to access this page.');
        }

    

        // Retrieve cities and load the admission form view
        $cities = City::orderBy('cityname')->get();
        $user = Auth::user();
        return view('addmission', compact('cities','user'));
    }

    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $validator = Validator::make($request->all(), [
                // 'name' => 'required|string|min:3|max:50',
                'image' => 'image|mimes:jpeg,jpg|max:2048',
                'father_name' => 'required|string',
                'student_cnic' => 'required|string',
                'father_cnic' => 'required|string',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:M,F',
                'nationality' => 'required|string',
                'religion' => 'required|string|max:25',
                'city' => 'required|string',
                'postal_address' => 'required|string',
                // 'email' => 'required|email|max:255|unique:ONLINE_ADMISSION_MST',
                'stCountryDialCode' => 'required|string|min:3|max:3',
                'frCountryDialCode' => 'required|string|min:3|max:3',
                'stMobilePhone' => 'required|string',
                'frMobilePhone' => 'required|string',
                'accommodation' => 'required|in:Y,N',
                'emg_cont_pname' => 'required|string',
                'emg_cont_mno' => 'required|string',
                'relation' => 'required|string',
                'cnicFront' => 'image|mimes:jpeg,png,jpg|max:2048', // CNIC front image validation
                'cnicBack' => 'image|mimes:jpeg,png,jpg|max:2048', // CNIC back image validation
                'matricDocument' => 'image|mimes:jpeg,png,jpg|max:2048', // Matric document validation
                'fscDocument' => 'image|mimes:jpeg,png,jpg|max:2048', // F.Sc document validation
                // 'mcatDocument' => 'image|mimes:jpeg,png,jpg|max:2048', // MCAT document validation
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return response()->json(['success' => false, 'errors' => $errors]);

            }

            // Process valid data and store in the database
            // Example: Create a new Applicant record
            // Create a folder for the current application number if it doesn't exist
            DB::beginTransaction();
            $app_no = DB::table('ONLINE_ADMISSION_MST')->max('adm_applicant_id') + 1;
            $applicantFolderPath = public_path('applications/' . $app_no);

            // Ensure the target directory exists
            if (!file_exists($applicantFolderPath)) {
                mkdir($applicantFolderPath, 0755, true);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $app_no . '.' . $image->getClientOriginalExtension();

                // Move the uploaded file to the folder
                try {
                    $image->move($applicantFolderPath, $imageName);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'msg' => 'Failed to move uploaded image']);
                }
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'msg' => 'Image not found in request'], 400);
            }
            // Handle CNIC front upload
            if ($request->hasFile('cnicFront')) {
                $cnicFront = $request->file('cnicFront');
                $cnicFrontName = 'cnic_front_' . $app_no . '.' . $cnicFront->getClientOriginalExtension();
                try {
                    $cnicFront->move($applicantFolderPath, $cnicFrontName);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'msg' => 'Failed to move CNIC front image']);
                }
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'msg' => 'CNIC front image not found in request'], 400);
            }

            // Handle CNIC back upload
            if ($request->hasFile('cnicBack')) {
                $cnicBack = $request->file('cnicBack');
                $cnicBackName = 'cnic_back_' . $app_no . '.' . $cnicBack->getClientOriginalExtension();
                try {
                    $cnicBack->move($applicantFolderPath, $cnicBackName);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'msg' => 'Failed to move CNIC back image']);
                }
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'msg' => 'CNIC back image not found in request'], 400);
            }

            // Handle Matric document upload
            if ($request->hasFile('matricDocument')) {
                $matricDocument = $request->file('matricDocument');
                $matricDocumentName = 'matric_document_' . $app_no . '.' . $matricDocument->getClientOriginalExtension();
                try {
                    $matricDocument->move($applicantFolderPath, $matricDocumentName);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'msg' => 'Failed to move Matric document']);
                }
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'msg' => 'Matric document not found in request'], 400);
            }

            // Handle F.Sc document upload
            if ($request->hasFile('fscDocument')) {
                $fscDocument = $request->file('fscDocument');
                $fscDocumentName = 'fsc_document_' . $app_no . '.' . $fscDocument->getClientOriginalExtension();
                try {
                    $fscDocument->move($applicantFolderPath, $fscDocumentName);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'msg' => 'Failed to move F.Sc document']);
                }
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'msg' => 'F.Sc document not found in request'], 400);
            }

            // Handle MCAT document upload (optional, if required)
            if ($request->hasFile('mcatDocument')) {
                $mcatDocument = $request->file('mcatDocument');
                $mcatDocumentName = 'mcat_document_' . $app_no . '.' . $mcatDocument->getClientOriginalExtension();
                try {
                    $mcatDocument->move($applicantFolderPath, $mcatDocumentName);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'msg' => 'Failed to move MCAT document']);
                }
            }
            $admissionMst = new AdmissionMst();
            $admissionMst->adm_applicant_id = $app_no;
            // $admissionMst->name = $request->input('name');
            $admissionMst->father_name = $request->input('father_name');
            $admissionMst->student_cnic = $request->input('student_cnic');
            $admissionMst->father_cnic = $request->input('father_cnic');
            $admissionMst->date_of_birth = $request->input('date_of_birth');
            $admissionMst->gender = $request->input('gender');
            $admissionMst->nationality = $request->input('nationality');
            $admissionMst->religion = $request->input('religion');
            $admissionMst->city = $request->input('city');
            $admissionMst->postal_address = $request->input('postal_address');
            // $admissionMst->email = $request->input('email');
            $admissionMst->st_mobile_phone = $request->input('stCountryDialCode') . $request->input('stMobilePhone');
            $admissionMst->fr_mobile_phone = $request->input('frCountryDialCode') . $request->input('frMobilePhone');
            $admissionMst->accommodation = $request->input('accommodation');
            $admissionMst->emg_cont_pname = $request->input('emg_cont_pname');
            $admissionMst->emg_cont_mno = $request->input('emg_cont_mno');
            $admissionMst->relation = $request->input('relation');
            $admissionMst->image_name = $imageName;

            $admissionMst->save();

            $maxIdDtl = DB::table('ONLINE_ADMISSION_DTL')->max('dtl_id') + 1;
            $qualifications = json_decode($request->input('qualifications'), true);
            foreach ($qualifications as $qualification) {
                $admissionDtl = new AdmissionDtl();
                $admissionDtl->dtl_id = $maxIdDtl++;
                $admissionDtl->adm_applicant_id = $app_no;
                $admissionDtl->sr_no = $qualification['sr_no'];
                $admissionDtl->qualification = $qualification['qualification'];
                $admissionDtl->roll_no = $qualification['roll_no'];
                $admissionDtl->institution = $qualification['institute'];
                $admissionDtl->obt_marks = $qualification['obt_marks'];
                $admissionDtl->total_marks = $qualification['total_marks'];
                // Add other fields from the qualifications array to AdmissionDtl model
                // Build the edu_document column value
                $eduDocument = "";

                // Add matric_document if available
                if ($qualification['sr_no'] == 1) {
                    $eduDocument = $matricDocumentName;
                }

                // Add fsc_document if available
                if ($qualification['sr_no'] == 2) {
                    $eduDocument = $fscDocumentName;
                }

                // Add mcat_document if available
                // if ($qualification['sr_no'] == 3) {
                //     $eduDocument = $mcatDocumentName;
                // }

                // Join the documents with a separator (e.g., comma)
                $admissionDtl->edu_document = $eduDocument;
                $admissionDtl->save();
            }

            DB::commit();

            return response()->json(['success' => true, 'msg' => 'Form submitted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);

        }
    }
}
