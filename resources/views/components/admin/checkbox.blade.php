<div class="form-check form-group mb-3">
    <input @if(!empty($old)) checked @endif type="checkbox" class="form-check-input" id="{{ $identify }}" name="{{ $identify }}">
    <label class="form-check-label" for="{{ $identify }}">
        {{ $description }}
    </label>
</div>
