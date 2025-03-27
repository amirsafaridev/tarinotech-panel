@if(isset($answer->answer_text) && $answer->answer_text !== '')
<div class="p-3">
    <p>{{ $answer->answer_text }}</p>
</div>
@else
    @include('survey::admin.response.partials.no-answer')
@endif
