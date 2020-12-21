<?php

namespace App\Services;

class ResponseService
{
    /**
     * ResponseService success response method.
     *
     * @param  array|string $params
     * @param  integer $code
     * @return object
     */
    public static function success($params = null, $code = 200)
    {
        return response()->json([
            'status' => true,
            'data' => $params
        ], $code);
    }

    /**
     * ResponseService failure response method.
     *
     * @param  array|string $params
     * @param  integer $code
     * @return object
     */
    public static function failure($params = null, $code = 403)
    {
        return response()->json([
            'status' => false,
            'message' => $params
        ], $code);
    }
}
