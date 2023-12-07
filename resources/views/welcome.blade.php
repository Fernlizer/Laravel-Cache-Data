<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Cache Tutorial</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h1,
        h2 {
            color: #333;
        }

        p {
            color: #555;
        }

        pre {
            background-color: #f4f4f4;
            padding: 10px;
            overflow: auto;
        }

        code {
            font-family: 'Courier New', monospace;
            color: #333;
        }

        ol {
            list-style-type: decimal;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <h1>Laravel 10 Cache Tutorial</h1>

    <p>This tutorial will guide you through the process of implementing caching in a Laravel application.</p>

    <h2>Step 1: Create Event</h2>
    <ol>
        <li>Create Model and controller
            <code>php artisan make:model Agents -c</code>
        </li>

        <li>this is exemple code in model
            <pre>
            <code>
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
            </code>
        </pre>
        </li>
        <li>
            this is exemple code in controller
            <pre>
                <code>
                    public function all(Request $request)
                        {
                        $key = 'agents';
                        
                        // Check if the cache exists
                        if (Cache::has($key)) {
                        // Get the cached data
                        $cachedData = Cache::get($key);
                        
                        // Check if the data is still valid (within a certain time window)
                        if (now()->diffInMinutes($cachedData['timestamp']) < 60) { // Modify the response with additional information for cached
                            data $cacheDataResponse=[ 'data'=> $cachedData['data'],
                            'message' => 'Data retrieved from cache.',
                            'timestamp' => $cachedData['timestamp'],
                            'cache_expiration' => now()->addMinutes(60)->toDateTimeString(),
                            ];
                        
                            return response()->json($cacheDataResponse);
                            }
                            }
                        
                            // If the cache doesn't exist or is expired, retrieve data from the database
                            $agents = Agents::all();
                        
                            // Store the data in the cache along with a timestamp
                            $cachedData = [
                            'data' => $agents,
                            'timestamp' => now(),
                            ];
                        
                            Cache::put($key, $cachedData, $minutes = 60);
                        
                            // Fire the AgentChanged event when the agent data changes
                            // event(new AgentChanged());
                            $rawDataResponse = [
                            'data' => $agents,
                            'message' => 'Raw Data',
                            'timestamp' => now(),
                            'cache_expiration' => now(),
                            ];
                            return response()->json($rawDataResponse);
                            }
                            public function update(Request $request)
                            {
                            // Validate input data
                            $request->validate([
                            'agent_id' => 'required|int',
                            'agent_name' => 'required|string|max:255',
                            'agent_nickname' => 'nullable|string|max:255',
                            'agent_size' => 'nullable|string|max:1',
                            'agent_birthdate' => 'nullable|date',
                            'agent_gender' => 'nullable|string|in:M,F',
                            ]);
                        
                            // Find the agent by ID
                            $agent = Agents::find($request->input('agent_id'));
                        
                            if (!$agent) {
                            return response()->json(['error' => 'Agent not found.'], 404);
                            }
                        
                            // Update agent data
                            $agent->agent_name = $request->input('agent_name');
                            $agent->agent_nickname = $request->input('agent_nickname');
                            $agent->agent_size = $request->input('agent_size');
                            $agent->agent_birthdate = $request->input('agent_birthdate');
                            $agent->agent_gender = $request->input('agent_gender');
                        
                            // Save changes to the database
                            $agent->save();
                        
                            // Clear or update the cache
                            Cache::forget('agents');
                            event(new AgentChanged());
                        
                            return response()->json(['message' => 'Agent updated successfully', 'data' => $agent]);
                            }
                </code>
            </pre>
        </li>
    </ol>

    <h2>Step 2: Create Event</h2>
    <ol>
        <li>Create Event for hook: <code>php artisan make:event AgentChanged</code></li>
    </ol>

    <h2>Step 3: Create Listener</h2>
    <ol>
        <li>Create Event for hook: <code>php artisan make:listener AgentChangedListener --event=AgentChanged</code></li>
        <li>
            this is exemple in Listener add <code>Cache::forget('agents');</code>
            <pre>
            <code>
                public function handle(AgentChanged $event): void
                    {
                    Cache::forget('agents');
                    }
            </code>
        </pre>
        </li>
    </ol>

    <p>Now you have successfully implemented caching in your Laravel application. Feel free to customize and extend
        these steps based on your project requirements.</p>

</body>

</html>