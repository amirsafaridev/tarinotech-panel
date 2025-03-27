@if($answer->options->isNotEmpty())
<div class="p-3">
    @php
        $selectedOptionIds = $answer->options->pluck('survey_question_option_id')->toArray();
    @endphp

    @foreach($question->options as $option)
        @php
            $isSelected = in_array($option->id, $selectedOptionIds);
            $optionClass = $isSelected ? 'option-selected' : 'option-unselected';
        @endphp

        <span class="option-badge {{ $optionClass }}">
            {{ $option->option_text }}
            @if($isSelected)
                <i class="fa fa-check ms-1"></i>
            @endif
        </span>
    @endforeach
</div>
@else
<div class="p-3">
    <p class="text-muted">هیچ گزینه‌ای انتخاب نشده است.</p>
</div>
@endif
