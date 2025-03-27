<script>
    $(document).ready(function () {
        // Number distribution chart
        const numberLabels{{ $question['id'] }} = [@foreach($question['number_distribution'] as $bucket) '{{ $bucket['range'] }}', @endforeach];
        const numberData{{ $question['id'] }} = [@foreach($question['number_distribution'] as $bucket) {{ $bucket['count'] }}, @endforeach];
        const numberCtx{{ $question['id'] }} = document.getElementById('numberChart{{ $question['id'] }}').getContext('2d');

        new Chart(numberCtx{{ $question['id'] }}, {
            type: 'bar',
            data: {
                labels: numberLabels{{ $question['id'] }},
                datasets: [{
                    label: 'توزیع پاسخ‌ها',
                    data: numberData{{ $question['id'] }},
                    backgroundColor: 'rgba(93, 135, 255, 0.7)',
                    borderColor: '#5d87ff',
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
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'محدوده‌های پاسخ'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'توزیع پاسخ‌های عددی'
                    },
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
    });
</script>

