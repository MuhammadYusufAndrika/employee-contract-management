@extends('layouts.bootstrap')

@section('title', 'Employees with Expired Contracts')

@section('content')
<div class="emp-container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="emp-page-title text-danger">
                    <i class="bi bi-person-x"></i> Employees with Expired Contracts
                </h2>
            </div>
        </div>
    </div>

    <!-- Alert for Expired Contracts -->
    <div class="alert alert-danger border-danger shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
            <div>
                <strong>Urgent Action Required!</strong><br>
                These employees have expired contracts and need immediate renewal. They cannot continue working without valid contracts.
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card emp-filter-card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employees.expired') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="emp-search" class="form-label emp-filter-label">Search</label>
                    <input type="text" 
                           class="form-control emp-search-input" 
                           id="emp-search"
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Name, NIK, or Contract Number">
                </div>
                <div class="col-md-3">
                    <label for="emp-department" class="form-label emp-filter-label">Department</label>
                    <select class="form-select emp-filter-select" id="emp-department" name="department">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="emp-work-location" class="form-label emp-filter-label">Work Location</label>
                    <select class="form-select emp-filter-select" id="emp-work-location" name="work_location">
                        <option value="">All Locations</option>
                        @foreach($workLocations as $location)
                            <option value="{{ $location }}" {{ request('work_location') == $location ? 'selected' : '' }}>
                                {{ $location }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn emp-btn-filter w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="card emp-table-card shadow-sm">
        <div class="card-body">
            @if($employees->count() > 0)
                <div class="table-responsive emp-table-responsive">
                    <table class="table emp-data-table table-hover">
                        <thead class="emp-table-header table-danger">
                            <tr>
                                <th class="emp-th-no">No</th>
                                <th class="emp-th-name">Name</th>
                                <th class="emp-th-nik">NIK</th>
                                <th class="emp-th-position">Job Position</th>
                                <th class="emp-th-department">Department</th>
                                <th class="emp-th-location">Work Location</th>
                                <th class="emp-th-expired">Expired Date</th>
                                <th class="emp-th-overdue">Days Overdue</th>
                                <th class="emp-th-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="emp-table-body">
                            @foreach($employees as $index => $employee)
                                @php
                                    $latestContract = $employee->contracts->sortByDesc('start_date')->first();
                                    $daysOverdue = $latestContract && $latestContract->end_date 
                                        ? (int) abs(now()->diffInDays($latestContract->end_date, false)) 
                                        : 0;
                                @endphp
                                <tr class="emp-table-row table-danger-light">
                                    <td class="emp-td-no">{{ $index + 1 }}</td>
                                    <td class="emp-td-name">
                                        <strong class="text-danger">{{ $employee->employee_name }}</strong>
                                    </td>
                                    <td class="emp-td-nik">{{ $employee->nik }}</td>
                                    <td class="emp-td-position">{{ $latestContract->job_position ?? '-' }}</td>
                                    <td class="emp-td-department">
                                        @if($latestContract && $latestContract->department)
                                            <span class="emp-badge emp-badge-department">{{ $latestContract->department }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="emp-td-location">{{ $latestContract->work_location ?? '-' }}</td>
                                    <td class="emp-td-expired">
                                        <strong class="text-danger">{{ $latestContract->end_date ? $latestContract->end_date->format('d M Y') : '-' }}</strong>
                                    </td>
                                    <td class="emp-td-overdue">
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-circle"></i> {{ $daysOverdue }} days
                                        </span>
                                    </td>
                                    <td class="emp-td-actions">
                                        <div class="btn-group" role="group">
                                            @if(auth()->user()->canModify())
                                                <a href="{{ route('contracts.renew', $latestContract) }}" 
                                                   class="btn btn-danger btn-sm"
                                                   title="Renew Contract - URGENT!">
                                                    <i class="bi bi-arrow-clockwise"></i> Renew Now
                                                </a>
                                            @endif
                                            <a href="{{ route('employees.show', $employee) }}" 
                                               class="btn emp-btn-view btn-sm"
                                               title="View Employee Details">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            @if(auth()->user()->canModify())
                                                <a href="{{ route('employees.edit', $employee) }}" 
                                                   class="btn emp-btn-edit btn-sm"
                                                   title="Edit Employee">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="{{ route('layoffs.create', ['employee_id' => $employee->id]) }}" 
                                                   class="btn emp-btn-layoff btn-sm"
                                                   title="Process Layoff"
                                                   onclick="return confirm('Are you sure you want to process layoff for {{ $employee->employee_name }}?');">
                                                    <i class="bi bi-person-x"></i> Layoff
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-info-circle"></i> 
                    Showing <strong>{{ $employees->count() }}</strong> employee(s) with expired contracts.
                    Please process renewals immediately to ensure compliance.
                </div>
            @else
                <div class="emp-empty-state text-center py-5">
                    <i class="bi bi-check-circle text-success emp-empty-icon" style="font-size: 4rem;"></i>
                    <p class="emp-empty-text mt-3 text-success">
                        <strong>Great!</strong> No employees with expired contracts.
                    </p>
                    <p class="text-muted">All employee contracts are current and valid.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Employee Module Unique Styles */
.emp-container {
    padding: 20px;
}

.emp-page-title {
    color: #003DA5;
    font-weight: 700;
    margin-bottom: 0;
}

.emp-filter-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.emp-filter-label {
    font-weight: 600;
    color: #003DA5;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.emp-search-input {
    border: 1px solid #003DA5;
    border-radius: 6px;
}

.emp-search-input:focus {
    border-color: #003DA5;
    box-shadow: 0 0 0 0.2rem rgba(0, 61, 165, 0.25);
}

.emp-filter-select {
    border: 1px solid #003DA5;
    border-radius: 6px;
}

.emp-filter-select:focus {
    border-color: #003DA5;
    box-shadow: 0 0 0 0.2rem rgba(0, 61, 165, 0.25);
}

.emp-btn-filter {
    background-color: #003DA5;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    padding: 0.5rem 1rem;
}

.emp-btn-filter:hover {
    background-color: #002d7a;
    color: white;
}

.emp-table-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.emp-data-table {
    margin-bottom: 0;
}

.emp-table-header {
    background-color: #003DA5;
    color: white;
}

.emp-table-header.table-danger {
    background-color: #dc3545;
}

.emp-table-header th {
    font-weight: 600;
    border: none;
    padding: 1rem 0.75rem;
    font-size: 0.9rem;
}

.emp-table-row:hover {
    background-color: #f8f9fa;
}

.emp-table-row.table-danger-light {
    background-color: #f8d7da;
}

.emp-table-row.table-danger-light:hover {
    background-color: #f1c2c6;
}

.emp-td-no {
    width: 60px;
    font-weight: 600;
}

.emp-td-name {
    min-width: 180px;
}

.emp-td-nik {
    min-width: 120px;
}

.emp-td-position,
.emp-td-department,
.emp-td-location {
    min-width: 140px;
}

.emp-td-status {
    min-width: 120px;
}

.emp-td-expired,
.emp-td-overdue {
    min-width: 120px;
}

.emp-td-actions {
    min-width: 200px;
}

.emp-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

.emp-badge-department {
    background-color: #e3f2fd;
    color: #1976d2;
}

.emp-badge-active {
    background-color: #d4edda;
    color: #155724;
}

.emp-badge-permanent {
    background-color: #cfe2ff;
    color: #084298;
}

.emp-badge-expiring {
    background-color: #fff3cd;
    color: #856404;
}

.emp-badge-expired {
    background-color: #f8d7da;
    color: #721c24;
}

.emp-badge-laidoff {
    background-color: #6c757d;
    color: white;
}

.emp-btn-view {
    background-color: #003DA5;
    color: white;
    border: none;
}

.emp-btn-view:hover {
    background-color: #002d7a;
    color: white;
}

.emp-btn-edit {
    background-color: #ffc107;
    color: #000;
    border: none;
}

.emp-btn-edit:hover {
    background-color: #e0a800;
    color: #000;
}

.emp-btn-layoff {
    background-color: #6c757d;
    color: white;
    border: none;
}

.emp-btn-layoff:hover {
    background-color: #5a6268;
    color: white;
}

.emp-btn-delete {
    background-color: #dc3545;
    color: white;
    border: none;
}

.emp-btn-delete:hover {
    background-color: #c82333;
    color: white;
}

.emp-empty-state {
    text-align: center;
}

.emp-empty-icon {
    font-size: 4rem;
    color: #6c757d;
}

.emp-empty-text {
    font-size: 1.2rem;
    color: #6c757d;
    margin-top: 1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .emp-table-responsive {
        overflow-x: auto;
    }
    
    .emp-td-actions .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .emp-td-actions .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }
}
</style>
@endsection
