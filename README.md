# Laravel 10 Cache Tutorial

This tutorial will guide you through the process of implementing caching in a Laravel application.

## Step 1: Create Model and Controller

1. Create Model and controller:

    ```bash
    php artisan make:model Agents -c
    ```

2. Example code in the model (`Agents.php`):

    ```php
    <?php

    public $timestamps = false;
    protected $primaryKey = 'agent_id';
    protected $fillable = [
        'agent_name',
        'agent_nickname',
        'agent_size',
        'agent_birthdate',
        'agent_gender',
    ];
    protected $table = 'agents';
    ```

3. Example code in the controller (`AgentsController.php`):

    ```php
    <?php

    public function all(Request $request)
    {
        // Code for retrieving data with caching
    }

    public function update(Request $request)
    {
        // Code for updating data and clearing the cache
    }
    ```

## Step 2: Create Event

1. Create an Event for the hook:

    ```bash
    php artisan make:event AgentChanged
    ```

## Step 3: Create Listener

1. Create a Listener for the hook:

    ```bash
    php artisan make:listener AgentChangedListener --event=AgentChanged
    ```

2. Example code in the listener (`AgentChangedListener.php`):

    ```php
    <?php

    public function handle(AgentChanged $event): void
    {
        Cache::forget('agents');
    }
    ```

Now you have successfully implemented caching in your Laravel application. Feel free to customize and extend these steps based on your project requirements.

For more information, refer to the Laravel documentation.
