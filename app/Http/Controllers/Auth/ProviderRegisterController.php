use App\Models\User;
use App\Models\Role;
use App\Models\Provider;

// ...

public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        // same validation rules you had
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Assign correct role
    $role = Role::where('slug', $request->provider_type)->firstOrFail();

    // Create user
    $user = User::create([
        'name'       => $request->first_name . ' ' . $request->last_name,
        'email'      => $request->email,
        'phone'      => $request->phone,
        'password'   => Hash::make($request->password),
        'role_id'    => $role->id,
        'department' => 'Provider',
        'status'     => 'pending_approval',
    ]);

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
            $filename = uniqid() . '_' . $field . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('provider_documents', $filename, 'public');
            $uploadedFiles[$field] = $path;
        }
    }

    if ($request->hasFile('additional_docs')) {
        $additionalDocs = [];
        foreach ($request->file('additional_docs') as $index => $file) {
            $filename = uniqid() . '_additional_' . $index . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('provider_documents/additional', $filename, 'public');
            $additionalDocs[] = $path;
        }
        $uploadedFiles['additional_docs'] = $additionalDocs;
    }

    // Create provider profile
    Provider::create([
        'user_id' => $user->id,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'date_of_birth' => $request->date_of_birth,
        'gender' => $request->gender,
        'nationality' => $request->nationality,
        'business_name' => $request->business_name,
        'years_in_business' => $request->years_in_business,
        'business_registration_number' => $request->business_registration_number,
        'tax_identification_number' => $request->tax_identification_number,
        'business_address' => $request->business_address,
        'city' => $request->city,
        'state_province' => $request->state_province,
        'postal_code' => $request->postal_code,
        'services_description' => $request->services_description,
        'target_age_groups' => $request->target_age_groups ?? [],
        'specializations' => $request->specializations,
        'languages_spoken' => $request->languages_spoken,
        'service_areas' => $request->service_areas,
        'education_background' => $request->education_background,
        'professional_licenses' => $request->professional_licenses,
        'certifications' => $request->certifications,
        'years_of_experience' => $request->years_of_experience,
        'provider_type' => $request->provider_type,
        'uploaded_documents' => $uploadedFiles,
        'marketing_consent' => $request->boolean('marketing_consent'),
        'application_date' => now(),
    ]);

    return redirect()->route('login')->with('status',
        'Provider application submitted successfully! Once approved, you will be redirected to your ' .
        ($request->provider_type === 'provider-professional' ? 'Professional Services' : 'Bonding Activities') .
        ' dashboard. You will receive an email within 24-48 hours. Thank you for joining Infanect!'
    );
}
