<?php
    
namespace App\Http\Controllers\Extension;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Extension\ExtensionOfficer;
    
class ExtensionOfficerMappingController extends Controller
{
    public $_permission    = "extension-mapping";
    public $_route         = "extension-officers.mapping";
    public $_dir           = "extension.mapping";
    public $_menu_group    = "Extension";
    public $_page_title    = 'Mapping';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function officers(Request $request)
    {
        try {            
            return view($this->_dir.'.officers');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }

    public function officerMap()
    {
        $officers = ExtensionOfficer::whereNotNull('latitude')->whereNotNull('longitude')->limit(10000)->get();

        $result = array();
        if (count($officers) > 0) {
            foreach ($officers as $officer) {

                $desc = '';
                $desc .= 'Name: <strong>'.$officer->name.'</strong><br>';
                $desc .= 'Gender: <strong>'.$officer->gender.'</strong><br>';
                $desc .= 'Category: <strong>'.$officer->category.'</strong><br>';
                $desc .= 'Phone: <strong>'.$officer->phone.'</strong><br>';
                $desc .= 'Location: <strong>'.$officer->location->name.'</strong>';

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
                            'coordinates' => [ $officer->longitude, $officer->latitude, 0.0 ]
                        ]
                    );
            }
        }
        return response()->json($result);
    }
}