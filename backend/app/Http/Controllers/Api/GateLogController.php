<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GateLogRequest;
use App\Models\GateLog;
use App\Services\GateLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GateLogController extends Controller
{
    public function __construct(private GateLogService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = GateLog::with(['vehicle:id,registration_number', 'gateOperator:id,name', 'jobCard:id,job_number']);

        if ($direction = $request->query('direction')) {
            $query->where('direction', $direction);
        }
        if ($branchId = $request->query('branch_id')) {
            $query->where('branch_id', $branchId);
        }
        if ($date = $request->query('date')) {
            $query->whereDate('logged_at', $date);
        }

        return response()->json($query->latest('logged_at')->paginate($request->integer('per_page', 30)));
    }

    public function store(GateLogRequest $request): JsonResponse
    {
        $log = $this->service->logEntry($request->validated(), $request->user()->id);

        return response()->json($log->load(['vehicle', 'jobCard']), 201);
    }
}
