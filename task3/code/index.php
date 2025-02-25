<?php

session_start();

// Redis Cluster configuration
$redis_cluster_nodes = [
    'redis1:6379',
    'redis2:6379',
    'redis3:6379',
    'redis4:6379',
    'redis5:6379',
    'redis6:6379',
];

try {
    $redis = new \RedisCluster(null, $redis_cluster_nodes, 1.5, 1.5, false, '');
} catch (Exception $e) {
    http_response_code(500);
    echo "Internal Server Error: Could not connect to Redis Cluster. " . $e->getMessage();
    exit;
}

try {
    $counter_key = 'page_load_counter';

    // Increment the counter
    $counter = $redis->incr($counter_key);

    // Get the current counter value (for display)
    //$counter = $redis->get($counter_key); // If you want to get the value after incr

    echo "Page loaded " . $counter . " times.";


    // Session example (optional, showing you can use it for sessions too)
    $_SESSION['page_loads'] = isset($_SESSION['page_loads']) ? $_SESSION['page_loads'] + 1 : 1;
    echo "<br>Session page loads: " . $_SESSION['page_loads'];

    $session_id = session_id();
    $redis->set("session:$session_id", serialize($_SESSION));


} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo "Error: " . $e->getMessage();
}

echo "<h1>Hello from FPM " . getenv('FPM_ID') . "!</h1>";

?>