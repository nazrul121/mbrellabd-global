<table class="tableCity table" style="width:100%">
    <thead>
        <tr>
            <th class="text-left uppercase">District</th>
            <th class="text-left uppercase">City Name</th>
        @foreach($division->districts()->get() as $dis)
        <tr>
            <td style="width:20%">{{ $dis->name }} - ({{ $dis->cities()->count() }}) </td>
            <td colspan="2" style="width:80%">
               <table class="table">
                @foreach($dis->cities()->get() as $city)
                    <tr class="row{{ $city->id }}">
                        <td><div style="width:100%" class="cityEditResult{{ $city->id }}"></div>
                            <span class="oldName{{ $city->id }}">{{ $city->name }}</span>
                            <input type="text" class="form-control cityName{{ $city->id }}" data-id="{{ $city->id }}" value="{{ $city->name }}" style="display: none">
                        </td>
                        <td class="text-right">
                            <input type="hidden" class="district_id{{ $city->id }}" value="{{ $dis->id }}">
                            <button class="updateCity{{ $city->id }} updateCityF{{ $city->id }} btn btn-info mt-4" style="display:none"data-id="{{ $city->id }}" >Update</button>
                            <div class="editAera{{ $city->id }}">
                                @if(check_access('edit-city'))
                                <a href="javaScript:;" class="btn btn-sm btn-info editCity" data-id="{{ $city->id }}"><span class="fa fa-edit"></span></a> @endif
                                @if(check_access('delete-city'))
                                <a href="javaScript:;" class="btn btn-sm btn-danger deleteCity" data-id="{{ $city->id }}"><span class="fa fa-trash"></span></a>@endif
                            </div>
                        </td>
                    </tr>
                @endforeach
               </table>
            </td>
        </tr>
        @endforeach
    </thead>
</table>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        $('.tableCity').on('click', '.deleteCity' ,function(e){
            if(confirm('Are you sure to remove the banner record??')){
                let id = $(this).data('id');
                $.get(url+'/common/area/delete-city/'+id, function (data, textStatus, jqXHR) {
                    if(data==1){
                        $('.row'+id).remove();
                    }else alert('Cannot be deleted because of Integrity constraint violation')

                });
            }
        });

        $('.tableCity').on('click', '.editCity' ,function(e){
            let id = $(this).data('id');
            $('.cityName'+id).show();
            $('.updateCity'+id).show();
            $('.editAera'+id).hide();
            $('.updateCityF'+id).on('click',function(){
                let name = $('.cityName'+id).val();
                let district_id = $('.district_id'+id).val();

                $.ajax({
                    type: "get",url: "{{ route('update-city') }}",
                    data: {id: id, name:name,district_id:district_id},
                    success: function(data){
                        $('.oldName'+id).text(name);
                        $('.cityEditResult'+id).html(data);
                        $('.cityName'+id).css('display','none');
                        $('.updateCity'+id).css('display','none');
                        $('.editAera'+id).css('display','block');
                        setTimeout(function() { $('.cityEditResult'+id).text('');}, 1000);
                    }
                });
            })
        });

    });
</script>

