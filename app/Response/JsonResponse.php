<?php
namespace App\Response;

trait JsonResponse
{
	/**
	 * Returns the response object for the given parameters.
	 *
	 * @param string $message
	 * @param string $isSuccess
	 *
	 * @return object The response object
	 */
	protected function createResponse($message, $isSuccess)
	{
		return response()->json([
				'success' => $isSuccess,
				'messsage' => $message
		]);
	}
}
