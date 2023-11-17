<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="bold">اغلام فاکتور</span>
        <h4 class="factor-item-price p-0 m-0">{{ number_format($item->final_price) }}</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <td>عنوان</td>
                    <td>{{ $item->title }}</td>
                    <td>دسته بندی</td>
                    <td>{{ $item->category->title }}</td>
                </tr>

                <tr>
                    <td>مبلغ (ریال)</td>
                    <td>{{ number_format($item->price) }}</td>
                    <td>مالیات (ریال)</td>
                    <td>{{ number_format($item->tax_amount) }}</td>
                </tr>

                <tr>
                    <td>تخفیف (ریال)</td>
                    <td>{{ number_format($item->discount) }}</td>
                    <td>قیمت نهایی (ریال)</td>
                    <td>{{ number_format($item->final_price) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
