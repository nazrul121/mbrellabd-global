@if(auth()->check() && auth()->user()->customer !=null)
    @php
        $addresses = \App\Models\Shipping_address::where('customer_id',auth()->user()->customer->id)->get();
    @endphp
    @foreach ($addresses as $key=>$item)
        <style>
            /* Hide the default radio button */
                .custom-checkbox input[type="radio"] {
                position: absolute;
                opacity: 0;
                cursor: pointer;
                }

                /* Create a custom checkbox appearance */
                .custom-checkbox {
                display: flex;
                align-items: center;
                cursor: pointer;
                font-size: 16px;
                }

                /* Style the checkmark box */
                .custom-checkbox .checkmark {
                height: 20px;
                width: 20px;
                background-color: white;
                border: 2px solid #ccc;
                border-radius: 4px;
                display: inline-block;
                margin-right: 10px;
                position: relative;
                }

                /* Add a checked effect */
                .custom-checkbox input[type="radio"]:checked + .checkmark {
                background-color: #2196f3;
                border-color: #2196f3;
                }

                /* Create the checkmark (hidden by default) */
                .custom-checkbox .checkmark::after {
                content: "";
                position: absolute;
                display: none;
                }

                /* Show the checkmark when checked */
                .custom-checkbox input[type="radio"]:checked + .checkmark::after {
                display: block;
                }

                /* Style the checkmark */
                .custom-checkbox .checkmark::after {
                left: 7px;
                top: 3px;
                width: 5px;
                height: 10px;
                border: solid white;
                border-width: 0 2px 2px 0;
                transform: rotate(45deg);
                }

        </style>
        <label class="custom-checkbox container-fluid alert alert-light bg-5" for="shippingAddress{{$key}}">
            <input type="radio" name="shipping_address_id" id="shippingAddress{{$key}}" data-city="{{$item->city_id}}" value="{{$item->id}}" @if($key==0)checked @endif />
            <span class="checkmark"></span> 

            <p class="mb-0">
                {{$item->fname.' '.$item->lname}} - {{$item->phone}} <br>

                @if($item->district)
                {{ $item->district->name}}  <i class="fas fa-long-arrow-alt-right text-info"></i> @endif

                @if($item->city)
                {{ $item->city->name}}  <i class="fas fa-long-arrow-alt-right text-info"></i> @endif 
                {{ $item->address}}
            </p>
        </label>

        {{-- <label for="shippingAddress{{$key}}" class="text-primary col-12"> 
            <div class="overflow-hidden d-flex align-items-center mt-1 p-3 ">
                <div class="checkout-user-img me-4 text-center">
                    <input type="radio" id="shippingAddress{{$key}}" name="shipping_address_id" data-city="{{$item->city_id}}" value="{{$item->id}}" @if($key==0)checked @endif /> <br>
                    <span>Set as Shipping Address</span>
        
                </div>
                <div class="checkout-user-details d-flex align-items-center justify-content-between w-100">
                    <div class="checkout-user-info">
                        <h2 class="checkout-user-name text-info">{{$item->fname.' '.$item->lname.' ('.$item->sex.')'}} - {{$item->phone}}</h2>
                        <p class="mb-0">
                            {{$item->fname.' '.$item->lname.' ('.$item->sex.')'}} - {{$item->phone}} <br>
                            
                            @if($item->district)
                            {{ $item->district->name}}  <i class="fas fa-long-arrow-alt-right text-info"></i> @endif

                            @if($item->city)
                            {{ $item->city->name}}  <i class="fas fa-long-arrow-alt-right text-info"></i> @endif 
                            {{ $item->address}}
                        </p>
                    </div>
                </div>
            </div> 
        </label> --}}
    @endforeach
@else
    <div class="shipping-address-form common-form checkout-summary-area">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">First name <span class="text-danger">*</span></label>
                    <input type="text" name="shipping_fname" value="{{ old('shipping_fname') }}"/>
                    <span class="text-danger">{{ $errors->first('shipping_fname')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Last name <span class="text-danger">*</span></label>
                    <input type="text" name="shipping_lname" value="{{ old('shipping_lname') }}"/>
                    <span class="text-danger">{{ $errors->first('shipping_lname')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Email address</label>
                    <input type="email" class="checkShipping" data-field="email" name="shipping_email" value="{{ old('shipping_email') }}"/>
                    <span class="text-danger">{{ $errors->first('shipping_email')}}</span>
                </fieldset>
            </div>

            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label>Phone No.</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text">{{ session('user_currency')->phone_code }}</div>
                        <input type="hidden" value="{{ session('user_currency')->phone_code }}">
                      </div>
                      <input type="text" class="form-control" name="shipping_phone" value="{{ old('shipping_phone','01') }}" placeholder="PHone No">
                    </div>
                    <span class="text-danger">{{ $errors->first('shipping_phone')}}</span>
                </fieldset>

                {{-- <fieldset style="width:13%;float:left">
                    <label class="label" style="min-height: 24px;"></label>
                    <input style="border-right:0px;" type="text" value="{{ session('user_currency')->phone_code }}">
                </fieldset>
                <fieldset style="width:87%">
                    <label class="label">Phone No. <span class="text-danger">*</span></label>
                    <input type="text" class="checkShipping" data-field="phone" name="shipping_phone" value="{{ old('shipping_phone','01') }}"/>
                    <span class="text-danger">{{ $errors->first('shipping_phone')}}</span>
                </fieldset> --}}
            </div>

            <div class="col-lg-6 col-md-12 col-12">
                <fieldset> 
                    <label class="label">Country <span class="text-danger">*</span></label>
                    <select name="shipping_country" class="form-select">
                        <option value="{{ session('user_currency')->id }}">{{ session('user_currency')->name }}</option>
                    </select>
                    <span class="text-danger">{{ $errors->first('shipping_country')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"> @if(session('user_currency')->id==2)Divisioin @else Region @endif  
                        <span class="text-danger">*</span>
                    </label>                                     
                    <select class="form-select" name="shipping_division">
                        <option value="">Choose Region</option>
                        @foreach (\DB::table('divisions')->where(['country_id'=>session('user_currency')->id, 'status'=>'1'])->get() as $div)
                            <option {{ old('shipping_division') == $div->id.'|'.$div->name ? 'selected' : '' }} value="{{ $div->id.'|'.$div->name }}">{{ $div->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('shipping_division')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"> @if(session('user_currency')->id==2)District @else State @endif  
                        <span class="text-danger">*</span>
                    </label>                                            
                    <select class="form-select" name="shipping_district"> </select>
                    <span class="text-danger">{{ $errors->first('shipping_district')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">@if(session('user_currency')->id==2)City @else Area @endif  
                        <span class="text-danger">*</span>
                    </label>                                  
                    <select class="form-select" name="shipping_city"> </select>
                    <span class="text-danger">{{ $errors->first('shipping_city')}}</span>
                </fieldset>
            </div>

            <div class="col-lg-12 col-md-12 col-12">
                <fieldset>
                    <label class="label">Address 2</label>
                    <textarea name="shipping_address" class="form-control">{{ old('shipping_address') }}</textarea>
                    <span class="text-danger">{{ $errors->first('shipping_address')}}</span>
                </fieldset>
            </div>
        </div>
        @if (session('user_currency')->id != 2)
            <div class="row mt-md-4">
                <div class="col-lg-6">
                    <label for="staticEmail" class="col-form-label text-md-end">Postal Code <span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-6">
                    <input type="text" name="shipping_postCode" class="form-control" value="{{ old('shipping_postCode') }}"/>
                    <span class="text-danger">{{ $errors->first('shipping_postCode')}}</span>
                </div>
            </div>
        @endif 
    </div>
@endif

