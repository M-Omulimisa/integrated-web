<?php
    
namespace App\Http\Controllers\Alerts;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use Spatie\Permission\Models\Users\Role;
    
class AlertMappingController extends Controller
{
    public $_permission    = "alert-mapping";
    public $_route         = "alerts.mapping";
    public $_dir           = "alerts.mapping";
    public $_menu_group    = "Alerts";
    public $_page_title    = 'Mapping outbreaks';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function alerts(Request $request)
    {
        try {            
            return view($this->_dir.'.alerts');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
}