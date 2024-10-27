
@extends('customer.layouts')

@section('title', 'My Panel | '.request()->get('system_title') )

@section('content')

<div class="trusted-section overflow-hidden">

    @if(Auth::user()->customer !=null)
 
        <div class="trusted-section-inner">
            <div class="container">
                <div class="row justify-content-center trusted-row">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="trusted-badge bg-trust-1 rounded">
                            <div class="trusted-icon">
                                <img class="icon-trusted" src="/assets/img/trusted/1.png" alt="icon-1">
                            </div>
                            <div class="trusted-content">
                                <h2 class="heading_18 trusted-heading">My Balance</h2>
                                <p class="text_16 trusted-subheading trusted-subheading-2"> {{ number_format(Auth::user()->customer->balance,2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="trusted-badge bg-trust-2 rounded">
                            <div class="trusted-icon">
                                <img class="icon-trusted" src="/assets/img/trusted/2.png" alt="icon-2">
                            </div>
                            <div class="trusted-content">
                                <h2 class="heading_18 trusted-heading">My <a href="{{ route('customer.orders',app()->getLocale()) }}">orders </a> </h2>
                                <p class="text_16 trusted-subheading trusted-subheading-2">{{ Auth::user()->customer->orders()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <table class="cart-table table mt-5">
                <thead>
                <tr> <th class="cart-caption heading_18">Billing information</th> </tr>
                </thead>
    
                <tbody class="bg-5 col-12">                                                                    
                    <tr>
                    <td class="cart-item-details">
                        <h2 class="product-title mt-3">
                            <b>Name</b>: {{ Auth::user()->customer->first_name }} {{ Auth::user()->customer->last_name }} -
                            <b>Phone</b>: {{ Auth::user()->phone }} -
                            @if(Auth::user()->email !=null)
                                <b>Email</b>: {{ Auth::user()->email }} <br>
                            @endif
    
                        </h2>
                        <p class="product-vendor text-dark mt-3">
                            <b>Area</b>: @if(Auth::user()->customer->division) {{ Auth::user()->customer->division->name }} <i class="fa fa-arrow-right"></i> @endif
                            @if(Auth::user()->customer->district) {{ Auth::user()->customer->district->name }} <i class="fa fa-arrow-right"></i>  @endif
                                @if(Auth::user()->customer->city) {{ Auth::user()->customer->city->name }} <br> @endif
    
                            <b>Address</b>: {{ Auth::user()->customer->address}}
                            <a href="{{ route('customer.account-info',app()->getLocale()) }}" class="position-relative review-submit-btn contact-submit-btn float-end"> Edit Billing</a>
                        </p>                                   
                    </td>                
                    </tr> 
                </tbody>
            </table>
        </div>
        
    @endif
</div>
@endsection


