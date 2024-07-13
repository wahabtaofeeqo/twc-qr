<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnError
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $name = $row['name'];
        $email = $row['email'];
        $qrCode = $row['data'];
        $location = $row['location'];
        $category = $row['category'];

        if($name && $email) {
            return new User([
                'name' => $name,
                'email' => $email,
                'data' => $qrCode,
                'location' => $location,
                'category' => $category
            ]);   
        }
    }

    /**
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        info($e->getMessage());
    }
}