<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Application;
use App\Models\CharityType;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ImportSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/kindnet_sample_data.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error("JSON data file not found at: {$jsonPath}");
            return;
        }

        $this->command->info("Reading JSON data...");
        $data = json_decode(file_get_contents($jsonPath), true);
        
        if (!$data || !isset($data['applicants']) || !isset($data['applications'])) {
            $this->command->error("Invalid JSON format in: {$jsonPath}");
            return;
        }

        $applicants = $data['applicants'];
        $applications = $data['applications'];

        // Define mappings for gender and marital status
        $maritalMapping = [
            'Married' => 'Berkahwin',
            'Single' => 'Bujang',
            'Divorced' => 'Duda/Janda',
            'Widowed' => 'Balu',
            'Other' => 'Lain-lain',
        ];

        $genderMapping = [
            'Male' => 'Lelaki',
            'Female' => 'Perempuan',
        ];

        $this->command->info("Checking and seeding Charity Types...");
        $charityTypeMap = [];
        $requiredTypes = [
            'Living Allowance' => 'Living Allowance',
            'Education' => 'Education',
            'Medical Support' => 'Medical Support'
        ];

        foreach ($requiredTypes as $dbName => $displayName) {
            $charityType = CharityType::firstOrCreate(
                ['name' => $dbName],
                [
                    'description' => "Financial assistance for {$displayName}.",
                    'start_date' => now()->subMonths(3),
                    'end_date' => now()->addMonths(3)
                ]
            );
            $charityTypeMap[$dbName] = $charityType->id;
        }

        $userIdMap = [];
        $userObjects = [];

        $this->command->info("Importing Users (Applicants)...");
        DB::beginTransaction();
        try {
            $importedUsersCount = 0;
            foreach ($applicants as $appRow) {
                $email = $appRow['email'];
                $icNumber = $appRow['ic_number'];

                // Check by email or IC number
                $user = User::where('email', $email)
                    ->orWhere('ic_number', $icNumber)
                    ->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $appRow['full_name'],
                        'email' => $email,
                        'phone_number' => $appRow['mobile_phone'],
                        'role' => 'applicant',
                        'password' => Hash::make($appRow['password']),
                        'ic_type' => $appRow['ic_type'],
                        'ic_number' => $icNumber,
                        'date_of_birth' => $appRow['date_of_birth'],
                        'place_of_birth' => $appRow['state_of_birth'],
                        'marital_status' => $maritalMapping[$appRow['marital_status']] ?? $appRow['marital_status'],
                        'race' => $appRow['race'],
                        'religion' => $appRow['religion'],
                        'citizen' => $appRow['citizen'],
                        'address_line1' => $appRow['address_line_1'],
                        'address_line2' => $appRow['address_line_2'],
                        'address_line3' => $appRow['address_line_3'],
                        'city' => $appRow['city'],
                        'postcode' => $appRow['postcode'],
                        'district' => $appRow['district'],
                        'state_nation' => $appRow['state'],
                        'phone_home' => $appRow['home_phone'],
                        'gender' => $genderMapping[$appRow['gender']] ?? $appRow['gender'],
                        'created_at' => $appRow['date_registered'] ? Carbon::parse($appRow['date_registered']) : now(),
                        'updated_at' => $appRow['date_registered'] ? Carbon::parse($appRow['date_registered']) : now(),
                    ]);
                    $importedUsersCount++;
                }

                $userIdMap[$appRow['applicant_id']] = $user->id;
                $userObjects[$appRow['applicant_id']] = $user;
            }
            $this->command->info("Users processed: " . count($applicants) . " (newly created: {$importedUsersCount})");

            $this->command->info("Importing Applications and Payments...");
            $importedAppsCount = 0;
            $importedPaymentsCount = 0;

            foreach ($applications as $appData) {
                $excelAppId = $appData['application_id'];
                $excelApplicantId = $appData['applicant_id'];

                if (!isset($userIdMap[$excelApplicantId])) {
                    $this->command->warn("Skipping Application {$excelAppId}: Applicant ID {$excelApplicantId} not found in parsed users.");
                    continue;
                }

                $userId = $userIdMap[$excelApplicantId];
                $user = $userObjects[$excelApplicantId];

                // Map application type to DB charity type name
                $appType = $appData['application_type'];
                $dbCharityName = match ($appType) {
                    'Medical' => 'Medical Support',
                    'Education' => 'Education',
                    'Living Allowance' => 'Living Allowance',
                    default => $appType
                };

                $charityTypeId = $charityTypeMap[$dbCharityName] ?? null;
                if (!$charityTypeId) {
                    $this->command->error("Charity Type '{$dbCharityName}' could not be matched/created for Application {$excelAppId}.");
                    continue;
                }

                // Parse status
                $rawStatus = $appData['status'];
                $dbStatus = match ($rawStatus) {
                    'Pending' => 'pending',
                    'Under Review' => 'pending',
                    'Approved' => 'approved',
                    'Rejected' => 'rejected',
                    default => strtolower($rawStatus)
                };

                $createdAt = $appData['application_date'] ? Carbon::parse($appData['application_date']) : now();
                $updatedAt = $appData['review_date'] ? Carbon::parse($appData['review_date']) : $createdAt;
                $approvedAt = ($dbStatus === 'approved' && $appData['review_date']) ? Carbon::parse($appData['review_date']) : null;

                // Check if application already exists for this user, charity type, and date to avoid duplicates
                $application = Application::where('user_id', $userId)
                    ->where('charity_type_id', $charityTypeId)
                    ->whereDate('created_at', $createdAt->toDateString())
                    ->first();

                if (!$application) {
                    // Build address block for applicant_address field
                    $addressParts = array_filter([
                        $user->address_line1,
                        $user->address_line2,
                        $user->address_line3,
                        $user->postcode . ' ' . $user->city,
                        $user->state_nation
                    ]);
                    $fullAddress = implode(', ', $addressParts);

                    $application = Application::create([
                        'user_id' => $userId,
                        'charity_type_id' => $charityTypeId,
                        'amount_requested' => null,
                        'status' => $dbStatus,
                        'approved_at' => $approvedAt,
                        'applicant_name' => $user->name,
                        'applicant_ic' => $user->ic_number,
                        'applicant_dob' => $user->date_of_birth,
                        'applicant_gender' => $user->gender,
                        'applicant_marital_status' => $user->marital_status,
                        'applicant_address' => $fullAddress,
                        'applicant_phone' => $user->phone_number,
                        'applicant_occupation' => $appData['occupation'] ?? 'Unemployed',
                        'applicant_email' => $user->email,
                        'father_name' => $appData['father_name'],
                        'father_occupation' => $appData['father_occupation'],
                        'father_income' => $appData['father_income_rm'],
                        'mother_name' => $appData['mother_name'],
                        'mother_occupation' => $appData['mother_occupation'],
                        'mother_income' => $appData['mother_income_rm'],
                        'total_dependents' => $appData['num_dependents'],
                        'total_income' => $appData['total_household_income_rm'],
                        'course_name' => $appData['course_name'],
                        'study_level' => $appData['level_of_study'],
                        'university_name' => $appData['university_name'],
                        'start_year' => $appData['start_year'],
                        'end_year' => $appData['end_year'],
                        'account_number' => $appData['account_number'],
                        'bank_name' => $appData['bank_name'],
                        'doc_student_ic' => ($appData['doc_ic'] === 'Uploaded') ? 'documents/dummy_ic.pdf' : null,
                        'doc_student_birth_cert' => ($appData['doc_birth_cert'] === 'Uploaded') ? 'documents/dummy_birth_cert.pdf' : null,
                        'doc_mother_ic' => ($appData['doc_mother_ic'] === 'Uploaded') ? 'documents/dummy_mother_ic.pdf' : null,
                        'doc_father_ic' => ($appData['doc_father_ic'] === 'Uploaded') ? 'documents/dummy_father_ic.pdf' : null,
                        'doc_offer_letter' => ($appData['doc_ipt_offer_letter'] === 'Uploaded') ? 'documents/dummy_offer_letter.pdf' : null,
                        'doc_salary_slip' => ($appData['doc_salary_slip'] === 'Uploaded') ? 'documents/dummy_salary_slip.pdf' : null,
                        'doc_marriage_cert' => ($appData['doc_marriage_cert'] === 'Uploaded') ? 'documents/dummy_marriage_cert.pdf' : null,
                        'doc_medical_report' => ($appData['doc_medical_report'] === 'Uploaded') ? 'documents/dummy_medical_report.pdf' : null,
                        'doc_pharmacy_quote' => ($appData['doc_price_quotes'] === 'Uploaded') ? 'documents/dummy_pharmacy_quote.pdf' : null,
                        'doc_bank_statement' => ($appData['doc_bank_statement'] === 'Uploaded') ? 'documents/dummy_bank_statement.pdf' : null,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ]);
                    $importedAppsCount++;
                }

                // If approved and has approved amount > 0, generate payment
                $approvedAmt = floatval($appData['amount_approved_rm'] ?? 0);
                if ($dbStatus === 'approved' && $approvedAmt > 0) {
                    if (!$application->payment()->exists()) {
                        Payment::create([
                            'application_id' => $application->id,
                            'amount' => $approvedAmt,
                            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                            'status' => 'completed',
                            'payment_gateway' => 'bank_transfer',
                            'payment_method' => 'Bank Transfer (Manual)',
                            'gateway_reference' => 'REF-' . strtoupper(Str::random(10)),
                            'notes' => $appData['remarks'] ?? 'Imported approved application payment.',
                            'created_at' => $updatedAt,
                            'updated_at' => $updatedAt,
                        ]);
                        $importedPaymentsCount++;
                    }
                }
            }

            DB::commit();
            $this->command->info("Applications processed: " . count($applications) . " (newly created: {$importedAppsCount})");
            $this->command->info("Payments created: {$importedPaymentsCount}");
            $this->command->info("Data import completed successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error seeding database: " . $e->getMessage());
            throw $e;
        }
    }
}
