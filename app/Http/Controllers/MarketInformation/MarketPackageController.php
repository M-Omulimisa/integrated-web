<?php
    
namespace App\Http\Controllers\MarketInformation;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Settings\Country;
use App\Models\Settings\Language;
use App\Models\Settings\Enterprise;
use App\Models\Market\MarketPackage;
use App\Models\Market\MarketPackageMessage;
use App\Models\Market\MarketPackagePricing;
use App\Models\Market\MarketPackageEnterprise;
use App\Models\Market\MarketPackageRegion;
use App\Models\RegionModel;
    
class MarketPackageController extends Controller
{
    public $_permission    = "settings";
    public $_route         = "market.packages";
    public $_dir           = "market.packages";
    public $_menu_group    = "Market Information";
    public $_page_title    = 'Market Packages';

    const FREQUENCIES = [
                'Trial'    => 'Trial',
                'Weekly'    => 'Weekly', 
                'Monthly'   => 'Monthly', 
                'Yearly'    => 'Yearly'
            ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {            
            return view($this->_dir.'.index');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try { 
            $countries  = Country::orderBy('name', 'ASC')->pluck('name', 'id')->all();
            $languages  = Language::orderBy('name', 'ASC')->get();
            $regions  = RegionModel::orderBy('name', 'ASC')->get();
            $enterprises= Enterprise::orderBy('name', 'ASC')->get();
            $frequencies= self::FREQUENCIES;  

            return view($this->_dir.'.create', compact('countries', 'enterprises', 'frequencies', 'languages', 'regions'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'country_id'  => 'required',
            'name'        => 'required',
            'menu'        => 'required',
            'enterprises' => 'required',
            'regions'   => 'required',
            'languages'   => 'required',
            'frequency'   => 'required',
            'messages'    => 'required',
            'cost'        => 'required',
        ]);

        try {            
            $package = MarketPackage::create($request->all()); 

            if ($package) {
                for ($i=0; $i < count($request->enterprises); ++$i) {
                      MarketPackageEnterprise::create([
                        'package_id'    => $package->id,
                        'enterprise_id' => $request->enterprises[$i]
                      ]);
                  }

                  for ($i=0; $i < count($request->frequency); ++$i) {
                    if (!is_null($request->messages[$i]) && !is_null($request->cost[$i])) {
                          MarketPackagePricing::create([
                            'package_id'=> $package->id,
                            'frequency' => $request->frequency[$i],
                            'menu'      => $request->frequency_menus[$i],
                            'messages'  => $request->messages[$i],
                            'cost'      => $request->cost[$i]
                          ]);
                    }
                  }

                  for ($i=0; $i < count($request->languages); ++$i) {
                    if (!is_null($request->menus[$i])) {
                          MarketPackageMessage::create([
                            'package_id'=> $package->id,
                            'language_id' => $request->languages[$i],
                            'menu'        => $request->menus[$i],
                          ]);
                    }
                  }  

                  for ($i=0; $i < count($request->regions); ++$i) {
                      MarketPackageRegion::create([
                        'package_id'    => $package->id,
                        'region_id' => $request->regions[$i]
                      ]);
                  }             
            }

            return redirect()->route($this->_route.'.index')->with('success','Operation was successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }    
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {        
            $data = [];
            return view($this->_dir.'.show', compact('data', 'id'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {      
        try { 
            $package    = MarketPackage::findorFail($id);
            $countries  = Country::orderBy('name', 'ASC')->pluck('name', 'id')->all();
            $languages  = Language::orderBy('name', 'ASC')->get();
            $regions  = RegionModel::orderBy('name', 'ASC')->get();
            $enterprises= Enterprise::orderBy('name', 'ASC')->get();
            $frequencies= self::FREQUENCIES;  

            return view($this->_dir.'.edit', compact('package', 'id', 'countries', 'enterprises', 'frequencies', 'languages', 'regions'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'country_id'  => 'required',
            'name'        => 'required',
            'menu'        => 'required',
            'enterprises' => 'required',
            'frequency'   => 'required',
            'num_message'=> 'required',
            'cost'        => 'required',
            'languages'   => 'required',
            'status'      => 'required'
        ]);

        try {            
            $package = MarketPackage::findorFail($id);

            if ($package->update($request->all())) {
                MarketPackageEnterprise::wherePackageId($id)->delete(); // remove existing settings
                for ($i=0; $i < count($request->enterprises); ++$i) {
                      MarketPackageEnterprise::create([
                        'package_id'    => $id,
                        'enterprise_id' => $request->enterprises[$i]
                      ]);
                  }

                  MarketPackagePricing::wherePackageId($id)->delete(); // remove existing settings
                  for ($i=0; $i < count($request->frequency); ++$i) {
                    if (!is_null($request->num_message[$i]) && !is_null($request->cost[$i])) {
                          MarketPackagePricing::create([
                            'package_id'=> $id,
                            'frequency' => $request->frequency[$i],
                            'menu'      => $request->frequency_menus[$i],
                            'messages'  => $request->num_message[$i],
                            'cost'      => $request->cost[$i]
                          ]);
                    }
                  }

                  MarketPackageMessage::wherePackageId($id)->delete(); // remove existing settings
                  for ($i=0; $i < count($request->languages); ++$i) {
                    if (!is_null($request->language_menu[$i])) {
                          MarketPackageMessage::create([
                            'package_id'  => $id,
                            'language_id' => $request->languages[$i],
                            'menu'        => $request->language_menu[$i],
                            'message'     => $request->message[$i],
                          ]);
                    }
                  }

                  MarketPackageRegion::wherePackageId($id)->delete(); // remove existing settings
                  for ($i=0; $i < count($request->regions); ++$i) {
                      MarketPackageRegion::create([
                        'package_id'  => $id,
                        'region_id' => $request->regions[$i],
                      ]);
                  }                
            }            
            return redirect()->route($this->_route.'.index')->with('success','Operation was successful');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {  

            $package = MarketPackage::findorFail($id);

            if (count($package->subscriptions) > 0 || count($package->ussd_sessions) > 0) {
                // market_package_enterprises
                // market_subscriptions
                // market_package_messages
                // market_package_pricings
                // ussd_session_data
                // market_package_regions
                return redirect()->back()->withErrors('Operation was not successful');
            }

            if(count($package->enterprises) > 0) {
                $package->enterprises()->delete();
            }

            if (count($package->messages) > 0) {
                $package->messages()->delete();
            }

            if (count($package->pricing) > 0) {
                $package->pricing()->delete();
            }

            if (count($package->regions) > 0) {
                $package->regions()->delete();
            }

            $package->delete();
            return redirect()->route($this->_route.'.index')->with('success','Operation was successful');                        
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Load records into a DataTable.
     *
     * @param  DataTable Obj
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {

            DB::statement(DB::raw('set @DT_RowIndex=0'));
            $data = MarketPackage::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('package', function($data) {
                    return $data->menu.'. '.$data->name;
                  })
                ->addColumn('enterprises', function($data) {
                    $list = '';
                    if (count($data->enterprises) > 0) {
                        foreach ($data->enterprises as $enterprise) {
                            $list .= $enterprise->enterprise->name.', ';
                        }
                    }
                    return $list;
                  })
                ->addColumn('pricing', function($data) {
                    $list = '';
                    if (count($data->pricing) > 0) {
                        foreach ($data->pricing as $pricing) {
                            $list .= $pricing->menu.'. '.$pricing->frequency.', '.$pricing->messages.'sms @'.number_format($pricing->cost).'<br/>';
                        }
                    }
                    return $list;
                  })
                ->addColumn('languages', function($data) {
                    $list = '';
                    if (count($data->messages) > 0) {
                        foreach ($data->messages as $language) {
                            $list .= $language->language->name.', ';
                        }
                    }
                    return $list;
                  })
                ->addColumn('regions', function($data) {
                    $list = '';
                    if (count($data->regions) > 0) {
                        foreach ($data->regions as $region) {
                            $list .= $region->region->name.', ';
                        }
                    }
                    return $list;
                  })
                ->addColumn('listing', function($data) {
                    return $data->status ? 'YES' : 'NO';
                  })
                ->addColumn('messages', function($data) {
                    if(count($data->enterprises) > 0 && count($data->pricing) > 0 && count($data->messages) > 0) return '<a href="'.url('market/package/messages/'.$data->id).'">Set</a> '.(isset($data->last_message->updated_at) ? '<br>Last update: '.$data->last_message->updated_at : '');
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = TRUE;
                    $delete  = TRUE;
                    return view('partials.actions_2', compact('route','id','manage','delete'))->render();
                })
                ->rawColumns(['action', 'enterprises', 'pricing', 'languages', 'messages'])
                ->make(true);
        }
    }

    /**
     * Display a listing messages of a package.
     *
     * @return \Illuminate\Http\Response
     */
    public function messages(MarketPackage $package)
    {
        try {            
            return view($this->_dir.'.messages', compact('package'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Updates messgaes of a package in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMessages(Request $request)
    {
        $this->validate($request, [
            'package_id'  => 'required|exists:market_packages,id',
            'message'     => 'required',
        ]);

        try {            
              for ($i=0; $i < count(array_filter($request->message)); ++$i) {
                  MarketPackageMessage::wherePackageId($request->package_id)
                                        ->whereLanguageId($request->languages[$i])
                                        ->update(['message' => trim($request->message[$i]) ]);
              }  
            return redirect()->back()->with('success', 'Operation was successful')->withInput();
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
}