<script>
    $(document).ready(function () {
        // Prepare data for charts
        const labels{{ $question['id'] }} = [@foreach($question['options'] as $option) '{{ $option['text'] }}', @endforeach];
        const data{{ $question['id'] }} = [@foreach($question['options'] as $option) {{ $option['count'] }}, @endforeach];
        const colors{{ $question['id'] }} = [@foreach($question['options'] as $option) '{{ $option['color'] }}', @endforeach];

        // Create chart for all questions tab
        const ctx{{ $question['id'] }} = document.getElementById('questionChart{{ $question['id'] }}').getContext('2d');
        new Chart(ctx{{ $question['id'] }}, {
            type: 'pie',
            data: {
                labels: labels{{ $question['id'] }},
                datasets: [{
                    data: data{{ $question['id'] }},
                    backgroundColor: colors{{ $question['id'] }},
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

        // Create chart for choice questions tab
        const choiceCtx{{ $question['id'] }} = document.getElementById('choiceQuestionChart{{ $question['id'] }}');
        if (choiceCtx{{ $question['id'] }}) {
            new Chart(choiceCtx{{ $question['id'] }}, {
                type: 'doughnut',
                data: {
                    labels: labels{{ $question['id'] }},
                    datasets: [{
                        data: data{{ $question['id'] }},
                        backgroundColor: colors{{ $question['id'] }},
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 10
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
        }
    });
</script>
