<?php

namespace App\Http\Controllers;

use App\Models\AssociatedUsers;
use Illuminate\Http\Request;

class AssociatedUsersController extends Controller
{
       public function insertAssociatedUsers()
    {
        $data = [
            ['contract_id' => 1, 'user_id' => 1],
            ['contract_id' => 1, 'user_id' => 2],
            ['contract_id' => 1, 'user_id' => 3],
            ['contract_id' => 1, 'user_id' => 4],

            ['contract_id' => 2, 'user_id' => 1],
            ['contract_id' => 2, 'user_id' => 2],
            ['contract_id' => 2, 'user_id' => 3],
            

            ['contract_id' => 3, 'user_id' => 1],

            ['contract_id' => 3, 'user_id' => 4],

            ['contract_id' => 4, 'user_id' => 1],
            ['contract_id' => 4, 'user_id' => 2],
            ['contract_id' => 4, 'user_id' => 3],
            ['contract_id' => 4, 'user_id' => 4],

            
            ['contract_id' => 5, 'user_id' => 3],
            ['contract_id' => 5, 'user_id' => 4],

            // Repeat similar patterns for contracts 6 and 7
        ];

        AssociatedUsers::insert($data);

        return 'Data inserted successfully!';
    }

}
