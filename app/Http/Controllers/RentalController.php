<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Carbon;
use App\Models\Reental;
use App\Http\Resources\rentalResource;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rental = Rental::all();
        return new rentalResource($rental, 'All Customer List', 'success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'   => 'required|integer',
            'device_id'  => 'required|integer',
            'start_date'  => 'required|date',
        ]);
    
        if ($validator->fails()) {
            return new RentalResource(null, 'Validasi gagal', $validator->errors());
        }
    
        // Get data dari User Service
        $customerResponse = Http::get("http://localhost:8001/api/customers/{$request->device_id}");
        if (!$customerResponse->ok() || !$customerResponse['data']) {
            return new RentalResource(null, 'Customer tidak ditemukan', 'error');
        }
        $customer = $customerResponse['data'];
    
        // Get data dari Package Service
        $deviceResponse = Http::get("http://localhost:8002/api/devices/{$request->device_id}");
        if (!$deviceResponse->ok() || !$deviceResponse['data']) {
            return new RentalResource(null, 'Device tidak ditemukan', 'error');
        }
        $device = $deviceResponse['data'];
    
        // Hitung durasi & tanggal selesai
        $startDate = Carbon::parse($request->start_date);
        $endDate = $startDate->copy()->addMonths($device['duration']);
    
        // Hitung harga total (jika ada price)
        $totalPrice = $device['duration'] * ($device['monthly_price'] ?? 0);
    
        $rental = Rental::create([
            'customer_id'    => $request->customer_id,
            'device_id'   => $request->device_id,
            'name'         => $customer['name'],
            'device_name'  => $device['device_name'] ?? 'Unknown Device',
            'duration'     => $device['duration'],
            'start_date'   => $startDate,
            'end_date'     => $endDate,
            'total_price'  => $totalPrice,
            'status'       => 'Aktif',
        ]);
    
        return new RentalResource($rental, 'Rental berhasil dibuat', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rental = Rental::find($id);

        if ($rental) {
            return new rentalResource($rental, 'Data Ditemukan', 'success');
        } else {
            return new rentalResource(null, 'Data Tidak Ditemukan', 'success');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
