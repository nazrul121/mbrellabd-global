
<p class="text-center bg-default text-primary p-2 border border-info">
    Default currency is <b>BDT</b>. Set <b>country </b> currency value <b>based on</b> <b>Default</b> currency <strong>BDT</strong>. Like, <b>1 BDT</b> = <b>?</b>
</p>


<input type="hidden" name="name">
<input type="hidden" name="short_name">

<div class="form-group row">
    <div class="col-md-6">
        <label for="recipient-name">Country Name</label>
        <select name="countries" class="form-control text-uppercase">
            <option value="">Choose ShortCode</option>
            @foreach (config('app.locales') as $key=>$locale)
                <option value="{{$locale}}-{{$key}}">{{$locale}} - {{$key}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="col-md-6">
        <label for="recipient-name">Currency Code</label>
        <input type="text" class="form-control" name="currency_code" placeholder="ex. BDT, USD etc.">
    </div>

</div>


<div class="form-group row">
    <div class="col-md-6">
        <label for="recipient-name"> Currency Symbol</label>
        <input type="text" class="form-control" name="currency_symbol">
    </div>
    
    <div class="col-md-6">
        <label for="recipient-name">Currency value</label>
        <input type="text" class="form-control" name="currency_value">
    </div>
</div>

<div class="form-group row">
    <div class="col-md-6">
        <label class="col-form-label">Phone Code </label>
        <input type="text" class="form-control" name="phone_code"> <span></span>
    </div>
    <div class="col-md-6">
        <label for="recipient-name"> Zone ID</label>
        <input type="text" class="form-control" name="zone">
    </div>    
</div>

<div class="form-group pt-2">
    <label class="form-label">
        <input type="radio" class="status" name="status" value="1"> <span></span>
        <span>Published</span>
    </label>
    <label class="form-label">
        <input type="radio" class="status" name="status" value="0">
        <span></span><span>Unpublished</span>
    </label>
</div>
{{-- 
<div class="form-group bg-light p-2 formula">
    <p class="text-info">Currency convertion formula</p>
    <label class="form-label">
        <input type="radio" class="nature" name="nature" value="multiply"> <span></span>
        <span></span><span>Multiply with <code>Default currency</code></span>
    </label>
    <label class="form-label">
        <input type="radio" class="nature" name="nature" value="divide">
        <span></span><span>Divide by <code>Default currency</code></span>
    </label>
</div> --}}

<div class="form-group row pt-1">
    <label for="" class="col-form-label col-md-6 text-md-right pt-3">Upload flag [size:23x15px]
        <img src="" class="flag">
    </label>
    <div class="col-md-6">
        <input type="file" class="form-control" name="flag"> <span></span>
    </div>
</div>

