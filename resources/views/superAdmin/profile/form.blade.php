<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"> <h5>Textual information</h5> </div>
            <div class="card-block">
                <div class="row form-group">
                    <div class="col-sm-2 text-right">
                        <label class="col-form-label">First name</label>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name')??Auth::user()->admin->first_name }}">
                        <span class="text-danger">{{ $errors->first('first_name')}}</span>
                    </div>
                    <div class="col-sm-2 text-right">
                        <label class="col-form-label">Last name</label>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name')??Auth::user()->admin->last_name }}">
                        <span class="text-danger">{{ $errors->first('last_name')}}</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2 ">Position</label>
                    <div class="col-sm-7">
                        <input tpye="text" class="form-control" name="position" value="{{ old('position')??Auth::user()->admin->position }}">
                        <span class="text-danger">{{ $errors->first('position')}}</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2 ">Location / Address</label>
                    <div class="col-sm-10">
                        <input tpye="text" class="form-control" name="address" value="{{ old('address')??Auth::user()->admin->address }}">
                        <span class="text-danger">{{ $errors->first('address')}}</span>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-6 offset-2">
                        @if (Auth::user()->admin->is_super=='1')
                        <label class="text-right col-form-label"><span class="feather icon-check text-success"> Super-admin</span></label>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"> <h5>Profile photo <small>Optional</small> - [300x300px]</h5> </div>
            <div class="card-block">
                <div class="form-group">
                    <input type="file" class="dropify" data-height="160" name="photo" data-default-file="/storage/{{ Auth::user()->admin->photo }}" accept="image/*">
                </div>
                <div class="row form-group text-right">
                    <div class="col-sm-3"> <label class="col-form-label">Gender</label> </div>
                    <div class="col-sm-9">
                        <div class="form-group mt-2">
                            <label class="form-label">
                                <input type="radio" @if(Auth::user()->admin->sex=='male')checked @endif class="sex" name="sex" value="male"> <span></span>
                                <span>Male</span>
                            </label> &nbsp;
                            <label class="form-label">
                                <input type="radio" @if(Auth::user()->admin->sex=='female')checked @endif class="sex" name="sex" value="female">
                                <span></span><span>Female</span>
                            </label> &nbsp;
                            <label class="form-label">
                                <input type="radio" @if(Auth::user()->admin->sex=='other')checked @endif class="sex" name="sex" value="other">
                                <span></span><span>Other</span>
                            </label>
                            <span class="text-danger">{{ $errors->first('sex')}}</span>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">

    <script>
        $(function(){
            $('.dropify').dropify();
        })
    </script>
@endpush
