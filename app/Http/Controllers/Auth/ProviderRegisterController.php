<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProviderRegisterController extends Controller
{
    public function show()
    {
        return view('auth.provider-register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|string|in:male,female,other,prefer_not_to_say',
            'nationality' => 'nullable|string|max:100',

            // Business Information
            'business_name' => 'required|string|max:255',
            'years_in_business' => 'nullable|string',
            'business_registration_number' => 'nullable|string|max:100',
            'tax_identification_number' => 'nullable|string|max:100',
            'business_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state_province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',

            // Services & Expertise
            'services_description' => 'required|string|max:2000',
            'target_age_groups' => 'nullable|array',
            'specializations' => 'nullable|string|max:1000',
            'languages_spoken' => 'nullable|string|max:200',
            'service_areas' => 'nullable|string|max:200',

            // Professional Credentials (for professional providers)
            'education_background' => 'nullable|string|max:1000',
            'professional_licenses' => 'nullable|string|max:1000',
            'certifications' => 'nullable|string|max:1000',
            'years_of_experience' => 'nullable|string',

            // Provider Type
            'provider_type' => 'required|string|in:provider-professional,provider-bonding',

            // Account Security
            'password' => 'required|string|min:8|confirmed',

            // Agreements
            'terms_conditions' => 'required|accepted',
            'privacy_policy' => 'required|accepted',
            'marketing_consent' => 'nullable|boolean',

            // Document Uploads
            'business_registration_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'tax_clearance_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'professional_license_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'insurance_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'police_clearance_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'additional_docs.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle file uploads
        $uploadedFiles = [];
        $fileFields = [
            'business_registration_doc',
            'tax_clearance_doc',
            'professional_license_doc',
            'insurance_doc',
            'police_clearance_doc'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('provider_documents', $filename, 'public');
                $uploadedFiles[$field] = $path;
            }
        }

        // Handle multiple additional documents
        if ($request->hasFile('additional_docs')) {
            $additionalDocs = [];
            foreach ($request->file('additional_docs') as $index => $file) {
                $filename = time() . '_additional_' . $index . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('provider_documents/additional', $filename, 'public');
                $additionalDocs[] = $path;
            }
            $uploadedFiles['additional_docs'] = $additionalDocs;
        }

        // Create user record with proper role assignment
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->provider_type, // This should be 'provider-professional' or 'provider-bonding'
            'department' => 'Provider',
            'status' => 'pending_approval',

            // Store complete provider data
            'provider_data' => json_encode([
                'personal_info' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'nationality' => $request->nationality,
                ],
                'business_info' => [
                    'business_name' => $request->business_name,
                    'years_in_business' => $request->years_in_business,
                    'business_registration_number' => $request->business_registration_number,
                    'tax_identification_number' => $request->tax_identification_number,
                    'business_address' => $request->business_address,
                    'city' => $request->city,
                    'state_province' => $request->state_province,
                    'postal_code' => $request->postal_code,
                ],
                'services_info' => [
                    'services_description' => $request->services_description,
                    'target_age_groups' => $request->target_age_groups ?? [],
                    'specializations' => $request->specializations,
                    'languages_spoken' => $request->languages_spoken,
                    'service_areas' => $request->service_areas,
                ],
                'professional_info' => [
                    'education_background' => $request->education_background,
                    'professional_licenses' => $request->professional_licenses,
                    'certifications' => $request->certifications,
                    'years_of_experience' => $request->years_of_experience,
                ],
                'uploaded_documents' => $uploadedFiles ?? [],
                'marketing_consent' => $request->boolean('marketing_consent'),
                'application_date' => now(),
                'provider_type' => $request->provider_type // Ensure this is stored
            ])
        ]);

        // Send notification emails to admin for approval
        // $this->sendApprovalNotification($user);

        // Redirect with success message and guidance
        return redirect()->route('login')->with('status',
            'Provider application submitted successfully! Once approved, you will be redirected to your ' .
            ($request->provider_type === 'provider-professional' ? 'Professional Services' : 'Bonding Activities') .
            ' dashboard. You will receive an email within 24-48 hours. Thank you for joining Infanect!'
        );
    }
}
