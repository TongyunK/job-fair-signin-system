<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueueRequest;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    protected $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    /**
     * 获取排队号
     */
    public function getNumber(QueueRequest $request): JsonResponse
    {
        try {
            $result = $this->queueService->getQueueNumber($request->validated());
            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 查询排队状态
     */
    public function checkStatus(string $number): JsonResponse
    {
        try {
            $result = $this->queueService->checkQueueStatus($number);
            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * 获取当前排队情况
     */
    public function getCurrentQueue(): JsonResponse
    {
        try {
            $result = $this->queueService->getCurrentQueue();
            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

