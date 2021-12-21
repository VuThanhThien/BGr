<?php

namespace App\Traits;

trait ApiResponser{

    protected function successResponse($data, $message, $code)
	{
		return response()->json([
			'status'=> 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function errorResponse($message, $code, $errors = [])
	{
		return response()->json([
			'status'=>'Error',
			'message' => $message,
			'data' => null,
			'errors' => $errors
		], $code);
	}

}
