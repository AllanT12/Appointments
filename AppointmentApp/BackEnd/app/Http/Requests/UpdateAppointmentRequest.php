<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'client_id' =>'required',
            'description'=>'required',
            'date' =>['required',function ($attribute, $value, $fail) {
                $validDates = [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                ];
                $dayOfWeek = date("l", strtotime($value));
                if (!in_array($dayOfWeek, $validDates)) {
                    $fail('The appointment must be between Monday and Friday.');
                    return false;
                }
                $hour = (int) date("G", strtotime($value));
                $minute = (int) date("i", strtotime($value));
                if (($hour >= 9 && $hour < 13)||($hour == 15 && $minute >= 30)||($hour == 13 && $minute == 0)||($hour >= 16 && $hour < 21)||($hour == 21 && $minute == 0)) {
                    return true;
                }else{$fail('The appointment must be between 9:00 and 13:00 or between 15:30 and 21:00.');return false;}
            }],
            'consultant_id' => 'required'
        ];
    }
}
