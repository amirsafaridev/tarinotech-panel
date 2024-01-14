<div id="message-{{$sampleMessage->id}}" class="col-12">
    <div class="p-3 sample-message-row" data-id="{{$sampleMessage->id}}" data-message="{{$sampleMessage->message}}">
        <h4>{{ $sampleMessage->title }}</h4>
        <p class="message">{{ $sampleMessage->message }}</p>
    </div>
</div>