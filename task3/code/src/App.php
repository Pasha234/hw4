<?php

namespace Pasha234\Hw43;

use RedisCluster;

class App
{
    public function run(): string
    {
        session_start();

        try {
            $redis = $this->makeRedisCluster();
        } catch (\Exception $e) {
            http_response_code(500);
            return "Internal Server Error: Could not connect to Redis Cluster. " . $e->getMessage();
        }

        try {
            $counter = $this->incrCounter($redis);
            $this->incrSessionPageLoadsCounter($redis);
        } catch (\Exception $e) {
            http_response_code($e->getCode() ?: 500);
            return "Error: " . $e->getMessage();
        }

        return $this->generateHtml($counter);
    }

    protected function makeRedisCluster(): \RedisCluster
    {
        $redis_cluster_nodes = [
            'redis1:6379',
            'redis2:6379',
            'redis3:6379',
            'redis4:6379',
            'redis5:6379',
            'redis6:6379',
        ];

        return new \RedisCluster(null, $redis_cluster_nodes, 1.5, 1.5, false, '');
    }

    protected function generateHtml(int $counter): string
    {
        return "Page loaded {$counter} times.
        <h1>Hello from FPM " . getenv('FPM_ID') . "!</h1>
        <br>Session page loads: {$_SESSION['page_loads']}";
    }

    protected function incrCounter(RedisCluster $redis): int
    {
        return $redis->incr('page_load_counter');
    }

    protected function incrSessionPageLoadsCounter(RedisCluster $redis)
    {
        $_SESSION['page_loads'] = isset($_SESSION['page_loads']) ? $_SESSION['page_loads'] + 1 : 1;

        $session_id = session_id();
        $redis->set("session:$session_id", serialize($_SESSION));
    }
}