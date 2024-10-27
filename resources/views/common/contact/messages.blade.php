
@foreach ($contacts as $contact)
    <div class="media chat-messages">
        <div class="media-body chat-menu-content">
            <div class="">
                <p class="chat-cont"> <b>{{ $contact->subject }} </b><br>  {{ $contact->message }}</p>
            </div>
            <p class="chat-time">{{$contact->created_at->diffForHumans()}}</p>
        </div>
    </div>
@endforeach
