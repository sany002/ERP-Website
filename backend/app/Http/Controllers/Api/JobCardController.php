<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobCardRequest;
use App\Models\JobCard;
use App\Repositories\JobCardRepository;
use App\Services\JobCardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobCardController extends Controller
{
    public function __construct(
        private JobCardRepository $repository,
        private JobCardService $service
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->repository->paginate($request));
    }

    public function store(JobCardRequest $request): JsonResponse
    {
        $job = $this->service->create($request->validated(), $request->user()->id);

        return response()->json($job, 201);
    }

    public function show(JobCard $jobCard): JsonResponse
    {
        return response()->json($this->repository->find($jobCard->id));
    }

    public function update(JobCardRequest $request, JobCard $jobCard): JsonResponse
    {
        $jobCard->update($request->safe()->except('items'));

        return response()->json($jobCard->fresh(['items', 'vehicle', 'customer', 'bay']));
    }

    public function updateStatus(Request $request, JobCard $jobCard): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', JobCard::STATUSES),
        ]);

        $job = $this->service->transitionStatus($jobCard, $data['status'], $request->user()->id);

        return response()->json($job);
    }

    public function addItem(Request $request, JobCard $jobCard): JsonResponse
    {
        $data = $request->validate([
            'type' => 'required|in:labor,part',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'mechanic_id' => 'nullable|exists:users,id',
        ]);

        $job = $this->service->addItem($jobCard, $data);

        return response()->json($job, 201);
    }

    public function removeItem(JobCard $jobCard, int $itemId): JsonResponse
    {
        $jobCard->items()->whereKey($itemId)->firstOrFail()->delete();

        return response()->json($jobCard->fresh(['items']));
    }

    public function destroy(JobCard $jobCard): JsonResponse
    {
        $jobCard->delete();

        return response()->json(['message' => 'Job card deleted.']);
    }
}
