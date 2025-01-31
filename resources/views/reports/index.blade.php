@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 text-white" >Room Occupancy Report</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="studentsPerRoomChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 text-white">Another Chart</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="anotherChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var ctx1 = document.getElementById('studentsPerRoomChart').getContext('2d');
                var chart1 = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: @json($rooms->pluck('name')),
                        datasets: [
                            {
                                label: 'Students',
                                data: @json($rooms->pluck('students_count')),
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Staff',
                                data: @json($rooms->pluck('staff_count')),
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Parents',
                                data: @json($rooms->pluck('parents_count')),
                                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Occupants'
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: false
                            }
                        }
                    }
                });

                var ctx2 = document.getElementById('anotherChart').getContext('2d');
                var chart2 = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: @json($rooms->pluck('name')),
                        datasets: [
                            {
                                label: 'Example Data',
                                data: @json($rooms->pluck('example_data')),
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Example Data'
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: false
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
