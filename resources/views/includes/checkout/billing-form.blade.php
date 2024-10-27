@if(auth()->check() && auth()->user()->customer !=null)
    <div class="checkout-user-area overflow-hidden d-flex align-items-center mt-1">
        <div class="checkout-user-img me-4">
            <img src="{{url('/storage/'. auth()->user()->customer->photo)}}" height="70">
        </div>
        <div class="checkout-user-details d-flex align-items-center justify-content-between w-100">
            <div class="checkout-user-info">
                <h2 class="checkout-user-name">{{auth()->user()->customer->first_name}}  {{auth()->user()->customer->last_name}} -  {{auth()->user()->customer->phone}}</h2>
                <p class="checkout-user-address mb-0">
                    @if(auth()->user()->customer->division)
                        {{auth()->user()->customer->division->name}} <i class="fas fa-long-arrow-alt-right text-info"></i> @endif

                    @if(auth()->user()->customer->district)
                    {{auth()->user()->customer->district->name}} <i class="fas fa-long-arrow-alt-right text-info"></i> @endif

                    @if(auth()->user()->customer->city)
                    {{auth()->user()->customer->city->name}} <i class="fas fa-long-arrow-alt-right text-info"></i> @endif 
                    {{auth()->user()->customer->address}}
                </p>
            </div>
            <input type="hidden" name="phone" value="{{ auth()->user()->customer->phone }}"/>
            <a href="{{ route('customer.account-info', app()->getLocale()) }}" target="_blank" class="edit-user btn-secondary">EDIT PROFILE</a>
        </div>
    </div>
@else 

    <div class="shipping-address-form common-form">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">First name <span class="text-danger">*</span></label>
                    <input type="text" name="fname" value="{{ old('fname') }}" required/>
                    <span class="text-danger">{{ $errors->first('fname')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Last name <span class="text-danger">*</span></label>
                    <input type="text" name="lname" value="{{ old('lname') }}" required/>
                    <span class="text-danger">{{ $errors->first('lname')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Email address</label>
                    <input type="email" class="checkBilling" name="email" data-field="email" value="{{ old('email') }}"/>
                    <span class="text-danger">{{ $errors->first('email')}}</span>
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
                      <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="PHone No">
                    </div>
                    <span class="text-danger">{{ $errors->first('phone')}}</span>
                </fieldset>

            </div>

            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Country <span class="text-danger">*</span></label>
                    <select name="country" class="form-select" required>
                        <option value="{{ session('user_currency')->id }}">{{ session('user_currency')->name }}</option>
                    </select>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"> @if(session('user_currency')->id==2)Divisioin @else Region @endif  
                        <span class="text-danger">*</span>
                    </label>                                                        
                    <select class="form-select" name="division">
                        <option value="">Choose @if(session('user_currency')->id==2)Divisioin @else Region @endif  </option>
                        @foreach (\DB::table('divisions')->where(['country_id'=>session('user_currency')->id, 'status'=>'1'])->get() as $div)
                            <option {{ old('division') == $div->id.'|'.$div->name ? 'selected' : '' }} value="{{ $div->id.'|'.$div->name }}">{{ $div->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('division')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"> @if(session('user_currency')->id==2)District @else State @endif  
                        <span class="text-danger">*</span>
                    </label>                                                        
                    <select class="form-select" name="district"> </select>
                    <span class="text-danger">{{ $errors->first('district')}}</span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">@if(session('user_currency')->id==2)City @else Area @endif  
                        <span class="text-danger">*</span>
                    </label>                                                        
                    <select class="form-select" name="city"> </select>
                    <span class="text-danger">{{ $errors->first('city')}}</span>
                </fieldset>
            </div>

            <div class="col-lg-12 col-md-12 col-12">
                <fieldset>
                    <label class="label">Address 2</label>
                    <textarea name="address" class="form-control" required>{{ old('address') }}</textarea>
                    <span class="text-danger">{{ $errors->first('address')}}</span>
                </fieldset>
            </div>
        </div>

        @if (session('user_currency')->id != 2)
            <div class="row mt-md-4">
                <div class="col-lg-6">
                    <label for="staticEmail" class="col-form-label text-md-end">Postal Code <span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-6">
                    <input type="text" name="postCode" class="form-control" value="{{ old('postCode') }}"/>
                    <span class="text-danger">{{ $errors->first('postCode')}}</span>
                </div>
            </div>
        @endif

        
    </div>

    <div class="shipping-address-area">
        <div class="form-checkbox d-flex align-items-center mt-4">
            <input class="form-check-input mt-0" id="createAcc" name="createAccount" type="checkbox" @if(old('createAccount')) checked @endif>
            <label class="form-check-label ms-2" for="createAcc">
                Create My Account
            </label>
        </div>
    </div>

@endif