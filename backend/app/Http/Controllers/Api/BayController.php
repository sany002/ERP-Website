<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BayController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Bay::query()->where('is_active', true);

        if ($branchId = $request->query('branch_id')) {
            $query->where('branch_id', $branchId);
        }
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        return response()->json($query->orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
            'type' => 'required|in:service,wash,inspection,paint,diagnostics,other',
        ]);

        $bay = Bay::create($data);

        return response()->json($bay, 201);
    }

    public function update(Request $request, Bay $bay): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:100',
            'status' => 'sometimes|in:available,occupied,maintenance',
            'is_active' => 'sometimes|boolean',
        ]);

        $bay->update($data);

        return response()->json($bay);
    }
}
