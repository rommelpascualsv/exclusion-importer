<?php namespace app\Repositories;

interface Repository
{
    public function create($record);

    public function clear();

    public function find($id);
}
