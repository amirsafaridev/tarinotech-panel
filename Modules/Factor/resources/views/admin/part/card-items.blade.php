<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="bold">اقلام فاکتور</span>
        @if(isset($shouldShowFactorLink) && $shouldShowFactorLink === true)
            <a class="btn btn-sm btn-info" href="{{ route('admin.factor.show', $items->first()->factor_id) }}">نمایش فاکتور</a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>عنوان</th>
                    <th>دسته بندی</th>
                    <th>مبلغ (ریال)</th>
                    <th>مالیات (ریال)</th>
                    <th>تخفیف (ریال)</th>
                    <th>قیمت نهایی (ریال)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->category->title }}</td>
                        <td>{{ number_format($item->price) }}</td>
                        <td>{{ number_format($item->tax_amount) }}</td>
                        <td>{{ number_format($item->discount) }}</td>
                        <td>{{ number_format($item->final_price) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


