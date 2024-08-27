<?php

namespace App\Http\Controllers;

use stdClass;
use App\Http\DTA\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Services\EventService;

class PaymentController extends Controller
{

    public function reset(): Response
    {
        (new Account())->reset();

        return response('OK', 200)->header('Content-Type', 'text/plain');
    }

    public function index(): JsonResponse
    {
        return response()->json(['accounts' => Cache::get('accounts')]);
    }

    public function balance(Request $request): JsonResponse
    {
        $eventService = new EventService();
        $result = $eventService->balance($request->input('account_id'));

        if($result['exists'] !== false) {
            return response()->json(array_values($result['account'])[0]['balance'], 200);
        } else {
            return response()->json(0, 404);
        }
    }

    public function event(Request $request): JsonResponse
    {
        $eventService = new EventService();
        $result = match ($request->input('type')) {
            'deposit' => $eventService->deposit($request->input('destination'), $request->input('amount')),
            'withdraw' => $eventService->withdraw($request->input('origin'), $request->input('amount')),
            'transfer' => $eventService->transfer($request->input('origin'), $request->input('amount'), $request->input('destination')),
            default => ['message'=>'no event type']
        };

        if(array_key_exists('exists', $result)) {
            return response()->json(0 ,404);
        } else {
            return response()->json($result,201);
        }

    }

}
