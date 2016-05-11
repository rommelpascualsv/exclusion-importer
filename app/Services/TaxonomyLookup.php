<?php namespace App\Services;

use App\Repositories\TaxonomyRepository;

class TaxonomyLookup
{
    protected $items;

    public function __construct(TaxonomyRepository $taxonomyRepository)
    {
        $this->items = [];

        foreach ($taxonomyRepository->all() as $record) {
            if (isset($record['code']) && isset($record['type'])) {
                $this->items[$record['code']] = $record['type'];
            }
        }
    }

    public function getTypeFromCode($code)
    {
        return isset($this->items[$code]) ? $this->items[$code] : "";
    }
}
