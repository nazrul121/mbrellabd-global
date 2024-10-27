<form class="col-md-12 mt-5 bg-5 p-3 mt-md-0 mb-5 border border-danger" method="post" action="{{ route('customer.save-info', app()->getLocale()) }}"> @csrf
    <h5 class="text-center text-danger p-md-2"><i class="fas fa-info-circle"></i> Please complete your profile first</h5> 
    <hr class="bg-primary mb-4">
    <div class="form-row row mt-10">
        <div class="form-group col-md-6">
            <label for="inputState">First Name</label>
            <input type="text" class="form-control" name="first_name" required value="@if($customer !=null) {{ $customer->first_name }} @endif">
        </div>
        <div class="form-group col-md-6">
            <label for="inputState">Last Name</label>
            <input type="text" class="form-control" name="last_name" required value="@if($customer !=null){{ $customer->last_name }} @endif">
        </div>
    </div>

    <div class="form-row row mt-2">
        <label for="district" class="col-md-2 pt-md-4 text-md-end mt-2">Contact No.</label>
        <div class="form-group col-md-4 mt-4">
            <input type="text" class="form-control" name="phone" required value="{{ Auth::user()->phone }}" required>
        </div>

        <label for="district" class="col-md-1 pt-md-4 text-md-end mt-2 "> @if(session('user_currency')->id==2) Division @else Regions @endif </label>
        <div class="form-group col-md-4 mt-4">
            <select id="division" name="division" class="form-control" required>
                <option >Choose your division</option>
                @foreach ($divisions  as $div)
                    <option value="{{ $div->id }}">{{ $div->name }}</option>
                @endforeach
            </select>
        </div>
        
    </div>


    <div class="form-row row mt-2">
        <label for="district" class="col-md-1 pt-md-4 text-md-end mt-2"> @if(session('user_currency')->id==2) District @else States @endif</label>
        <div class="form-group col-md-5 mt-4">
            <select id="district" name="district" class="form-control" required>
                <option selected>Choose...</option>
            </select>
        </div>

        <label for="district" class="col-md-1 pt-md-4 text-md-end mt-2 ">City</label>
        <div class="form-group col-md-4 mt-4">
            <select id="city" name="city" class="form-control" required>
            <option selected>Choose...</option>
            </select>
        </div>
        
    </div>

    <div class="form row now mt-5">
        <label for="district" class="col-md-1 pt-md-3 text-md-end">Address</label>
        <div class="form-group col-md-11">
            <textarea name="address" class="form-control" rows="2" required=""></textarea>
        </div>
    </div>
    <div class="row mt-md-4">
        <div class="col-lg-6 text-md-end">
            <label for="staticEmail" class="col-form-label text-md-right">Postal Code</label>
        </div>
        <div class="col-lg-6">
            <input type="text" name="postCode" class="form-control" value="{{ old('postCode') }}"/>
            <span class="text-danger">{{ $errors->first('postCode')}}</span>
        </div>
    </div>

    <br><br>

    <button type="submit" value="save" name="saveInit" class="btn btn-primary float-end mb-md-5"><i class="fa fa-edit"></i> &nbsp; Update information</button>
</form>