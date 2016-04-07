<?php namespace App\Http\Controllers;

use App\Repositories\NJCredentialRepository;
use Laravel\Lumen\Routing\Controller as BaseController;

class NJCredentialController extends BaseController
{
	protected $repository;

	public function __construct(NJCredentialRepository $repository)
	{
		$this->repository = $repository;
	}

	public function getNJCredentialRecord($id)
	{
		$record = $this->repository->find($id);

		if (! $record) {
			abort(404);
		}

		return response()->json($record);
	}
}
