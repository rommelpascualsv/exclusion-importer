<?php namespace App\Http\Controllers;

use App\Repositories\NppesRepository;

class NppesController extends BaseController
{
	protected $repository;

	public function __construct(NppesRepository $repository)
	{
		$this->repository = $repository;
	}

	public function getNppesRecord($id)
	{
		$record = $this->repository->find($id);

		if (! $record) {
			abort(404);
		}

		return response()->json($record);
	}
}
