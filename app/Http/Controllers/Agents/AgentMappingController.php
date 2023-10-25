<?php
    
namespace App\Http\Controllers\Agents;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use Spatie\Permission\Models\Users\Role;
use App\Models\Agents\Agent;
    
class AgentMappingController extends Controller
{
    public $_permission    = "village-agents";
    public $_route         = "village-agents.mapping";
    public $_dir           = "agents.mapping";
    public $_menu_group    = "Agents";
    public $_page_title    = 'Mapping agents';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function agents(Request $request)
    {
        try {            
            return view($this->_dir.'.agents');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }

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

    public function agentMap()
    {
        $farmers = Agent::whereNotNull('latitude')->whereNotNull('longitude')->limit(10000)->get();

        $result = array();
        if (count($farmers) > 0) {
            foreach ($farmers as $farmer) {

                $desc = '';
                $desc .= 'Name: <strong>'.$farmer->first_name.' '.$farmer->last_name.'</strong><br>';
                $desc .= 'Gender: <strong>'.$farmer->gender.'</strong><br>';
                // $desc .= 'YOB: <strong>'.$farmer->year_of_birth.'</strong><br>';
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
}