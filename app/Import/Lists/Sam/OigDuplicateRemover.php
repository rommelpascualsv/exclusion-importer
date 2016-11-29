<?php namespace App\Import\Lists\Sam;


class OigDuplicateRemover
{

    private $tempTable = 'sam_records_temp';

    public function markOigDuplicates()
    {
        $records_updated = app('db')
            ->table($this->tempTable)
            ->join('oig_records', function ($join) {
                $join->on('oig_records.firstname', '=', 'sam_records_temp.First')
                    ->on('oig_records.lastname', '=', 'sam_records_temp.Last')
                    ->on('oig_records.excldate', '<=', 'sam_records_temp.Active_Date');
            })
            ->where('sam_records_temp.First', '!=', '')
            ->where('sam_records_temp.Last', '!=', '')
            ->where('sam_records_temp.Excluding_Agency', '=', 'HHS')
            ->update([
                'sam_records_temp.matching_OIG_hash' => app('db')->raw('oig_records.hash')
            ]);

        info('Record(s) updated is ' .$records_updated);

        /*if ($records_updated == null) {
            info('No records updated');
        }*/

        return $records_updated;
    }

    public function invoke()
    {
        $this->markOigDuplicates();

        $total_records_deleted = app('db')
            ->table($this->tempTable)
            ->where('matching_OIG_hash', '!=', app('db')->raw('UNHEX(\'00000000000000000000000000000000\')'))
            ->delete();

        if ($total_records_deleted == null) {
            info('No records deleted!');
        }

        info('Done removing Oig records.');

        return $total_records_deleted;
    }

}