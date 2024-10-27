<div class="row">
    <div class="col-12">
        <div class="card mb-0">
            <div class="form-group">
                <label class="form-label" for="title">District Name</label>
                <select name="district" class="form-control" id="district">
                    <?php $districts = \App\Models\District::orderBy('name')->get();?>
                    <option value="">Choose district</option>
                    @foreach ($districts as $dis)
                    <option value="{{ $dis->id }}">{{ $dis->name }}</option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="title">City [ <code>Select multiple cities</code> ]</label>
                <select name="cities[]" class="form-control cities" multiple style="height: 180px;" required></select>
            </div>
        </div>

    </div>
</div>
