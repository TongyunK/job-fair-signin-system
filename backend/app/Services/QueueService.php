<?php

namespace App\Services;

use App\Models\Queue;
use App\Models\SignIn;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class QueueService
{
    /**
     * 获取排队号
     */
    public function getQueueNumber(array $userInfo): array
    {
        return DB::transaction(function () use ($userInfo) {
            // 检查是否已经签到
            $existingSignIn = SignIn::where('phone', $userInfo['phone'])->first();
            
            if ($existingSignIn && $existingSignIn->queue_number) {
                // 已存在排队号，返回现有信息
                return $this->formatQueueInfo($existingSignIn);
            }

            // 创建签到记录
            $signIn = SignIn::create([
                'name' => $userInfo['name'],
                'phone' => $userInfo['phone'],
                'email' => $userInfo['email'] ?? null,
                'company' => $userInfo['company'] ?? null,
                'position' => $userInfo['position'] ?? null,
            ]);

            // 生成排队号
            $queueNumber = $this->generateQueueNumber();
            $signIn->queue_number = $queueNumber;
            $signIn->save();

            // 创建排队记录
            $queue = Queue::create([
                'sign_in_id' => $signIn->id,
                'queue_number' => $queueNumber,
                'status' => 'waiting',
            ]);

            // 使用Redis维护排队队列
            Redis::zadd('queue:waiting', time(), $queueNumber);

            return $this->formatQueueInfo($signIn, $queue);
        });
    }

    /**
     * 查询排队状态
     */
    public function checkQueueStatus(string $queueNumber): array
    {
        $queue = Queue::where('queue_number', $queueNumber)->first();
        
        if (!$queue) {
            throw new \Exception('排队号不存在');
        }

        $position = $this->getQueuePosition($queueNumber);
        $estimatedWaitTime = $this->calculateEstimatedWaitTime($position);

        return [
            'number' => $queueNumber,
            'position' => $position,
            'status' => $queue->status,
            'estimatedWaitTime' => $estimatedWaitTime,
        ];
    }

    /**
     * 获取当前排队情况
     */
    public function getCurrentQueue(): array
    {
        $waitingCount = Queue::where('status', 'waiting')->count();
        $processingCount = Queue::where('status', 'processing')->count();
        $completedCount = Queue::where('status', 'completed')->count();

        return [
            'waiting' => $waitingCount,
            'processing' => $processingCount,
            'completed' => $completedCount,
            'total' => $waitingCount + $processingCount + $completedCount,
        ];
    }

    /**
     * 生成排队号
     */
    protected function generateQueueNumber(): string
    {
        $date = date('Ymd');
        $sequence = Redis::incr("queue:sequence:{$date}");
        return sprintf('%s%04d', $date, $sequence);
    }

    /**
     * 获取排队位置
     */
    protected function getQueuePosition(string $queueNumber): int
    {
        $rank = Redis::zrank('queue:waiting', $queueNumber);
        return $rank !== false ? $rank + 1 : 0;
    }

    /**
     * 计算预计等待时间（分钟）
     */
    protected function calculateEstimatedWaitTime(int $position): int
    {
        // 假设平均每人处理时间2分钟
        $averageTimePerPerson = 2;
        return max(0, ($position - 1) * $averageTimePerPerson);
    }

    /**
     * 格式化排队信息
     */
    protected function formatQueueInfo(SignIn $signIn, ?Queue $queue = null): array
    {
        if (!$queue) {
            $queue = Queue::where('queue_number', $signIn->queue_number)->first();
        }

        $position = $queue ? $this->getQueuePosition($signIn->queue_number) : 0;
        $estimatedWaitTime = $queue ? $this->calculateEstimatedWaitTime($position) : 0;

        return [
            'number' => $signIn->queue_number,
            'position' => $position,
            'status' => $queue?->status ?? 'waiting',
            'estimatedWaitTime' => $estimatedWaitTime,
        ];
    }
}

