<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoverService;

class RoverController extends Controller
{
    public function move(Request $request)
    {
        $validated = $request->validate([
            'x' => 'required|integer|min:0|max:199',
            'y' => 'required|integer|min:0|max:199',
            'direction' => 'required|in:N,S,E,W',
            'commands' => 'required|string',
            'obstacles' => 'array'
        ]);

        $rover = new RoverService(
            $validated['x'], 
            $validated['y'], 
            $validated['direction'], 
            $validated['obstacles'] ?? []
        );

        return response()->json($rover->executeCommands($validated['commands']));
    }
}
