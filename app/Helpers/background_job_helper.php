<?php

if (!function_exists('runBackgroundJob')) {

    function runBackgroundJob($className, $methodName, $params = [], $retryCount = 3, $delay = 0, $priority = 1)
    {



        // Validate class and method
        if (!class_exists($className) || !method_exists($className, $methodName)) {
            Log::error("Invalid class or method: {$className}::{$methodName}");
            return;
        }


        // Build the command string
        $paramsString = implode(' ', array_map('escapeshellarg', $params));
        $command = "php " . base_path('background_job_runner.php') . " {$className} {$methodName} {$paramsString}";

        // Add delay if specified
        if ($delay > 0) {
            $command = "sleep {$delay}; {$command}";
        }

        // Set up background execution for both Unix and Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // For Windows, use 'start' to run the job in the background
            $command = "start /B " . $command;
        } else {
            // For Unix-based systems, use '&' to run the job in the background
            $command .= " > /dev/null 2>&1 &";
        }

        // Execute the command

        $attempts = 0;
        $success = false;
        while ($attempts < $retryCount && !$success) {
            try {
                exec($command);
                $success = true;
                Log::info($className, $methodName, 'success');

            } catch (Exception $e) {
            $attempts++;
            Log::info($className, $methodName, 'failure', $e->getMessage());
            if ($attempts >= $retryCount) {
                break;
            }
            }
        }

        // Log the job details
        Log::info("Background job for {$className}::{$methodName} started with params: " . json_encode($params));
    }

}
