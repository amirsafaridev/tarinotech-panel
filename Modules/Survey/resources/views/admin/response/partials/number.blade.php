@if(isset($answer->rating_value) && $answer->rating_value !== '')
<div class="p-3">
    <p class="answer-value">{{ $answer->rating_value }}</p>
</div>
@else
    @include('survey::admin.response.partials.no-answer')
@endif
