<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\Agents;
use App\Events\AgentChanged;


use Illuminate\Http\Request;

class AgentsController extends Controller
{
    public function all(Request $request)
    {
        $key = 'agents';

        // Check if the cache exists
        if (Cache::has($key)) {
            // Get the cached data
            $cachedData = Cache::get($key);

            // Check if the data is still valid (within a certain time window)
            if (now()->diffInMinutes($cachedData['timestamp']) < 60) {
                // Modify the response with additional information for cached data
                $cacheDataResponse = [
                    'data' => $cachedData['data'],
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
            'data'      => $agents,
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
}
