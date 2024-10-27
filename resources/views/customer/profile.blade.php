@extends('customer.layouts')

@section('title', 'My profile')

@section('content')

    <div class="accordion-item">
        @if(session()->get('message')) 
            <p class="alert bg-5 text-success text-center">{{session()->get('message')}}</p>
        @endif

       
        <h2 class="accordion-header" id="headingOne">
            <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne">Billing information</button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#myAccordion">
            <form class="card-body" action="{{ route('customer.update.account', app()->getLocale()) }}" method="post">@csrf
                @include('customer.includes.profile-form')

                <div class="row mt-4">
                    <div class="col-md-12">
                        <button class="btuton float-end btn-primary" type="submit"> <i class="fa fa-edit"></i> &nbsp; Update profile info</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $(function(){
            get_district({{ Auth::user()->customer->division_id }})
            get_cities({{ Auth::user()->customer->district_id }})

            setTimeout(function() {
                $('[name=district] option[value="{{ Auth::user()->customer->district_id }}"]').prop('selected', true);
                $('[name=city] option[value="{{ Auth::user()->customer->city_id }}"]').prop('selected', true);
            }, 500);
        })

        function get_district(id){
            $.ajax({
                url:url+"/get-districts/"+ id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=district]").append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        }

        function get_cities(id){
            $.ajax({ url:url+"/get-cities/"+ id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=city]").append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        }
    </script>
@endpush
