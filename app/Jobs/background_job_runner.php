<?php

// Include the Composer autoloader to make Laravel classes and helpers available
require_once __DIR__ . '../../../vendor/autoload.php';

// Bootstrap the Laravel application to initialize the environment and helpers
require_once __DIR__ . '../../../bootstrap/app.php';
// background_job_runner.php

if ($argc < 3) {
    echo "Usage: php background_job_runner.php <ClassName> <MethodName> <Parameters...>\n";
    exit(1);
}

$className = $argv[1];
$methodName = $argv[2];
$params = array_slice($argv, 3);

$approvedClasses = ['App\\testClass'];

// Validate Class and Method
if (!class_exists($className)) {
    echo "Class {$className} does not exist.\n";
    exit(1);
}

if (!method_exists($className, $methodName)) {
    echo "Method {$methodName} does not exist in {$className}.\n";
    exit(1);
}

if (!in_array($className, $approvedClasses)) {
    echo "Unauthorized class {$className}.\n";
}

// Create an instance of the class and execute the method
try {
    $instance = new $className();
    call_user_func_array([$instance, $methodName], $params);
    logJobExecution($className, $methodName, 'success');
} catch (Exception $e) {
    logJobExecution($className, $methodName, 'failure', $e->getMessage());
}

// Log job execution status
function logJobExecution($className, $methodName, $status, $errorMessage = null)
{

    $logFile = storage_path('/logs/background_jobs.log');
    $timestamp = date('Y-m-d H:i:s');
    $statusMessage = "{$timestamp} - {$className}::{$methodName} - {$status}";

    if ($status === 'failure') {
        $statusMessage .= " - Error: {$errorMessage}";
    }

    file_put_contents($logFile, $statusMessage . PHP_EOL, FILE_APPEND);
}


