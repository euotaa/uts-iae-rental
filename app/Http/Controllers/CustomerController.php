<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Customer;
use App\Http\Resources\customerResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return new customerResource($customers, 'Customer List', 'success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);
        if ($validator->fails()) {
            return new customerResource(null, 'gagal', $validator->errors());
        }

        $customers = Customer::create($request->all());
            return new customerResource($customers, 'Customer Berhasil Ditambahkan', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customers = Customer::find($id);

        if ($customers) {
            return new customerResource($customers, 'Data Customer Ditemukan', 'success');
        } else {
            return new customerResource(null, 'Data Customer tidak ditemukan', 'error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customers = Customer::find($id);

        if ($customers) {
            $customers->update($request->all());
            return new customerResource($customers, 'Data Customer berhasil diupdate', 'success');
        } else {
            return new customerResource(null, 'Data Customer tidak ditemukan', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customers = Customer::find($id);

        if ($customers) {
            $customers->delete();
            return new customerResource(null, 'Data Customer berhasil dihapus', 'success');
        } else {
            return new customerResource(null, 'Data Customer tidak ditemukan', 'error');
        }
    }
}
