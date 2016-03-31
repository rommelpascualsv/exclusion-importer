<?php namespace App\Console\Commands\MICna;

use App\Console\Commands\BaseClear;
use App\Repositories\MICnaRepository;

class Clear extends BaseClear {

    protected $name = 'micna:clear';

    protected $description = 'Clears the Michigan Certified Nurse Aide collection in mongo';

    protected $database = 'Michigan Certified Nurse Aide';

    public function __construct(MICnaRepository $micnaRepository)
    {
        parent::__construct();
        $this->repository = $micnaRepository;
    }
}
