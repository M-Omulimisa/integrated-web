<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningInactiveStudentsCallSetting;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

class SysteOutCallsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = ELearningInactiveStudentsCallSetting::limit(1)->first();

        if (!$setting){
          ELearningInactiveStudentsCallSetting::create();
          $setting = ELearningInactiveStudentsCallSetting::limit(1)->first();  
        } 

        return view('e_learning.settings.system_auto_out_calls', compact('setting'));
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
        request()->validate([
            'calling_time'                  => 'required|string',
            'retry_after_in_minutes'        => 'required|numeric',
            'number_of_retries'             => 'required|numeric',
            'make_missed_after_in_minutes'  => 'required|numeric',
            'calls_per_cycle'               => 'required|numeric',
        ]);

        try {

            if ($data   = ELearningInactiveStudentsCallSetting::find($id)) {
              $setting = [
                    'calling_time'                  => $request->calling_time,
                    'retry_after_in_minutes'        => $request->retry_after_in_minutes,
                    'number_of_retries'             => $request->number_of_retries,
                    'make_missed_after_in_minutes'  => $request->make_missed_after_in_minutes,
                    'calls_per_cycle'               => $request->calls_per_cycle
                ];

                if ($data->update($setting)) {  
                  return redirect()->back()->with('success', 'Chapter successfully updated');
                }
                else{
                  return redirect()->back()->withErrors('Resource NOT Updated')->withInput();
                }
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Found')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

}
