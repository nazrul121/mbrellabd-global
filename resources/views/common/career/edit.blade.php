
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Career</h5> </div>

            <form id="editForm" class="card-body" method="post" enctype="multipart/form-data" action="{{route('common.career.update',$career->id)}}"> @csrf
                <div class="edit_result"></div>

                <div class="form-group">
                   <div class="row">
                    <div class="col-md-7">
                        <label for="recipient-name" class="col-form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="{{$career->title}}">
                    </div>
                    <div class="col-md-5">
                        <label for="message-text" class="col-form-label">Application last date </label>
                        <input type="date" class="form-control" name="last_date" value="{{$career->last_date}}">
                    </div>
                   </div>
                </div>
                
                
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Post Description </label>
                    <textarea class="form-control description" id="description" role="15" name="description">{{$career->description}}</textarea>
                </div>
                
                <div class="bg-light p-3">
                    <div class="row">
                        <div class="col-md-12 linkField">
                            <div class="form-group">
                                <label >Meta title</label>
                                <input class="form-control" placeholder="Service Meta title" name="meta_title" value="{{$career->meta_title}}"/>
                                
                                <label >Meta description</label>
                                <textarea class="form-control" placeholder="Service Meta description" name="meta_description" rows="3">{{$career->meta_description}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group bg-light p-2">
                    <p class="text-info">Country for--</p>
                    @foreach (get_currency() as $item)
                        @php
                            $check = \DB::table('career_country')->where(['career_id'=>$career->id, 'country_id'=>$item->id]);
                            if($check->count()>0){
                                $isChecked = 'checked';
                            }else $isChecked = '';
                        @endphp
                        <label class="form-label">
                            <input type="checkbox" class="position-relative lang" style="top:3px;" name="langs[]" value="{{$item->id}}" {{$isChecked}}> <span></span>
                            <span> <img class="flag" style="height:13px;" src="{{ url($item->flag) }}"> {{$item->short_name}}</span>
                        </label> &nbsp; &nbsp; 
                    @endforeach
                    <span class="text-danger">{{ $errors->first('langs')}}</span>
                </div>
                
                
                <div class="form-group">
                    <label class="form-label">
                        <input type="radio" @if($career->status=='1')checked @endif class="status" name="status" value="1"> <span></span>
                        <span>Published</span>
                    </label> &nbsp;
                    <label class="form-label">
                        <input type="radio" @if($career->status=='0')checked @endif class="status" name="status" value="0">
                        <span></span><span>Unpublished</span>
                    </label>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>

        </div>
    </div>
    @include('common.career.modal')
</div>
@endsection


@push('scripts')


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        CKEDITOR.replace('description', {
            width: '100%',
            height: 500,
            removeButtons: 'PasteFromWord'
        });
        $("#editFormT").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html('Loading...');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success + '</div>';
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.edit_result').html(html);
                }
            });
        });

    });

</script>

@endpush
