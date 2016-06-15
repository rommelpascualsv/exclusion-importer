<?php
namespace App\Repositories;

class ExclusionListVersionRepository implements Repository
{
    public function create($record)
    {
        return app('db')->table('exclusion_list_versions')->insert($record);    
    }
    
    public function clear()
    {
        app('db')->table('exclusion_list_versions')->truncate();
    }
    
    public function find($prefix)
    {
        return app('db')->table('exclusion_list_versions')->where('prefix', $prefix)->get();
    }

    public function contains($prefix)
    {
        return app('db')->table('exclusion_list_versions')->where('prefix', $prefix)->count() > 0;
    }
    
    public function createOrUpdate($prefix, $data)
    {
        if ($this->contains($prefix)) {
            return app('db')->table('exclusion_list_versions')->where('prefix', $prefix)->update($data);
        } else {
            return $this->create(array_merge(['prefix' => $prefix], $data));
        }
    }
}