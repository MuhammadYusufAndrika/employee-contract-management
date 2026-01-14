@extends('layouts.bootstrap')

@section('title', 'Process Employee Layoff')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-danger mb-1">
                        <i class="bi bi-person-x-fill me-2"></i>Process Employee Layoff
                    </h2>
                    <p class="text-muted mb-0">Enter layoff details and confirmation</p>
                </div>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6 class="alert-heading">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:
                    </h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Employee Info Card -->
            @if($employee)
                <div class="card shadow-sm border-danger mb-4">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge me-2"></i>Employee Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Name:</th>
                                        <td><strong>{{ $employee->employee_name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>NIK:</th>
                                        <td>{{ $employee->nik }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $employee->nomor_hp ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Birth Date:</th>
                                        <td>{{ $employee->birthdate->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Birth Place:</th>
                                        <td>{{ $employee->birthplace }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{ $employee->address }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Layoff Form -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Layoff Confirmation Form
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('layoffs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if($employee)
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        @else
                            <!-- Employee Selection -->
                            <div class="mb-3">
                                <label for="employee_id" class="form-label fw-semibold">
                                    <i class="bi bi-person text-danger me-1"></i>Select Employee
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('employee_id') is-invalid @enderror" 
                                        id="employee_id" 
                                        name="employee_id" 
                                        required>
                                    <option value="">-- Select Employee --</option>
                                    @foreach(App\Models\Employee::whereDoesntHave('layoff')->orderBy('employee_name')->get() as $emp)
                                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->employee_name }} (NIK: {{ $emp->nik }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Layoff Date -->
                        <div class="mb-3">
                            <label for="layoff_date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-x text-danger me-1"></i>Layoff Date
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('layoff_date') is-invalid @enderror" 
                                   id="layoff_date" 
                                   name="layoff_date" 
                                   value="{{ old('layoff_date', now()->format('Y-m-d')) }}"
                                   required>
                            @error('layoff_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label for="reason" class="form-label fw-semibold">
                                <i class="bi bi-chat-text text-danger me-1"></i>Reason for Layoff
                            </label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" 
                                      name="reason" 
                                      rows="4"
                                      placeholder="Enter the reason for layoff (optional)">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Layoff Letter -->
                        <div class="mb-3">
                            <label for="layoff_letter" class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-pdf text-danger me-1"></i>Layoff Letter (PDF)
                            </label>
                            <input type="file" 
                                   class="form-control @error('layoff_letter') is-invalid @enderror" 
                                   id="layoff_letter" 
                                   name="layoff_letter" 
                                   accept=".pdf">
                            @error('layoff_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>Upload the official layoff letter (optional). Maximum file size: 5MB.
                            </small>
                        </div>

                        <!-- Warning Message -->
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Warning:</strong> This action will mark the employee as laid off. Please ensure all information is correct before proceeding.
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-check-circle me-1"></i>Confirm Layoff
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.card-header {
    border-bottom: 3px solid #FF6B00;
}
</style>
@endsection
