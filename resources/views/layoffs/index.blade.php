@extends('layouts.bootstrap')

@section('title', 'Layoff Records')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-danger mb-0">
                    <i class="bi bi-person-x-fill me-2"></i>Layoff Records
                    <span class="badge bg-danger ms-2">{{ $layoffs->total() }}</span>
                </h2>
                @if(auth()->user()->canModify())
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Employees
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('layoffs.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-semibold">Search</label>
                    <input type="text" 
                           class="form-control" 
                           id="search"
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Name or NIK">
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label fw-semibold">Layoff Date From</label>
                    <input type="date" 
                           class="form-control" 
                           id="date_from" 
                           name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label fw-semibold">To</label>
                    <input type="date" 
                           class="form-control" 
                           id="date_to" 
                           name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Layoff Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($layoffs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-danger">
                            <tr>
                                <th>No</th>
                                <th>Employee Name</th>
                                <th>NIK</th>
                                <th>Phone</th>
                                <th>Layoff Date</th>
                                <th>Reason</th>
                                <th>Letter</th>
                                <th>Processed By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($layoffs as $index => $layoff)
                                <tr>
                                    <td>{{ $layoffs->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $layoff->employee->employee_name }}</strong>
                                    </td>
                                    <td>{{ $layoff->employee->nik }}</td>
                                    <td>{{ $layoff->employee->nomor_hp ?? '-' }}</td>
                                    <td>
                                        <i class="bi bi-calendar-x text-danger me-1"></i>
                                        {{ $layoff->layoff_date->format('d M Y') }}
                                    </td>
                                    <td>
                                        @if($layoff->reason)
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $layoff->reason }}">
                                                {{ $layoff->reason }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($layoff->layoff_letter)
                                            <a href="{{ asset('storage/' . $layoff->layoff_letter) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-file-pdf"></i> View
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $layoff->processedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('layoffs.show', $layoff) }}" 
                                               class="btn btn-sm btn-primary"
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(auth()->user()->canModify())
                                                <a href="{{ route('layoffs.edit', $layoff) }}" 
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('layoffs.destroy', $layoff) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this layoff record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $layoffs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">No layoff records found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.table-danger {
    background-color: #f8d7da;
}

.btn-group .btn {
    white-space: nowrap;
}
</style>
@endsection
