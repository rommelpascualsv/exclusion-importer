<?php
namespace App\Response;

trait JsonResponse
{
	/**
	 * Returns the response object for the given parameters.
	 *
	 * @param string $isSuccess
	 * @param string $message
	 * @param object|array $data the message data
	 * @return object The response object
	 */
	protected function createResponse($message, $isSuccess, $data = null)
	{
		return response()->json([
				'success' => $isSuccess,
				'message' => $message,
		        'data' => $data
		]);
	}
}
