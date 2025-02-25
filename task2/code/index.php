<?php

session_start();

// Connect to Redis
$redis = new Redis();
$redis->connect('redis', 6379);

// Store the FPM ID in the session (for demonstration)
if (!isset($_SESSION['fpm_id'])) {
    $_SESSION['fpm_id'] = getenv('FPM_ID');
}

// Increment a counter in Redis
$counterKey = 'my_counter';
$counter = $redis->incr($counterKey);

echo "<h1>Hello from FPM " . getenv('FPM_ID') . "!</h1>";
echo "<p>Session FPM ID: " . $_SESSION['fpm_id'] . "</p>";
echo "<p>Counter in Redis: " . $counter . "</p>";
echo "<p>Served by: " . gethostname() . "</p>"; // Shows the container's hostname (nginx1/nginx2)

?>