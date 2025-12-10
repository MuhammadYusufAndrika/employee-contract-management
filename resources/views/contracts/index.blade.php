@extends('layouts.bootstrap')

@section('title', 'Contracts List')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-file-text"></i> Employee Contracts</h2>
                <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Contract
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow mb-3">
        <div class="card-body">
            <form action="{{ route('contracts.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Name, NIK, or Contract No.">
                    </div>
                    <div class="col-md-3">
                        <label for="work_location" class="form-label">Work Location</label>
                        <select class="form-select" id="work_location" name="work_location">
                            <option value="">All Locations</option>
                            @foreach($workLocations as $location)
                                <option value="{{ $location }}" {{ request('work_location') == $location ? 'selected' : '' }}>
                                    {{ $location }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" id="department" name="department">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expiring" {{ request('status') == 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            @if($contracts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>NIK</th>
                                <th>Contract Number</th>
                                <th>Job Position</th>
                                <th>Department</th>
                                <th>Work Location</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contracts as $contract)
                                <tr class="{{ $contract->isExpiringSoon() ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration + ($contracts->currentPage() - 1) * $contracts->perPage() }}</td>
                                    <td>
                                        <strong>{{ $contract->employee_name }}</strong>
                                        @if($contract->isExpiringSoon())
                                            <span class="badge bg-warning text-dark ms-2">
                                                <i class="bi bi-exclamation-triangle"></i> Expiring Soon
                                            </span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $contract->birthplace }}, {{ $contract->birthdate->format('d M Y') }}</small>
                                    </td>
                                    <td><small>{{ $contract->nik }}</small></td>
                                    <td><small class="text-primary"><strong>{{ $contract->nomor_kontrak }}</strong></small></td>
                                    <td>{{ $contract->job_position }}</td>
                                    <td>{{ $contract->department }}</td>
                                    <td>{{ $contract->work_location }}</td>
                                    <td>{{ $contract->start_date->format('d M Y') }}</td>
                                    <td>{{ $contract->end_date ? $contract->end_date->format('d M Y') : 'Permanent' }}</td>
                                    <td>
                                        @if(!$contract->end_date)
                                            <span class="badge bg-info">Permanent</span>
                                        @elseif($contract->end_date < now())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($contract->isExpiringSoon())
                                            <span class="badge bg-warning text-dark">
                                                {{ $contract->daysUntilExpiration() }} days left
                                            </span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($contract->file_contract)
                                                <a href="{{ asset('storage/' . $contract->file_contract) }}" target="_blank" class="btn btn-sm btn-primary" title="View PDF">
                                                    <i class="bi bi-file-pdf"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            {{-- @if($contract->isExpiringSoon() || $contract->end_date < now())
                                                <a href="{{ route('contracts.renew', $contract) }}" class="btn btn-sm btn-info" title="Renew Contract">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </a>
                                            @endif --}}
                                            <a href="{{ route('contract-histories.show', $contract) }}" class="btn btn-sm btn-secondary" title="View History">
                                                <i class="bi bi-clock-history"></i>
                                            </a>
                                            <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this contract?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $contracts->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">No contracts found. Click "Add New Contract" to create one.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
