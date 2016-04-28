<?php namespace App\Console\Commands\Njna;

use App\Console\Commands\BaseClear;
use App\Repositories\NjnaRepository;

class Clear extends BaseClear 
{
    protected $name = 'njna:clear';

    protected $description = 'Clears the New Jersey Nurse Aide collection in mongo';

    protected $database = 'New Jersey Nurse Aide';

    public function __construct(NjnaRepository $njnaRepository)
    {
        parent::__construct();
        $this->repository = $njnaRepository;
    }
}
