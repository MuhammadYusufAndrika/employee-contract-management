@extends('layouts.bootstrap')

@section('title', 'Employees')

@section('content')
<div class="emp-container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="emp-page-title">
                <i class="bi bi-people-fill"></i> Employee List
            </h2>
        </div>
    </div>

    <!-- Filters -->
    <div class="card emp-filter-card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employees.index') }}" class="row g-3">
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
                                <tr class="emp-table-row">
                                    <td class="emp-td-no">{{ $index + 1 }}</td>
                                    <td class="emp-td-name">
                                        <strong>{{ $employee->employee_name }}</strong>
                                    </td>
                                    <td class="emp-td-nik">{{ $employee->nik }}</td>
                                    <td class="emp-td-position">{{ $employee->job_position }}</td>
                                    <td class="emp-td-department">
                                        <span class="emp-badge emp-badge-department">{{ $employee->department }}</span>
                                    </td>
                                    <td class="emp-td-location">{{ $employee->work_location }}</td>
                                    <td class="emp-td-status">
                                        @if(!$employee->end_date)
                                            <span class="emp-badge emp-badge-permanent">
                                                <i class="bi bi-infinity"></i> Permanent
                                            </span>
                                        @elseif($employee->end_date < now())
                                            <span class="emp-badge emp-badge-expired">
                                                <i class="bi bi-x-circle"></i> Expired
                                            </span>
                                        @elseif($employee->isExpiringSoon())
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
                                        <a href="{{ route('employees.show', $employee) }}" 
                                           class="btn emp-btn-view btn-sm"
                                           title="View Employee Details">
                                            <i class="bi bi-eye"></i> View
                                        </a>
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
    background-color: #FF6B00;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 107, 0, 0.3);
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
