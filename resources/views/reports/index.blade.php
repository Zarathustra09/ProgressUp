@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 text-white">Branch Occupancy Report</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="studentsPerRoomChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 text-white">Branch Attendance Rate Report</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Present Rate (%)</th>
{{--                                    <th>Absent Rate (%)</th>--}}
                                    <th>Late Rate (%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($branchAttendanceRates as $rate)
                                    <tr>
                                        <td>{{ $rate['branch'] }}</td>
                                        <td>{{ number_format($rate['present_rate'], 2) }}%</td>
{{--                                        <td>{{ number_format($rate['absent_rate'], 2) }}%</td>--}}
                                        <td>{{ number_format($rate['late_rate'], 2) }}%</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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
            });
        </script>
    @endpush
@endsection
