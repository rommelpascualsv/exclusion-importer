<?php namespace App\Console\Commands\Taxonomy;

use App\Console\Commands\BaseClear;
use App\Repositories\TaxonomyRepository;

class Clear extends BaseClear {

    protected $name = 'taxonomy:clear';

    protected $description = 'Clears the taxonomy collection in mongo';

    protected $database = 'Taxonomy';

    public function __construct(TaxonomyRepository $taxonomyRepository)
    {
        parent::__construct();
        $this->repository = $taxonomyRepository;
    }
}
