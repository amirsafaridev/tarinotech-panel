@extends('admin.master')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h5 class="mb-0">{{ $title }}</h5>
                <div class="btn-group">
                    <a href="{{ $prevMonthUrl }}" class="btn btn-outline-primary btn-sm me-2">&lt;&lt;</a>

                    
                    <span class="btn btn-light btn-sm">{{ $currentMonth }}</span>
                    <a href="{{ $nextMonthUrl }}" class="btn btn-outline-primary btn-sm ms-2">&gt;&gt;</a>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th class="bg-light">شنبه</th>
                            <th class="bg-light">یکشنبه</th>
                            <th class="bg-light">دوشنبه</th>
                            <th class="bg-light">سه شنبه</th>
                            <th class="bg-light">چهارشنبه</th>
                            <th class="bg-light">پنج شنبه</th>
                            <th class="bg-light">جمعه</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeksOfMonth as $week)
                            <tr style="height: 150px;">
                                @foreach($week as $day)
                                    <td class="{{ !$day['isCurrentMonth'] ? 'text-muted bg-light' : '' }} {{ $day['isHoliday'] ? 'bg-danger' : '' }}">
                                        <div class="d-flex flex-column h-100">
                                            <small class="text-muted">{{ $day['date']->format('%d %B') }}</small>
                                            <div class="flex-grow-1">
                                                @if($day['leaves']->isNotEmpty())
                                                    @foreach($day['leaves'] as $leave)
                                                        <div class="mb-1">
                                                            <small class="d-block text-truncate">
                                                                {{ optional($leave->user)->full_name }}
                                                                @if($leave->type === 'hourly')
                                                                <br>
                                                                ({{ $leave->start_time }} - {{ $leave->end_time }})                                                                @endif
                                                            </small>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .table-danger {
            background-color: #ffebee !important;
        }
        .text-muted {
            opacity: 0.6;
        }
    </style>
@endsection 