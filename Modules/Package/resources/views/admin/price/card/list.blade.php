<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">لیست قیمت ها</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>قیمت</th>
                    <th>از تاریخ</th>
                    <th>تا تاریخ</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @if($prices->isNotEmpty())
                    @foreach($prices as $price)
                        <tr>
                            <td>{{ $price->id }}</td>
                            <td>{{  number_format($price->price ?? 0) }}</td>
                            <td>
                                {{ verta($price->start_at)->format(formatJalaliDate()) }}
                            </td>
                            <td>
                                @if(is_null($price->end_at))
                                    <span>تا هم اکنون</span>
                                @else
                                    {{ verta($price->end_at)->format(formatJalaliDate()) }}
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="{{ route('admin.package-price.edit',[$price->package_id,$price->id]) }}">ویرایش</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

        </div>
    </div>
</div>