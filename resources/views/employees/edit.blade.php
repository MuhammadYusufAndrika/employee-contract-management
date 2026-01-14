@extends('layouts.bootstrap')

@section('title', 'Edit Employee')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="bi bi-pencil-square me-2"></i>Edit Employee
                    </h2>
                    <p class="text-muted mb-0">Update employee personal information</p>
                </div>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-person me-2"></i>Personal Information Form
                    </h5>
                </div>
                <div class="card-body p-4">
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

                    <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information Section -->
                        <div class="row g-3">
                            <!-- Employee Name -->
                            <div class="col-md-6">
                                <label for="employee_name" class="form-label fw-semibold">
                                    <i class="bi bi-person-fill text-primary me-1"></i>Full Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('employee_name') is-invalid @enderror" 
                                       id="employee_name" 
                                       name="employee_name" 
                                       value="{{ old('employee_name', $employee->employee_name) }}"
                                       placeholder="Enter full name"
                                       required>
                                @error('employee_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIK -->
                            <div class="col-md-6">
                                <label for="nik" class="form-label fw-semibold">
                                    <i class="bi bi-credit-card-2-front text-primary me-1"></i>NIK (ID Number)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nik') is-invalid @enderror" 
                                       id="nik" 
                                       name="nik" 
                                       value="{{ old('nik', $employee->nik) }}"
                                       placeholder="Enter NIK"
                                       maxlength="50"
                                       required>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Unique employee identification number</small>
                            </div>

                            <!-- Birthplace -->
                            <div class="col-md-6">
                                <label for="birthplace" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>Place of Birth
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('birthplace') is-invalid @enderror" 
                                       id="birthplace" 
                                       name="birthplace" 
                                       value="{{ old('birthplace', $employee->birthplace) }}"
                                       placeholder="Enter place of birth"
                                       required>
                                @error('birthplace')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Birthdate -->
                            <div class="col-md-6">
                                <label for="birthdate" class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event text-primary me-1"></i>Date of Birth
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('birthdate') is-invalid @enderror" 
                                       id="birthdate" 
                                       name="birthdate" 
                                       value="{{ old('birthdate', $employee->birthdate?->format('Y-m-d')) }}"
                                       max="{{ date('Y-m-d', strtotime('-17 years')) }}"
                                       required>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">
                                    <i class="bi bi-house-fill text-primary me-1"></i>Address
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3"
                                          placeholder="Enter complete address"
                                          required>{{ old('address', $employee->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current CV Display -->
                            @if($employee->file_cv)
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-check text-success me-1"></i>Current CV
                                </label>
                                <div class="p-3 bg-light rounded border">
                                    <a href="{{ Storage::url($employee->file_cv) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-pdf me-1"></i>View Current CV
                                    </a>
                                    <small class="text-muted ms-2">Uploaded previously</small>
                                </div>
                            </div>
                            @endif

                            <!-- CV File Upload -->
                            <div class="col-12">
                                <label for="file_cv" class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-pdf text-primary me-1"></i>
                                    {{ $employee->file_cv ? 'Update CV / Resume (PDF)' : 'CV / Resume (PDF)' }}
                                </label>
                                <input type="file" 
                                       class="form-control @error('file_cv') is-invalid @enderror" 
                                       id="file_cv" 
                                       name="file_cv" 
                                       accept=".pdf">
                                @error('file_cv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ $employee->file_cv ? 'Leave empty to keep current CV. ' : '' }}
                                    Maximum file size: 5MB. Accepted format: PDF only.
                                </small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>Update Employee
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
    background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
}

.form-label {
    color: #002060;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: 3px solid #FF6B00;
}

.btn-primary {
    background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #002060 0%, #001040 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 61, 165, 0.3);
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
}

.form-control:focus {
    border-color: #003DA5;
    box-shadow: 0 0 0 0.2rem rgba(0, 61, 165, 0.15);
}
</style>
@endsection
