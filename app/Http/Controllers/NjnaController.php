<?php namespace App\Http\Controllers;

use App\Repositories\NjnaRepository;
use Laravel\Lumen\Routing\Controller as BaseController;

class NjnaController extends BaseController
{
	protected $repository;

	public function __construct(NjnaRepository $repository)
	{
		$this->repository = $repository;
	}

	public function getNjnaRecord($id)
	{
		$record = $this->repository->find($id);

		if (! $record) {
			abort(404);
		}

		return response()->json($record);
	}
}
