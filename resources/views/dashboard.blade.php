@extends('layouts.bootstrap')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
    </div>

    @php
        $totalContracts = \App\Models\Contract::count();
        $activeContracts = \App\Models\Contract::where('end_date', '>=', now())->count();
        $expiredContracts = \App\Models\Contract::where('end_date', '<', now())->count();
        $expiringContracts = \App\Models\Contract::expiringWithinDays(30);
        $recentContracts = \App\Models\Contract::orderBy('created_at', 'desc')->take(5)->get();
    @endphp

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Total Contracts</h6>
                            <h2 class="mb-0">{{ $totalContracts }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-file-text" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Active Contracts</h6>
                            <h2 class="mb-0">{{ $activeContracts }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Expiring Soon</h6>
                            <h2 class="mb-0">{{ $expiringContracts->count() }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Expired</h6>
                            <h2 class="mb-0">{{ $expiredContracts }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-x-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                        <h6 class="mb-1">{{ $contract->employee_name }}</h6>
                                        <small>
                                            <span class="badge {{ $contract->daysUntilExpiration() <= 7 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                {{ $contract->daysUntilExpiration() }} days
                                            </span>
                                        </small>
                                    </div>
                                    <p class="mb-1 small">{{ $contract->department }} - {{ $contract->work_location }}</p>
                                    <small>Expires: {{ $contract->end_date->format('d M Y') }}</small>
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
                                        <h6 class="mb-1">{{ $contract->employee_name }}</h6>
                                        <small>
                                            @if($contract->end_date < now())
                                                <span class="badge bg-danger">Expired</span>
                                            @elseif($contract->isExpiringSoon())
                                                <span class="badge bg-warning text-dark">Expiring</span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        </small>
                                    </div>
                                    <p class="mb-1 small">{{ $contract->department }} - {{ $contract->work_location }}</p>
                                    <small class="text-muted">{{ $contract->start_date->format('d M Y') }} - {{ $contract->end_date->format('d M Y') }}</small>
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
</div>
@endsection
