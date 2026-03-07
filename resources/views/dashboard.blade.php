@extends('layouts.bootstrap')

@section('title', 'Dashboard')

@section('content')
    @php
        // Employee statistics
        $allEmployees = \App\Models\Employee::with(['contracts' => function ($q) {
            $q->orderBy('start_date', 'desc');
        }])->get();
        
        $totalEmployees = $allEmployees->filter(function ($employee) {
            $latestContract = $employee->contracts->first();
            return $latestContract && $latestContract->status !== 'Layoff';
        })->count();
        
        $activeEmployees = $allEmployees->filter(function ($employee) {
            $latestContract = $employee->contracts->first();
            if (!$latestContract || $latestContract->status === 'Layoff') {
                return false;
            }
            
            // Include Permanent employees (contract_type === 'KPP' or no end_date)
            if ($latestContract->contract_type === 'KPP' || !$latestContract->end_date) {
                return true;
            }
            
            // Exclude expired contracts
            if ($latestContract->end_date < now()) {
                return false;
            }
            
            // Exclude expiring soon (within 30 days) - they have their own category
            if ($latestContract->end_date <= now()->addDays(30)) {
                return false;
            }
            
            // Active = contract expires MORE than 30 days from now
            return true;
        })->count();
        
        $expiringEmployees = $allEmployees->filter(function ($employee) {
            $latestContract = $employee->contracts->first();
            if (!$latestContract || $latestContract->status === 'Layoff') {
                return false;
            }
            // Exclude Permanent employees (contract_type === 'KPP' or no end_date)
            if ($latestContract->contract_type === 'KPP' || !$latestContract->end_date) {
                return false;
            }
            return $latestContract->end_date 
                && $latestContract->end_date >= now() 
                && $latestContract->end_date <= now()->addDays(30);
        })->count();
        
        $expiredEmployees = $allEmployees->filter(function ($employee) {
            $latestContract = $employee->contracts->first();
            if (!$latestContract || $latestContract->status === 'Layoff') {
                return false;
            }
            // Exclude Permanent employees (contract_type === 'KPP' or no end_date)
            if ($latestContract->contract_type === 'KPP' || !$latestContract->end_date) {
                return false;
            }
            // Show only expired contracts
            return $latestContract->end_date && $latestContract->end_date < now();
        })->count();
        
        $layoffEmployees = $allEmployees->filter(function ($employee) {
            $latestContract = $employee->contracts->first();
            return $latestContract && $latestContract->status === 'Layoff';
        })->count();
        
        // Contract statistics (latest contract per employee only)
        $latestContractIds = \App\Models\Contract::latestPerEmployee();
        $totalContracts = \App\Models\Contract::whereIn('id', $latestContractIds)->count();
        $activeContracts = \App\Models\Contract::whereIn('id', $latestContractIds)->where('end_date', '>=', now())->count();
        $expiredContracts = \App\Models\Contract::whereIn('id', $latestContractIds)->where('end_date', '<', now())->count();
        $expiringContracts = \App\Models\Contract::expiringWithinDays(30);
        $recentContracts = \App\Models\Contract::with('employee')->whereIn('id', $latestContractIds)->orderBy('created_at', 'desc')->take(5)->get();
        
        // Analytics data
        $expiringIn7Days = \App\Models\Contract::expiringWithinDays(7);
        
        // Department breakdown for expired contracts (latest per employee only)
        $allContracts = \App\Models\Contract::whereIn('id', $latestContractIds)->get();
        $departmentExpired = $allContracts->filter(function($contract) {
            return $contract->end_date && $contract->end_date < now();
        })->groupBy('department')->map(function($group) {
            return $group->count();
        })->sortDesc();
        $topExpiredDept = $departmentExpired->keys()->first() ?? 'N/A';
        $topExpiredDeptCount = $departmentExpired->first() ?? 0;
        
        // Contracts by department (for bar chart)
        $contractsByDept = $allContracts->groupBy('department')->map(function($group) {
            return $group->count();
        })->sortDesc()->take(6);
        
        // // Monthly trend (compare this month vs last month active contracts)
        // $thisMonthActive = \App\Models\Contract::where('start_date', '<=', now())
        //     ->where(function($q) {
        //         $q->where('end_date', '>=', now()->startOfMonth())
        //           ->orWhereNull('end_date');
        //     })->count();
        
        // $lastMonthActive = \App\Models\Contract::where('start_date', '<=', now()->subMonth())
        //     ->where(function($q) {
        //         $q->where('end_date', '>=', now()->subMonth()->startOfMonth())
        //           ->orWhereNull('end_date');
        //     })->count();
        
        // $monthlyTrend = $thisMonthActive - $lastMonthActive;
        // $monthlyTrendPercent = $lastMonthActive > 0 ? round(($monthlyTrend / $lastMonthActive) * 100, 1) : 0;
    @endphp

    <!-- Employee Statistics Cards -->
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h4><i class="bi bi-people-fill"></i> Employee Statistics</h4>
        </div>
        
        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Total Employees</h6>
                            <h2 class="mb-0">{{ $totalEmployees }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Active Employees</h6>
                            <h2 class="mb-0">{{ $activeEmployees }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-check-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Expiring Soon</h6>
                            <h2 class="mb-0">{{ $expiringEmployees }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Expired</h6>
                            <h2 class="mb-0">{{ $expiredEmployees }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-person-x" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-dark shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Layoffs</h6>
                            <h2 class="mb-0">{{ $layoffEmployees }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-person-dash" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Statistics Cards -->
    {{-- <div class="row mb-4">
        <div class="col-12 mb-3">
            <h4><i class="bi bi-file-text"></i> Contract Statistics</h4>
        </div>
        
        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Total Contracts</h6>
                            <h2 class="mb-0">{{ $totalContracts }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-file-text" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Active Contracts</h6>
                            <h2 class="mb-0">{{ $activeContracts }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-check-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Expiring Soon</h6>
                            <h2 class="mb-0">{{ $expiringContracts->count() }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg col-6">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 small">Expired</h6>
                            <h2 class="mb-0">{{ $expiredContracts }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-x-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Analytics Section -->
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h4><i class="bi bi-graph-up"></i> Analytics</h4>
        </div>
        
        <!-- Donut Chart - Contract Status Distribution -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Contract Status Distribution</h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="statusDonutChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Bar Chart - Contracts by Department -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Contracts by Department</h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="deptBarChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Insight Summary Card -->
        {{-- <div class="col-md-4 mb-4">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Insight Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Expiring in 7 Days</small>
                                <h4 class="mb-0 text-danger">{{ $expiringIn7Days->count() }}</h4>
                            </div>
                            <div>
                                <i class="bi bi-calendar-x text-danger" style="font-size: 2rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted">Dept with Most Expired</small>
                        <h5 class="mb-0">{{ $topExpiredDept }}</h5>
                        <small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $topExpiredDeptCount }} expired contracts</small>
                    </div>
                    
                    <div>
                        <small class="text-muted">Monthly Trend</small>
                        <h5 class="mb-0">
                            @if($monthlyTrend > 0)
                                <i class="bi bi-arrow-up-circle text-success"></i>
                                <span class="text-success">+{{ $monthlyTrend }}</span>
                            @elseif($monthlyTrend < 0)
                                <i class="bi bi-arrow-down-circle text-danger"></i>
                                <span class="text-danger">{{ $monthlyTrend }}</span>
                            @else
                                <i class="bi bi-dash-circle text-secondary"></i>
                                <span class="text-secondary">0</span>
                            @endif
                        </h5>
                        <small class="text-muted">{{ $monthlyTrendPercent > 0 ? '+' : '' }}{{ $monthlyTrendPercent }}% vs last month</small>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="row">
        <!-- Expiring Contracts -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Contracts Expiring Soon
                    </h5>
                </div>
                <div class="card-body">
                    @if($expiringContracts->count() > 0)
                        <div class="list-group">
                            @foreach($expiringContracts->take(5) as $contract)
                                <a href="{{ route('contracts.edit', $contract) }}" class="list-group-item list-group-item-action {{ $contract->daysUntilExpiration() <= 7 ? 'list-group-item-danger' : 'list-group-item-warning' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $contract->employee->employee_name ?? 'N/A' }}</h6>
                                        <small>
                                            <span class="badge {{ $contract->daysUntilExpiration() <= 7 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                {{ $contract->daysUntilExpiration() }} days
                                            </span>
                                        </small>
                                    </div>
                                    <h6 class="mb-1 small">{{ $contract->job_position }}</h6>
                                    {{-- <p class="mb-1 small"></p> --}}
                                    <small class="text-muted">{{ $contract->work_location }} • Expires: {{ $contract->end_date ? $contract->end_date->format('d M Y') : 'Permanent' }}</small>
                                </a>
                            @endforeach
                        </div>
                        @if($expiringContracts->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('contracts.expiring') }}" class="btn btn-sm btn-warning">
                                    View All {{ $expiringContracts->count() }} Expiring Contracts
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="mt-2 text-muted">No contracts expiring soon</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Contracts -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Recent Contracts
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentContracts->count() > 0)
                        <div class="list-group">
                            @foreach($recentContracts as $contract)
                                <a href="{{ route('contracts.edit', $contract) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $contract->employee->employee_name ?? 'N/A' }}</h6>
                                        <small>
                                            @if(!$contract->end_date)
                                                <span class="badge bg-info">Permanent</span>
                                            @elseif($contract->end_date < now())
                                                <span class="badge bg-danger">Expired</span>
                                            @elseif($contract->isExpiringSoon())
                                                <span class="badge bg-warning text-dark">Expiring</span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        </small>
                                    </div>
                                    <p class="mb-1 small">{{ $contract->job_position }}</p>
                                    <small class="text-muted">{{ $contract->work_location }} • {{ $contract->start_date->format('d M Y') }} - {{ $contract->end_date ? $contract->end_date->format('d M Y') : 'Permanent' }}</small>
                                </a>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-primary">
                                View All Contracts
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-2 text-muted">No contracts yet</p>
                            <a href="{{ route('contracts.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Create First Contract
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                </div>
                <div class="card-body"> 
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-plus-circle"></i><br>
                                Add New Contract
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('contracts.expiring') }}" class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-exclamation-triangle"></i><br>
                                View Expiring Contracts
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('contracts.index') }}" class="btn btn-info btn-lg w-100 text-white">
                                <i class="bi bi-list-ul"></i><br>
                                View All Contracts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Plugin to display text in center of donut chart
        const centerTextPlugin = {
            id: 'centerText',
            afterDatasetsDraw(chart) {
                const { ctx, chartArea: { left, top, width, height } } = chart;
                const centerX = left + width / 2;
                const centerY = top + height / 2;
                
                ctx.save();
                const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                
                // Draw total number
                ctx.font = 'bold 32px Arial';
                ctx.fillStyle = '#333';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(total, centerX, centerY - 10);
                
                // Draw label
                ctx.font = '14px Arial';
                ctx.fillStyle = '#666';
                ctx.fillText('Contaract', centerX, centerY + 20);
                ctx.restore();
            }
        };
        
        // Donut Chart - Contract Status Distribution
        const statusCtx = document.getElementById('statusDonutChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Expiring Soon', 'Expired'],
                datasets: [{
                    data: [{{ $activeContracts }}, {{ $expiringContracts->count() }}, {{ $expiredContracts }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgb(40, 167, 69)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        return {
                                            text: label + ': ' + value,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].borderColor[i],
                                            lineWidth: 2,
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            },
            plugins: [centerTextPlugin]
        });
        
        // Bar Chart - Contracts by Department
        const deptCtx = document.getElementById('deptBarChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($contractsByDept->keys()->toArray()) !!},
                datasets: [{
                    label: 'Contracts',
                    data: {!! json_encode($contractsByDept->values()->toArray()) !!},
                    backgroundColor: 'rgba(13, 110, 253, 0.8)',
                    borderColor: 'rgb(13, 110, 253)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush
