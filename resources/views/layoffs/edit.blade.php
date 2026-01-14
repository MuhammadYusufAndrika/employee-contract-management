@extends('layouts.bootstrap')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0 pb-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('layoffs.index') }}" class="text-decoration-none">Layoffs</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('layoffs.show', $layoff) }}" class="text-decoration-none">{{ $layoff->employee->employee_name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Layoff Record
                    </h4>
                </div>

                <div class="card-body p-4">
                    <!-- Employee Information -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle fs-1 me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $layoff->employee->employee_name }}</h6>
                                <p class="mb-0 small">
                                    NIK: <strong>{{ $layoff->employee->nik }}</strong>
                                    @if($layoff->employee->nomor_hp)
                                        | Phone: <strong>{{ $layoff->employee->nomor_hp }}</strong>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                                <div>
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('layoffs.update', $layoff) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Layoff Date -->
                        <div class="mb-4">
                            <label for="layoff_date" class="form-label fw-semibold">
                                Layoff Date <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="date" 
                                class="form-control @error('layoff_date') is-invalid @enderror" 
                                id="layoff_date" 
                                name="layoff_date" 
                                value="{{ old('layoff_date', $layoff->layoff_date->format('Y-m-d')) }}" 
                                required
                            >
                            @error('layoff_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">The date when the employee was laid off</div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-4">
                            <label for="reason" class="form-label fw-semibold">
                                Reason for Layoff
                            </label>
                            <textarea 
                                class="form-control @error('reason') is-invalid @enderror" 
                                id="reason" 
                                name="reason" 
                                rows="4" 
                                placeholder="Enter the reason for layoff (optional)"
                            >{{ old('reason', $layoff->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Provide details about why the employee was laid off</div>
                        </div>

                        <!-- Current Layoff Letter -->
                        @if($layoff->layoff_letter)
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Current Layoff Letter</label>
                                <div class="card bg-light border">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-pdf-fill text-danger fs-3 me-3"></i>
                                                <div>
                                                    <p class="mb-0 fw-semibold">{{ basename($layoff->layoff_letter) }}</p>
                                                    <small class="text-muted">Current file</small>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($layoff->layoff_letter) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download me-1"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- New Layoff Letter -->
                        <div class="mb-4">
                            <label for="layoff_letter" class="form-label fw-semibold">
                                {{ $layoff->layoff_letter ? 'Replace Layoff Letter' : 'Upload Layoff Letter' }}
                            </label>
                            <input 
                                type="file" 
                                class="form-control @error('layoff_letter') is-invalid @enderror" 
                                id="layoff_letter" 
                                name="layoff_letter" 
                                accept=".pdf"
                            >
                            @error('layoff_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                {{ $layoff->layoff_letter ? 'Upload a new file to replace the current one (optional)' : 'Optional: Upload the official layoff letter (PDF format, max 5MB)' }}
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('layoffs.show', $layoff) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="bi bi-check-circle me-1"></i>Update Layoff Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-size: 1.2rem;
}

.breadcrumb-item a {
    color: #003DA5;
}

.breadcrumb-item.active {
    color: #6c757d;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.form-label {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 0.625rem 0.875rem;
}

.form-control:focus, .form-select:focus {
    border-color: #FF6B00;
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 0, 0.15);
}

.btn {
    border-radius: 8px;
    padding: 0.5rem 1.25rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-warning {
    background-color: #FF6B00;
    border-color: #FF6B00;
}

.btn-warning:hover {
    background-color: #e55f00;
    border-color: #e55f00;
}

.alert {
    border-radius: 8px;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection
