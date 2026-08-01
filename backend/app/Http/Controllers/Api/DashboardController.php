<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tenant-scoped summary. Note: no manual company_id filtering needed here —
     * the BelongsToCompany global scope on Branch/User does it automatically.
     */
    public function summary(Request $request): JsonResponse
    {
        return response()->json([
            'company' => $request->user()->company?->only(['id', 'name', 'subscription_plan']),
            'total_branches' => Branch::count(),
            'total_users' => User::count(),
            'generated_at' => now()->toIso8601String(),
        ]);
    }
}
