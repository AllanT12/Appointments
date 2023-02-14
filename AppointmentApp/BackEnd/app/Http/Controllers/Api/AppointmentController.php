<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $userId = auth()->user()->getAuthIdentifier();
        $appointments = Appointment::query()->where('client_id', $userId)->orderBy('id', 'desc')->paginate(10);
        return AppointmentResource::collection($appointments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAppointmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAppointmentRequest $request)
    {

        $data = $request->validated();

        $appointment = Appointment::query()->where('consultant_id','=',$data['consultant_id'])->orderBy('date','desc')->first();
        $newAppointmentStart = Carbon::parse($data['date']);

        // verific daca consultantul are deja o programare
        $lastAppointmentEnd = $newAppointmentStart->subHours(1)->subMinutes(30);
        $existingAppointment = $appointment->where('date', '>=', $lastAppointmentEnd)->first();
        if ($existingAppointment) {
            return response(['message' => 'The consultant has another appointment 1 and a half hours before.'], 421);
        }

        $appointment = Appointment::create($data);

        return response(new AppointmentResource($appointment) , 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return AppointmentResource
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAppointmentRequest  $request
     * @param  \App\Models\Appointment  $appointment
     * @return AppointmentResource
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {error_log('Some message here.');
        $data = $request->validated();
        $appointment->update($data);
        return new AppointmentResource($appointment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {error_log('Some message here.');
        $appointment->delete();
        return response("",204);
    }
}
