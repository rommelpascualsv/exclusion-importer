<?php namespace App\Http\Controllers;

use App\Repositories\MICnaRepository;
use Laravel\Lumen\Routing\Controller as BaseController;

class MICnaController extends BaseController
{
	protected $repository;

	public function __construct(MICnaRepository $repository)
	{
		$this->repository = $repository;
	}

	public function getMICnaRecord($id)
	{
		$record = $this->repository->find($id);

		if (! $record) {
			abort(404);
		}

		return response()->json($record);
	}
}
