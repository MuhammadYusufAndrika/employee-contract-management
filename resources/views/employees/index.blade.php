@extends('layouts.bootstrap')

@section('title', 'Employees')

@section('content')
<div class="emp-container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="emp-page-title">
                    <i class="bi bi-people-fill"></i> Employee List
                </h2>
                @if(auth()->user()->canModify())
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-1"></i>Add New Employee
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card emp-filter-card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employees.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="emp-search" class="form-label emp-filter-label">Search</label>
                    <input type="text" 
                           class="form-control emp-search-input" 
                           id="emp-search"
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Name, NIK, or Contract Number">
                </div>
                <div class="col-md-2">
                    <label for="emp-status" class="form-label emp-filter-label">Status</label>
                    <select class="form-select emp-filter-select" id="emp-status" name="status">
                        <option value="">Active Only</option>
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All (Active & Expired)</option>
                        {{-- <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option> --}}
                        <option value="permanent" {{ request('status') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="expiring_soon" {{ request('status') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
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
                <div class="col-md-2">
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
                <div class="col-md-3 d-flex align-items-end">
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
                        <thead class="emp-table-header">
                            <tr>
                                <th class="emp-th-no">No</th>
                                <th class="emp-th-name">Name</th>
                                <th class="emp-th-nik">NIK</th>
                                <th class="emp-th-position">Job Position</th>
                                <th class="emp-th-department">Department</th>
                                <th class="emp-th-location">Work Location</th>
                                <th class="emp-th-status">Status</th>
                                <th class="emp-th-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="emp-table-body">
                            @foreach($employees as $index => $employee)
                                @php
                                    $latestContract = $employee->contracts->sortByDesc('start_date')->first();
                                @endphp
                                <tr class="emp-table-row">
                                    <td class="emp-td-no">{{ $index + 1 }}</td>
                                    <td class="emp-td-name">
                                        <strong>{{ $employee->employee_name }}</strong>
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
                                    <td class="emp-td-status">
                                        @if($employee->status === 'Layoff')
                                            <span class="emp-badge emp-badge-laidoff">
                                                <i class="bi bi-person-dash-fill"></i> Laid Off
                                            </span>
                                        @elseif(!$latestContract)
                                            <span class="emp-badge emp-badge-expired">
                                                <i class="bi bi-dash-circle"></i> No Contract
                                            </span>
                                        @elseif($latestContract->contract_type === 'KPP' || !$latestContract->end_date)
                                            <span class="emp-badge emp-badge-permanent">
                                                <i class="bi bi-infinity"></i> Permanent
                                            </span>
                                        @elseif($latestContract->end_date < now())
                                            <span class="emp-badge emp-badge-expired">
                                                <i class="bi bi-x-circle"></i> Expired
                                            </span>
                                        @elseif($latestContract->end_date <= now()->addDays(30))
                                            <span class="emp-badge emp-badge-expiring">
                                                <i class="bi bi-exclamation-triangle"></i> Expiring Soon
                                            </span>
                                        @else
                                            <span class="emp-badge emp-badge-active">
                                                <i class="bi bi-check-circle"></i> Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="emp-td-actions">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('employees.show', $employee) }}" 
                                               class="btn emp-btn-view btn-sm"
                                               title="View Employee Details">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            @if(auth()->user()->canModify())
                                                @if(!$employee->isLaidOff())
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
                                                @else
                                                    <span class="badge bg-danger">Laid Off</span>
                                                @endif
                                                @if(!$employee->isLaidOff())
                                                    <form action="{{ route('employees.destroy', $employee) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this employee? All associated contracts will also be deleted.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn emp-btn-delete btn-sm"
                                                                title="Delete Employee">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="emp-empty-state text-center py-5">
                    <i class="bi bi-inbox emp-empty-icon"></i>
                    <p class="emp-empty-text mt-3">No employees found.</p>
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
    color: #333;
    font-size: 0.9rem;
}

.emp-search-input,
.emp-filter-select {
    border: 1px solid #d0d0d0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
}

.emp-search-input:focus,
.emp-filter-select:focus {
    border-color: #003DA5;
    box-shadow: 0 0 0 0.2rem rgba(0, 61, 165, 0.25);
}

.emp-btn-filter {
    background-color: #003DA5;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.emp-btn-filter:hover {
    background-color: #002060;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 61, 165, 0.3);
}

.emp-table-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.emp-data-table {
    margin-bottom: 0;
}

.emp-table-header {
    background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
    color: white;
}

.emp-table-header th {
    border: none;
    padding: 1rem;
    font-weight: 600;
    font-size: 0.9rem;
    vertical-align: middle;
}

.emp-th-no { width: 60px; }
.emp-th-name { width: 200px; }
.emp-th-nik { width: 150px; }
.emp-th-position { width: 180px; }
.emp-th-department { width: 150px; }
.emp-th-location { width: 150px; }
.emp-th-status { width: 130px; }
.emp-th-actions { width: 100px; text-align: center; }

.emp-table-body tr {
    transition: background-color 0.2s ease;
}

.emp-table-row:hover {
    background-color: #f8f9fa;
}

.emp-td-no,
.emp-td-nik,
.emp-td-name,
.emp-td-position,
.emp-td-department,
.emp-td-location,
.emp-td-status,
.emp-td-actions {
    padding: 0.875rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.emp-td-name strong {
    color: #003DA5;
    font-weight: 600;
}

.emp-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.emp-badge-department {
    background-color: #e3f2fd;
    color: #1976d2;
}

.emp-badge-permanent {
    background-color: #e0f2f1;
    color: #00796b;
}

.emp-badge-active {
    background-color: #e8f5e9;
    color: #388e3c;
}

.emp-badge-expiring {
    background-color: #fff3e0;
    color: #f57c00;
}

.emp-badge-expired {
    background-color: #ffebee;
    color: #c62828;
}

.emp-btn-view {
    background-color: #003DA5;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.emp-btn-view:hover {
    background-color: #002060;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 61, 165, 0.3);
}

.emp-btn-edit {
    background-color: #FF6B00;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.emp-btn-edit:hover {
    background-color: #e55f00;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 107, 0, 0.3);
}

.emp-btn-delete {
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.emp-btn-delete:hover {
    background-color: #c82333;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.emp-btn-layoff {
    background: linear-gradient(135deg, #f57c00 0%, #d84315 100%);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.emp-btn-layoff:hover {
    background: linear-gradient(135deg, #e55f00 0%, #c62828 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(245, 124, 0, 0.4);
}

.emp-badge-laidoff {
    background-color: #ffebee;
    color: #c62828;
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
    border: 1px solid #ef9a9a;
}

.emp-td-actions .btn-group {
    gap: 0.25rem;
}

.emp-td-actions .btn-group .btn {
    white-space: nowrap;
}

.emp-empty-state {
    padding: 4rem 2rem;
}

.emp-empty-icon {
    font-size: 4rem;
    color: #ccc;
}

.emp-empty-text {
    color: #6c757d;
    font-size: 1.1rem;
}

.emp-pagination-wrapper {
    display: flex;
    justify-content: center;
}

.emp-pagination-wrapper .pagination {
    gap: 0.5rem;
}

.emp-pagination-wrapper .page-link {
    border-radius: 6px;
    border: 1px solid #dee2e6;
    color: #003DA5;
    padding: 0.5rem 0.75rem;
}

.emp-pagination-wrapper .page-link:hover {
    background-color: #003DA5;
    color: white;
    border-color: #003DA5;
}

.emp-pagination-wrapper .page-item.active .page-link {
    background-color: #003DA5;
    border-color: #003DA5;
}

.emp-pagination-wrapper .page-item:not(:first-child):not(:last-child) {
    display: none;
}

.emp-pagination-wrapper .page-link svg {
    display: none;
}

@media (max-width: 992px) {
    .emp-table-responsive {
        overflow-x: auto;
    }
    
    .emp-data-table {
        min-width: 900px;
    }
}

@media (max-width: 768px) {
    .emp-container {
        padding: 10px;
    }
    
    .emp-page-title {
        font-size: 1.5rem;
    }
    
    .emp-filter-card .col-md-2,
    .emp-filter-card .col-md-3,
    .emp-filter-card .col-md-4 {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection
