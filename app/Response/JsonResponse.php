<?php namespace App\Response;

trait JsonResponse
{
    /**
     * Returns the response object for the given parameters.
     *
     * @param string $isSuccess
     * @param string $message
     *
     * @return object The response object
     */
    protected function createResponse($message, $isSuccess)
    {
        return response()->json([
            'success' => $isSuccess,
            'message' => $message
        ]);
    }
}
