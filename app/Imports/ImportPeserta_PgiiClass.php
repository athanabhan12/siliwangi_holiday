<?php

namespace App\Imports;

use App\Models\PGII;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportPeserta_PgiiClass implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new PGII([
            'nama_peserta' => $row[1],
            'kelas' => $row[2],
            'no_telepon' => $row[3],
            'no_peserta_tour' => $row[4],
            'no_bus_kendaraan' => $row[5],
            'id_tour' => $row[6],
        ]);    
    }
}
