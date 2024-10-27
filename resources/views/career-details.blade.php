@extends('layouts.app')

@section('title', $career->title.' | Career | '.request()->get('system_title'))

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{$career->title.' | Career | '.request()->get('system_title')}}" />
@endpush

@section('content')

<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="{{route('home')}}">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000"></path>
                    </g>
                </svg>
            </li>
            <li><a href="{{route('career',app()->getLocale())}}">Career</a> </li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000"></path>
                    </g>
                </svg>
            </li>
            <li>{{$career->title}}</li>
        </ul>
    </div>
</div>

<div class="article-page">
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12 p-4 m-2 border border-warning">
                <header class="jumbotron">
                    <a href="#"><h1>{{$career->title}}</h1></a>
                    <p>Deadline: {{date('d M, Y',strtotime($career->last_date))}}</p>
                </header> <hr>

                {!! $career->description !!}

                <button class="btn-primary float-end apply"> Apply job Now</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="trackingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" id="formArea">
       
        <div class="modal-content">
            <div class="bg-warning p-3">
                <h4 class="modal-title">{{$career->title}}
                <button type="button" class="close float-end" data-dismiss="modal">&times;</button> </h4>
            </div>
            <div class="container pt-md-3 border border-warning border-top-0 border-2">
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('save-career-applicant',$career->id) }}" id="careerForm">@csrf
                    <div class="form-group row">
                        <div class="add_result col-md-10 offset-md-1"></div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-10 col-sm-12 offset-md-1">
                            <label>Name:</label>
                            <input type="text" class="form-control" placeholder="Enter full Name *" name="name">
                            <span class="text-danger">{{$errors->first('name')}}</span>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-10 col-sm-12 offset-md-1">
                            <label>Phone No:</label>
                            <input type="text" class="form-control" placeholder="Enter phone*" name="phone">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-10 col-sm-12 offset-md-1">
                            <label >Email Address:</label>
                            <input type="email" class="form-control" placeholder="Enter email" name="email">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-10 col-sm-12 offset-md-1">
                            <label>Cover letter:</label>
                            <textarea class="form-control" placeholder="Type Cover letter *" name="cover_letter" rows="10">{{old('cover_letter')}}</textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-9 col-sm-12 offset-md-1">
                            <label>Upload CV/Resume:</label>
                            <input type="file" class="form-control" name="cv_resume">
                        </div>
                    </div>

                    <div class="form-group row mb-3">        
                      <div class="col-md-10 mt-3 mb-5 offset-md-1">
                        <button type="submit" class="btn btn-primary float-end">Apply the job</button>
                      </div>
                    </div>
                </form>
            </div>
       
        </div>
    </div>
</div>

@endsection


@push('scripts')
    <script>
        $(function(){
            $('.apply').on('click', function(){
                $('#trackingModal').modal('show');
                $('#careerForm').trigger("reset");
            })

            $("#careerForm").submit(function(event) {
                event.preventDefault();
                $("[type='submit']").html(' Loading...');$('.add_result').html('');
                $("[type='submit']").prop('disabled',true);

                document.getElementById("formArea").scrollIntoView( {behavior: "smooth" })
                

                var form = $(this);var url = form.attr('action');
                var html = '';
                $.ajax({
                    url:url, method:"post", data: new FormData(this),
                    contentType: false,cache:false, processData: false,
                    dataType:"json",
                    success:function(data){
                   
                        if(data.errors) {
                            html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning!  </strong>';
                            for(var count = 0; count < data.errors.length; count++)
                            { html +=  data.errors[count];break;}
                            html += '</div>';
                        }
                        if(data.success){
                            html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                            setTimeout(function() { 
                                $('#trackingModal').modal('hide');
                            }, 1000);
                        }
                        if(data.alert){
                            html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-info">Alert! </strong> ' + data.alert +'</div>';
                        }
                        $("[type='submit']").text('Apply the job');
                        $("[type='submit']").prop('disabled',false);
                        $('.add_result').html(html);
                    }
                });
            });
        })
    </script>
@endpush