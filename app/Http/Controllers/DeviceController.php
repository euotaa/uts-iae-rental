<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DeviceResource;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::all();
        return new deviceResource($devices , 'Devices List', 'success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:devices,name',
            'price' => 'required|integer|min:1000000',
            'type' => 'required|in:Iphone,Digital Camera,Analog Camera,MacBook',
        ]);

        if ($validator->fails()) {
            return new DeviceResource(null, 'Validasi gagal', $validator->errors());
        }

        // Atur durasi berdasarkan tipe device
        $durasiMap = [
            'Iphone' => 12,
            'MacBook' => 12,
            'Digital Camera' => 6,
            'Analog Camera' => 3,
        ];

        $duration = $durasiMap[$request->type];

        $device = Device::create([
            'name' => $request->name,
            'price' => $request->price,
            'type' => $request->type,
            'duration' => $duration,
        ]);

        return new DeviceResource($device, 'Device berhasil ditambahkan', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $device = Device::find($id);

        if ($device) {
            return new DeviceResource($device, 'Device ditemukan', 'success');
        } else {
            return new DeviceResource(null, 'Device tidak ditemukan', 'error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $device = Device::find($id);

        if ($device) {
            $device->update($request->all());
            return new DeviceResource($device, 'Device berhasil diupdate', 'success');
        } else {
            return new DeviceResource(null, 'Device tidak ditemukan', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $device = Device::find($id);

        if ($device) {
            $device->delete();
            return new DeviceResource(null, 'Device berhasil dihapus', 'success');
        } else {
            return new DeviceResource(null, 'Device tidak ditemukan', 'error');
        }
    }

    public function ai(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'budget' => 'required|integer|min:0',
            'type' => 'required|in:Iphone,Digital Camera,Analog Camera,MacBook',
        ]);

        if ($validator->fails()) {
            return new DeviceResource(null, 'Validasi gagal', $validator->errors());
        }

        $budget = $request->budget;
        $type = $request->type;

        // Cari device sesuai tipe dan budget
        $recommended = Device::where('type', $type)
            ->where('price', '<=', $budget)
            ->orderByDesc('duration')
            ->first();

        if (!$recommended) {
            // Coba cari device lain sesuai budget saja
            $recommended = Device::where('price', '<=', $budget)
                ->orderByDesc('duration')
                ->first();

            if ($recommended) {
                return new DeviceResource($recommended, 'Tidak ada device sesuai tipe, tapi kami temukan device lain sesuai budget.', 'partial');
            }

            return new DeviceResource(null, 'Tidak ada device sesuai dengan budget.', 'not found');
        }

        return new DeviceResource($recommended, 'Device rekomendasi ditemukan.', 'success');
    }
}
