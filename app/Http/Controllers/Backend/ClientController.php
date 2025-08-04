<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function client()
    {
        return view('backend.pages.client');
    }

    public function clientList()
    {
        $data = Client::orderBy('created_at', 'desc')->get();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No Client Found.',
                'data' => [],
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Client List.',
                'data' => $data,
            ], 200);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:clients,email',
            'phone'   => 'required|numeric|digits:11',
            'address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $client = Client::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Client created successfully.',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while saving client.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function clientById(Request $request)
    {
        $data = Client::where('id', $request->id)->first();
        if ($data !== null) {
            return response()->json([
                'status' => 'success',
                'message' => 'Client Found Successfully.',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Client Not Found.',
            ], 200);
        }
    }

    public function destroy($id)
    {
        $account = Client::find($id);

        if (!$account) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $account->delete();

        return response()->json(['message' => 'Client deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:clients,email,' . $id,
            'phone'   => 'required|numeric|digits:11',
            'address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $client = Client::findOrFail($id);
            $client->update($validator->validated());

            return response()->json([
                'status' => true,
                'message' => 'Client updated successfully.',
                'data' => $client
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
