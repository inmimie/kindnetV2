<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'charity_type_id', 'amount_requested', 'status', 'approved_at',
        'applicant_name', 'applicant_ic', 'applicant_dob', 'applicant_gender', 'applicant_marital_status',
        'applicant_address', 'applicant_phone', 'applicant_occupation', 'applicant_email',
        'father_name', 'father_occupation', 'father_income',
        'mother_name', 'mother_occupation', 'mother_income',
        'total_dependents', 'total_income',
        'course_name', 'study_level', 'university_name', 'start_year', 'end_year',
        'account_number', 'bank_name',
        'doc_student_ic', 'doc_student_birth_cert', 'doc_mother_ic', 'doc_father_ic', 'doc_offer_letter'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected function applicantMaritalStatus(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value) => match ($value) {
                'Bujang' => 'Single',
                'Berkahwin' => 'Married',
                'Duda/Janda' => 'Divorced',
                'Balu' => 'Widowed',
                'Lain-lain' => 'Other',
                default => $value,
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function charityType()
    {
        return $this->belongsTo(CharityType::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
