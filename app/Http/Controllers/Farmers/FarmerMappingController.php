<?php
    
namespace App\Http\Controllers\Farmers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use Spatie\Permission\Models\Users\Role;
use App\Models\Farmers\Farmer;
use App\Models\Farmers\FarmerGroup;
    
class FarmerMappingController extends Controller
{
    public $_permission    = "farmer-mapping";
    public $_route         = "farmers.mapping";
    public $_dir           = "farmers.mapping";
    public $_menu_group    = "Farmers";
    public $_page_title    = 'Mapping farmers & groups';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function farmers(Request $request)
    {
        try {            
            return view($this->_dir.'.farmers');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function groups(Request $request)
    {
        try {            
            return view($this->_dir.'.groups');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }

    public function farmerMap()
    {
        $farmers = Farmer::whereNotNull('latitude')->whereNotNull('longitude')->limit(10000)->get();

        $result = array();
        if (count($farmers) > 0) {
            foreach ($farmers as $farmer) {

                $desc = '';
                $desc .= 'Name: <strong>'.$farmer->first_name.' '.$farmer->last_name.'</strong><br>';
                $desc .= 'Gender: <strong>'.$farmer->gender.'</strong><br>';
                $desc .= 'YOB: <strong>'.$farmer->year_of_birth.'</strong><br>';
                $desc .= 'Phone: <strong>'.$farmer->phone.'</strong><br>';
                $desc .= 'Location: <strong>'.$farmer->location->name.'</strong>';

                $result[] = array(
                        'type' => 'Feature', 
                        'properties' => [
                            'id' => 'ak16994521',
                            'mag' => 2.3,
                            'time' => 1507425650893,
                            'felt' => null,
                            'das' => 0,
                            'description' => $desc,
                        ],
                        'geometry' => [
                            'type' => 'Point',
                            'coordinates' => [ $farmer->longitude, $farmer->latitude, 0.0 ]
                        ]
                    );
            }
        }
        return response()->json($result);
    }

    public function farmerGroupMap()
    {
        $groups = FarmerGroup::whereNotNull('latitude')->whereNotNull('longitude')->limit(10000)->get();

        $result = array();
        if (count($groups) > 0) {
            foreach ($groups as $group) {

                $desc = '';
                $desc .= 'Name: <strong>'.$group->name.'</strong><br>';
                $desc .= 'Code: <strong>'.$group->code.'</strong><br>';
                $desc .= 'Year: <strong>'.$group->establishment_year.'</strong><br>';
                $desc .= 'Members: <strong>'.$group->total_members.'</strong><br>';
                $desc .= 'Contact: <strong>'.$group->group_leader_contact.'</strong><br>';
                $desc .= 'Location: <strong>'.$group->location->name.'</strong>';

                $result[] = array(
                        'type' => 'Feature', 
                        'properties' => [
                            'id' => 'ak16994521',
                            'mag' => 2.3,
                            'time' => 1507425650893,
                            'felt' => null,
                            'das' => 0,
                            'description' => $desc,
                        ],
                        'geometry' => [
                            'type' => 'Point',
                            'coordinates' => [ $group->longitude, $group->latitude, 0.0 ]
                        ]
                    );
            }
        }
        return response()->json($result);
    }
}