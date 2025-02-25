<?php

namespace Pasha234\Hw42;

class App
{
    public function run(): string
    {
        session_start();

        $redis = $this->makeRedis();

        // Store the FPM ID in the session (for demonstration)
        if (!isset($_SESSION['fpm_id'])) {
            $_SESSION['fpm_id'] = getenv('FPM_ID');
        }

        // Increment a counter in Redis
        $counter = $this->incrCounter($redis);

        return $this->generateHtml($counter);
    }

    protected function generateHtml(int $counter)
    {
        return "<h1>Hello from FPM " . getenv('FPM_ID') . "!</h1>
        <p>Session FPM ID: " . $_SESSION['fpm_id'] . "</p>
        <p>Counter in Redis: " . $counter . "</p>
        <p>Served by: " . gethostname() . "</p>";
    }

    protected function makeRedis(): \Redis
    {
        $redis = new \Redis();
        $redis->connect('redis', 6379);
        return $redis;
    }

    protected function incrCounter(\Redis $redis): int
    {
        $counterKey = 'my_counter';
        return $redis->incr($counterKey);
    }
}