
@if ($shipment !=null)
    @if ($shipment->documents !=null)
    <div>
        <?php $document = json_decode($shipment->documents); 
            $allReturns = json_decode($shipment->all_returns); 
        ?>

        <ul class="nav nav-pills pl-0" id="pills-tab" role="tablist">
            @for($i=0; $i <count($document); $i++)
            <li class="nav-item">
              <a class="nav-link @if($i==0)active @endif" id="pills-{{ $i }}-tab" data-toggle="pill" href="#pills-{{ $i }}" role="tab" aria-controls="pills-{{ $i }}" aria-selected="true">{{ $document[$i]->typeCode }}</a>
            </li>
            @endfor
            <!--<li class="nav-item">-->
            <!--  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Commercial invoice</a>-->
            <!--</li>-->
            <!--<li class="nav-item">-->
            <!--  <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Network Label</a>-->
            <!--</li>-->
            
            @if($shipment->dispatchConfirmationNumbers ==null)
                <a href="{{ route('common.create-dhl-pickup',$order->id) }}" class="btn text-success btn-sm" target="_blank" >Generate A pickup request</a>
            @else 
                <a class="btn text-success btn-sm">Dispatch Conf. Numbers: <b>{{ $shipment->dispatchConfirmationNumbers }}</b></a>
            @endif
        </ul>
        <div class="tab-content p-0" id="pills-tabContent">

            @for($i=0; $i <count($document); $i++)
            <div class="tab-pane fade @if($i==0)show active @endif " id="pills-{{ $i }}" role="tabpanel" aria-labelledby="pills-{{ $i }}-tab">
                <iframe src="data:application/pdf;base64,{{ $document[$i]->content }}" width="100%" height="600px">
                    This browser does not support PDFs. Please download the PDF to view it: <a href="data:application/pdf;base64,{{ $document[$i]->content }}">Download PDF</a>.
                </iframe>
            </div>
            @endfor
           
        </div>
      
      </div>
        
    @else 
        <?php 
            $documentData = json_decode($shipment->all_returns);
        ?>
        <h2 class="text-danger text-center bg-warning p-4"><b>DHL</b> shipment failed </h2>
        <h2 class="alert text-danger bg-light mb-0"> <b>{{ $documentData->title }}</b></h2>
        <p class="alert bg-warning p-3 pl-4 text-white">{{ $documentData->detail }}</p>
        @if (property_exists($documentData, 'additionalDetails'))
            @foreach($documentData->additionalDetails as $item) 
                <p class="alert bg-light p-3 pl-4 text-danger">{{ $item }}</p>
            @endforeach
        @endif
        <a href="{{ route('common.reorder-dhl',$order->id) }}">Try Regenerating shipment</a>
    @endif
@else   
    <h2 class="text-danger text-center bg-warning p-4">No <b>DHL</b> shipment found </h2>
    <a href="{{ route('common.reorder-dhl',$order->id) }}">Try Regenerating shipment</a>
@endif

<style>
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: #fff;
        background: #323639;
        -webkit-box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .2);
        box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .2);
    }
</style>

