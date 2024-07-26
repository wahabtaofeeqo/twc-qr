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
        $age = $row['age'];
        $email = $row['email'];
        $phone = $row['phone'];
        $gender = $row['gender'];
        $location = $row['location'];
        $name = $row['first_name'] . '' . $row['last_name'];
        // $category = $row['category'];
        // $qrCode = "name=" . urlencode($name) . "&email=" . urlencode($email) . "&org=$category" . "&jobTitle=$location";

        if($name && $email) {
            return new User([
                'age' => $age,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'location' => $location,
            ]);   
        }
    }

    /**
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        // info($e->getMessage());
    }
}