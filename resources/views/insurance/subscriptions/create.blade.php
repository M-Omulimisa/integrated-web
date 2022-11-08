{!! Form::open(['method' => 'POST', 'route' => ['insurance.subscriptions.store']]) !!}

           <input type="hidden" name="category" value="{{ $category }}">
                
                    {!! Form::hidden('session_id', 'MOWEB_'.generateRandomString(0, 9, 30)) !!}                
                    {!! Form::hidden('phone', 'System') !!} 
                    {!! Form::hidden('user_type', Auth::user()->roles->pluck('id')->first() ) !!} 
                    {!! Form::hidden('tool', 'web') !!} 
                    {!! Form::hidden('main_action', 2 ) !!}
                    {!! Form::hidden('subscription', $category) !!}

                <div class="form-group mb-3">
                    {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                    <div class="col-sm-5">
                   {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading"><h5 class="text-primary">Agent Information</h5></div>
                    <div class="panel-body">
                        <p>This field is optional. Only fill if the source of the subscription details is an Agent</p>

                        <div class="form-group mb-3">
                            {!! Form::label('agent_id', 'Agent (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                            <div class="col-sm-5">
                           {!! Form::select('agent_id', $agents, old('agent_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading"><h5 class="text-primary">Farmer Information</h5></div>
                    <div class="panel-body">

                        <div class="form-group mb-3">
                            {!! Form::label('farmer_id', 'Farmer (required)', ['class' => 'col-sm-3 form-label']) !!}                
                            <div class="col-sm-5">
                                <select class="form-control select2" name="farmer_id" required>
                                    @if (count($farmers) > 0)
                                    <option value="null">--Select--</option>
                                        @foreach ($farmers as $farmer)
                                            <option value="{{$farmer->id}}">{{ $farmer->first_name.' '.$farmer->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                           {{-- {!! Form::select('farmer_id', $farmers, old('farmer_id'), ['class' => 'form-control select2','placeholder'=>'--Select--', 'required'=>'']) !!}  --}}   
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading"><h5 class="text-primary">Coverage</h5></div>
                    <div class="panel-body">
                        
                        <div class="form-group mb-3">
                            {!! Form::label('name', 'Season (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            @if (isset($seasons) && count($seasons) > 0)
                            {!! Form::select('season_id', $seasons, old('season_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}
                            @else
                            No Seasons in the system. Ensure they are set to continue
                            @endif               
                            </div>
                        </div>
                        
                    </div>
                </div>

                @if ($category == 'crop')
                <input type="hidden" name="item_category" value="1">

                    <div class="panel">
                    <div class="panel-heading"><h5 class="text-primary">Crops</h5></div>
                    <div class="panel-body">
                        
                        <div class="form-group mb-3">
                            {!! Form::label('name', 'Item (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            @if (isset($enterprises) && count($enterprises) > 0)
                            {!! Form::select('item_crops', $enterprises, old('item_crops'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--', 'id' => 'crop_id']) !!}
                            @else
                            No Items in the system under this category. Ensure they are set to continue
                            @endif               
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('crops_acrage', 'Acrage (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('crops_acrage', old('crops_acrage'), ['class' => 'form-control', 'placeholder' => '', 'id' => 'acerage']) !!}               
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            {{-- <div id="crops_label"></div> --}}
                            {!! Form::label('name', 'Expected output per acre (required)', ['class' => 'col-sm-4 col-form-label', 'id' => 'crops_label']) !!}                
                            <div class="col-sm-5">
                                {!! Form::text('crops_yield_per_acrage', old('crops_yield_per_acrage'), ['class' => 'form-control', 'placeholder' => '', 'id' => 'crops_yield_per_acrage']) !!}              
                            </div>
                        </div> 
                        
                        <div class="form-group mb-3">
                            {!! Form::label('name', 'Expected price per unit output (required)', ['class' => 'col-sm-4 col-form-label', 'id' => 'price_label']) !!}

                            <div class="col-sm-5">
                                {!! Form::text('crops_price_per_unit', old('crops_price_per_unit'), ['class' => 'form-control', 'placeholder' => '', 'id' => 'crops_price_per_unit']) !!}            
                            </div> 
                        </div> 
                        
                    </div>
                </div>

                @elseif($category == 'livestock')
                <input type="hidden" name="item_category" value="2">

                    <div class="panel">
                    <div class="panel-heading"><h5 class="text-primary">Livestock</h5></div>
                    <div class="panel-body">
                        
                        <div class="form-group mb-3">
                            {!! Form::label('name', 'Item (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            @if (isset($enterprises) && count($enterprises) > 0)
                            {!! Form::select('item_livestock', $enterprises, old('item_livestock'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--', 'id' => 'item_livestock']) !!}
                            @else
                            No Items in the system under this category. Ensure they are set to continue
                            @endif               
                            </div>
                        </div>

                        <input type="hidden" name="livestock_farm_size" value="0">
                           
                        <div class="form-group mb-3">
                            <label class="col-sm-4 col-form-label">Breed</label>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                         {{ Form::radio('livestock_breed', 1, null, ['class' => 'form-check-input']) }} Exotic</label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                       {{ Form::radio('livestock_breed', 2, null, ['class' => 'form-check-input']) }} Local</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('livestock_count', 'No. of Animals (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('livestock_count', old('livestock_count'), ['class' => 'form-control', 'placeholder' => '']) !!}               
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('name', 'Expected income per animal (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                                {!! Form::text('livestock_sum_per_animal', old('livestock_sum_per_animal'), ['class' => 'form-control', 'placeholder' => '', 'id' => 'livestock_sum_per_animal']) !!}
                                  {{-- <select class="form-control select2" id="livestock_sum_per_animal" name="livestock_sum_per_animal">
                                    <option value="" selected="">--select expected income per acre--</option>
                                  </select>   --}}             
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endif      

                <div class="panel mb-5" id="btn-continue">
                    <div class="panel-body">
                       <button type="button" class="btn btn-primary" onclick="loadResult()">Continue</button>
                    </div>
                </div>

                <div id="results" class="none">
                    <div class="panel">
                    <div class="panel-heading"><h5 class="text-primary">Computed Results</h5></div>
                    <div class="panel-body">
                        
                        <div class="form-group mb-3">
                            {!! Form::label('sum_insured', 'Sum Insured (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                            <div class="col-sm-5">
                                {!! Form::text('sum_insured', 0, ['class' => 'form-control', 'id' => 'sum_insured', 'disabled'=>'']) !!}
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('', 'Basic Premium (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                                {!! Form::text('basic_premium', 0, ['class' => 'form-control', 'id' => 'basic_premium', 'disabled'=>'']) !!}
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('', 'Government Subsidy (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                                {!! Form::text('govt_subsidy', 0, ['class' => 'form-control', 'id' => 'govt_subsidy', 'disabled'=>'']) !!}
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('', 'IRA Levy (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                                {!! Form::text('ira_levy', 0, ['class' => 'form-control', 'id' => 'ira_levy', 'disabled'=>'']) !!}
                            </div>
                        </div>
                        
                        {{-- <div class="form-group mb-3">
                            {!! Form::label('', 'V.A.T (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                                {!! Form::text('vat', 0, ['class' => 'form-control', 'id' => 'vat', 'disabled'=>'']) !!}
                            </div>
                        </div> --}}
                        
                        <div class="form-group mb-3">
                            {!! Form::label('', 'Net Premium (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                                {!! Form::text('net_premium', 0, ['class' => 'form-control', 'id' => 'payment_amount']) !!}
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('commission', 'Agent Commission (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                                {!! Form::text('commission', 0, ['class' => 'form-control', 'id' => 'commission', 'disabled'=>'']) !!}
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-body">
                       {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!} 
                    </div>
                </div>

                </div><!--end result-->
                   
            {!! Form::close() !!}