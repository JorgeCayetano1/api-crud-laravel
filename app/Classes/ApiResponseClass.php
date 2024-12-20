<?php

namespace App\Classes;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function rollback($e, $message = "An error occurred") {
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e, $message = "An error occurred") {
        Log::info($e);
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => $message,
                'data' => $e
            ],
            500
        ));
    }

    public static function sendResponse($result, $message, $code = 200) {
        $response = [
            'success' => true,
            'message' => $message,
        ];
        if (!empty($message)) {
            $response['data'] = $result;
        }
        return response()->json($response, $code);
    }
}
