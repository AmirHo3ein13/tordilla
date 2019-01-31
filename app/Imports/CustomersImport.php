<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
//        dump([$row[0], $row[1], $row[2], $row[3]]);
        if ($row[0] == 'کد' and $row[1] == 'عنوان')
            return null;
        return new Customer([
            'store_name' => $row['1'],
            'address' => $row['3'],
            'code' => $row['0'],
            'phone' => $row['2'],
        ]);
    }
}
