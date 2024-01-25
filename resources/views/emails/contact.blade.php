<div class="card-body no-padding">
    <div class="mailbox-read-info mb-5">
        <h5>From: {{ $contactRequest->first_name }} {{ $contactRequest->last_name }} ({{$contactRequest->email}})</h5>
        <span class="mailbox-read-time pull-right">
            {{ date('F d, Y (H:i)',strtotime('now')) }}
        </span>
    </div>
    <div class="mailbox-read-message">
        <p>{{ $contactRequest->message }}</p>
    </div>
</div>
