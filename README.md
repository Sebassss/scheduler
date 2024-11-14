

# Task: Build a Custom Background Job Runner for Laravel

#### Objective: Design and implement a custom system to execute PHP classes as background jobs, independent of Laravel's built-in queue system. The solution should demonstrate scalability, error handling, and ease of use within a Laravel application.

#### Task Description:

### 1)  Design a Background Job Runner System:
Create a PHP script that can run classes or methods in the background, separate from the main Laravel application process. The script should:
Accept a class name, method, and parameters as inputs.
Instantiate the class and execute the specified method with the provided parameters.
Log each job's execution, including its class, method, and status (success or failure).
### 2) Implement a Global Function for Job Execution:
Develop a global helper function called runBackgroundJob in Laravel that:
Executes the class in background
Supports both Windows and Unix-based systems for background execution.
### 3) Feature Requirements:
Error Handling: Implement error handling in the background process to catch exceptions and log errors in a separate log file (e.g., background_jobs_errors.log).
#### Retry Mechanism: Allow for configurable retry attempts if a job fails.
#### Logging: Log each job's status (e.g., running, completed, failed) with timestamps into a log file.

### 4) Security Requirements:
Validate and sanitize class and method names to prevent execution of unauthorized or harmful code.
Ensure that only pre-approved classes can be run in the background for security reasons.

### 5) Documentation and Usage:
Write clear documentation explaining how the runBackgroundJob function works and how to use it within a Laravel application.
Include examples of how to run different classes and methods as background jobs.
Document how to configure retry attempts, delays, and job priorities.




###  Advanced Requirements (Optional):

### 6) Implement a Web-Based Dashboard:
Create a simple Laravel web interface to display active background jobs, their status, and any errors.
The dashboard should allow an admin to view job logs, see the retry count, and cancel running jobs if needed.
#### Job Delays: Support the ability to delay the execution of a job by a specified number of seconds.

#### Job Priority: Implement a basic system to prioritize jobs. Higher priority jobs should run before lower-priority ones.
## Authors

- [@Sebassss](https://github.com/Sebassss)


## Documentation



### PHP Script to Execute Classes and Methods in Background:
background_job_runner.php created into app/Jobs that accepts the class name, method, and parameters, and then runs the specified job in the background, 
this script takes in arguments: class name, method name, and any parameters required by the method.

#### It validates if the class and method exist.
#### It executes the method in the background.
#### It logs each job’s execution status (success/failure) with timestamps into a log file


### Global Function for Background Job Execution:
A global helper function runBackgroundJob in Laravel to execute the job in the background, supporting both Windows and Unix-based systems, its created in app/Helpers/background_job_helper.php

The function takes the class, method, parameters, retry count, delay, and priority.
#### It validates the class and method before forming a shell command to execute the background job.
#### It supports both Windows and Unix systems for background execution.
#### Logs the job execution attempt.

The function will attempt to execute the background job up to retryCount times if an exception occurs.
Each retry attempt is logged, and on failure, the error message is stored in the log file.

To prevent unauthorized code execution i validate the class and method names. and check if exist in permitted array.


# RUN 



## Documentation and Usage:
### Usage:
Define classes and methods.
Use runBackgroundJob in controllers to execute jobs in the background.

runBackgroundJob('App\\Jobs\\MyJob', 'executeMethod', ['param1', 'param2'], 3, 10);

### Configuration:
Retry attempts: Configurable by $retryCount parameter.
Delay: Configurable by $delay parameter.
Priority: Can be adjusted using priority values.
This solution allows for scalable background job execution within a Laravel application. It incorporates job retries, logging, error handling, and security features while providing a basic web interface for job monitoring.

## Note
Since I based myself on simple txt files for logs, not on a database, I did not take the trouble to generate the dashboard or any view that shows the status of these files... definitely a serious improvement in parallel to dumping data to the logs, insert into a table eg: jobs and through a dashboard be able to know its status, time in which it ran and how many retries it had... and be able to trigger it, or delete it among others...
## Usage/Examples

### from laravel 
```php
///Define classes and methods. Use runBackgroundJob in controllers to execute jobs in the background.

runBackgroundJob('App\Jobs\TheJob', 'example', ['param1', 'param2'], 3, 10);

```

### with artisan 
```php
php artisan run:job TheJob example param1 param2
````

### from CLI

```php
///windows or linux shell;

php ./app/Jobs/background_job_runner.php App\testClass  scan4Files  param1 param2
```


# LOGS




## Screenshots


### logs 
![log dump](https://raw.githubusercontent.com/Sebassss/scheduler/refs/heads/master/4.png)

### Response if unauthorized class
![Check auth](https://raw.githubusercontent.com/Sebassss/scheduler/refs/heads/master/3.png)

### Response if class doesn't exists
![Check class](https://raw.githubusercontent.com/Sebassss/scheduler/refs/heads/master/2.png)

### Response if method doesn't exists
![Check method](https://raw.githubusercontent.com/Sebassss/scheduler/refs/heads/master/1.png)

