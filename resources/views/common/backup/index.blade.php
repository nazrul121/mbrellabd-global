
@extends('common.layouts')

@section('title', 'Get backup of database')

@section('content')
<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h5>Get database backup</h5>
            </div>

            <div class="card-body">
                {{-- {{session()->get('link')}} --}}
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Action Success!</strong> {{session()->get('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                @endif

                @if(session()->has('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Action may failed!</strong> &nbsp; {{session()->get('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                @endif

                <form action="{{route('common.send-backup')}}" method="post"  class="row">@csrf 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="checkbox checkbox-info checkbox-fill d-inline">
                                <input type="checkbox" name="external_download" id="cod">
                                <label for="cod" class="cr">Download file externally in your device</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <div class="checkbox checkbox-info checkbox-fill d-inline">
                                        <input type="checkbox" name="send_mail" id="toMail">
                                        <label for="toMail" class="cr">Send database to an email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <input type="text" name="email" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-12 mt-3">
                       <div class="row float-right">
                            <button type="submit" class="btn-info btn submitForm"> <i class="fa fa-database"></i> Get Database Backup</button>
                       </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @if(session()->has('link'))
        <script>
            setTimeout(() => {
                window.open("{{ url( '/storage/backup/'.session()->get('link') )}}", '_blank');
            }, 2000);
        </script>
    @endif

    <script>
        $( document ).ready(function() {
            $('[name=send_mail]').on('change', function(){
                if ($(this).prop('checked')) {
                    $('[name=email]').prop('disabled', false)
                } else {
                    $('[name=email]').prop('disabled', true)
                }
            });

            $('.submitForm').on('click', function(){
                setInterval(() => {
                    $(this).html('Working...');
                    $(this).prop('disabled', true);
                }, 500);
            })
        });
    </script>
@endpush