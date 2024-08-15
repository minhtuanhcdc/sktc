<?php

namespace App\Imports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PostImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Post([
            'name'     => $row['name'],
            'code'     => $row['code'],
            'parent_code' => $row['parent_code'],
            'province_code' => $row['provice_code'],
            'address' => $row['address'],  
        ]);
    }
}
