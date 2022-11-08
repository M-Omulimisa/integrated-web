<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class InformationController extends Controller
{

    public function getFrequencyPeriod(Request $request)
    {
        $data = ['id' => null, 'name' => 'No result'];
        
        if ($request->frequency == 'Trial') {
            $data = [
                ['id' => 1, 'name' => '1 Week'],
            ];
        }
        elseif ($request->frequency == 'Weekly') {
            $data = [
                ['id' => 1, 'name' => '1 Week'],
                ['id' => 2, 'name' => '2 Weeks'],
                ['id' => 3, 'name' => '3 Weeks'],
            ];
        }
        elseif ($request->frequency == 'Monthly') {
            $data = [
                ['id' => 1, 'name' => '1 Month'],
                ['id' => 2, 'name' => '2 Months'],
                ['id' => 3, 'name' => '3 Months'],
                ['id' => 4, 'name' => '4 Months'],
                ['id' => 5, 'name' => '5 Months'],
                ['id' => 6, 'name' => '6 Months'],
                ['id' => 7, 'name' => '7 Months'],
                ['id' => 8, 'name' => '8 Months'],
                ['id' => 9, 'name' => '9 Months'],
                ['id' => 10, 'name' => '10 Months'],
                ['id' => 11, 'name' => '11 Months'],
            ];
        }
        elseif ($request->frequency == 'Yearly') {
            $data = [
                ['id' => 1, 'name' => '1 Year'],
                ['id' => 2, 'name' => '2 Years'],
                ['id' => 3, 'name' => '3 Years'],
                ['id' => 4, 'name' => '4 Years'],
                ['id' => 5, 'name' => '5 Years'],
            ];
        }

        return Response::json($data);
    }
}
