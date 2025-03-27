<script>
    $(document).ready(function () {
        activeParentUl('{{ route('admin.survey.index') }}');

        @if($question->question_type == QuestionTypeEnum::Number && isset($questionData['number_distribution']) && count($questionData['number_distribution']) > 0)
        // Number distribution chart
        const numberCtx = document.getElementById('numberDistributionChart').getContext('2d');
        const numberData = {
            labels: [@foreach($questionData['number_distribution'] as $bucket) '{{ $bucket['range'] }}', @endforeach],
            datasets: [{
                label: 'توزیع پاسخ‌ها',
                data: [@foreach($questionData['number_distribution'] as $bucket) {{ $bucket['count'] }}, @endforeach],
                backgroundColor: 'rgba(93, 135, 255, 0.7)',
                borderColor: '#5d87ff',
                borderWidth: 1
            }]
        };

        new Chart(numberCtx, {
            type: 'bar',
            data: numberData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.dataset.label || '';
                                const value = context.formattedValue || '';
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        @endif

        @if(($question->question_type == QuestionTypeEnum::Single || $question->question_type == QuestionTypeEnum::Multiple) && isset($questionData['options']) && count($questionData['options']) > 0)
        // Prepare data for charts
        const labels = [@foreach($questionData['options'] as $option) '{{ $option['text'] }}', @endforeach];
        const data = [@foreach($questionData['options'] as $option) {{ $option['count'] }}, @endforeach];
        const colors = [@foreach($questionData['options'] as $option) '{{ $option['color'] }}', @endforeach];

        // Pie chart
        const ctx = document.getElementById('optionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.formattedValue || '';
                                const dataset = context.dataset;
                                const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Bar chart
        const barCtx = document.getElementById('optionsBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'تعداد پاسخ‌ها',
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.dataset.label || '';
                                const value = context.formattedValue || '';
                                const dataset = context.dataset;
                                const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        @endif

        @if(isset($questionData['monthly_responses']) && count($questionData['monthly_responses']) > 0)
        // Monthly trend chart
        const trendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        const trendData = {
            labels: [@foreach($questionData['monthly_responses'] as $month => $count) '{{ $month }}', @endforeach],
            datasets: [{
                label: 'تعداد پاسخ‌ها',
                data: [@foreach($questionData['monthly_responses'] as $month => $count) {{ $count }}, @endforeach],
                borderColor: '#5d87ff',
                backgroundColor: 'rgba(93, 135, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        };

        new Chart(trendCtx, {
            type: 'line',
            data: trendData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
        @endif
    });
</script>