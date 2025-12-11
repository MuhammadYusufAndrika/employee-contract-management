@extends('layouts.bootstrap')

@section('title', 'Create New Contract')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-plus-circle"></i> Create New Contract</h2>
                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="mb-3"><i class="bi bi-person"></i> Personal Information</h5>
                        
                        <div class="mb-3">
                            <label for="employee_name" class="form-label">Employee Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('employee_name') is-invalid @enderror" 
                                   id="employee_name" 
                                   name="employee_name" 
                                   value="{{ old('employee_name') }}" 
                                   placeholder="Enter employee name"
                                   required>
                            @error('employee_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK (National ID) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nik') is-invalid @enderror" 
                                   id="nik" 
                                   name="nik" 
                                   value="{{ old('nik') }}" 
                                   placeholder="Enter 16 digit NIK"
                                   maxlength="16"
                                   required>
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="TMT_awal" class="form-label">TMT Awal (Effective Start Date) <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('TMT_awal') is-invalid @enderror" 
                                   id="TMT_awal" 
                                   name="TMT_awal" 
                                   value="{{ old('TMT_awal') }}" 
                                   required>
                            @error('TMT_awal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birthplace" class="form-label">Birth Place <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('birthplace') is-invalid @enderror" 
                                       id="birthplace" 
                                       name="birthplace" 
                                       value="{{ old('birthplace') }}" 
                                       placeholder="e.g., Jakarta"
                                       required>
                                @error('birthplace')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="birthdate" class="form-label">Birth Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('birthdate') is-invalid @enderror" 
                                       id="birthdate" 
                                       name="birthdate" 
                                       value="{{ old('birthdate') }}" 
                                       required>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Enter full address"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3"><i class="bi bi-briefcase"></i> Employment Information</h5>

                        <div class="mb-3">
                            <label for="contract_type" class="form-label">Contract Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('contract_type') is-invalid @enderror" 
                                    id="contract_type" 
                                    name="contract_type" 
                                    required>
                                <option value="">Select Contract Type</option>
                                <option value="Kontrak" {{ old('contract_type') == 'Kontrak' ? 'selected' : '' }}>Kontrak (Fixed Term)</option>
                                <option value="KPP" {{ old('contract_type') == 'KPP' ? 'selected' : '' }}>KPP (Permanent)</option>
                            </select>
                            @error('contract_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nomor_kontrak" class="form-label">Contract Number <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nomor_kontrak') is-invalid @enderror" 
                                   id="nomor_kontrak" 
                                   name="nomor_kontrak" 
                                   value="{{ old('nomor_kontrak') }}" 
                                   placeholder="e.g., CTR-2025-001"
                                   required>
                            @error('nomor_kontrak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="job_position" class="form-label">Job Position <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('job_position') is-invalid @enderror" 
                                   id="job_position" 
                                   name="job_position" 
                                   value="{{ old('job_position') }}" 
                                   placeholder="e.g., Software Engineer"
                                   required>
                            @error('job_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('department') is-invalid @enderror" 
                                   id="department" 
                                   name="department" 
                                   value="{{ old('department') }}" 
                                   placeholder="e.g., IT, HR, Finance"
                                   required>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="work_location" class="form-label">Work Location <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('work_location') is-invalid @enderror" 
                                   id="work_location" 
                                   name="work_location" 
                                   value="{{ old('work_location') }}" 
                                   placeholder="e.g., Head Office, Branch A"
                                   required>
                            @error('work_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="point_of_hire" class="form-label">Point of Hire <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('point_of_hire') is-invalid @enderror" 
                                   id="point_of_hire" 
                                   name="point_of_hire" 
                                   value="{{ old('point_of_hire') }}" 
                                   placeholder="e.g., Head Office, Branch A"
                                   required>
                            @error('point_of_hire')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date') }}" 
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="end_date_field">
                                <label for="end_date" class="form-label">End Date <span class="text-danger" id="end_date_required">*</span></label>
                                <input type="date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="file_contract" class="form-label">Contract File (PDF)</label>
                            <input type="file" 
                                   class="form-control @error('file_contract') is-invalid @enderror" 
                                   id="file_contract" 
                                   name="file_contract" 
                                   accept=".pdf">
                            <small class="form-text text-muted">Upload contract PDF file (max 5MB)</small>
                            @error('file_contract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('contracts.index') }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Contract
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contractType = document.getElementById('contract_type');
    const endDateField = document.getElementById('end_date_field');
    const endDateInput = document.getElementById('end_date');
    const endDateRequired = document.getElementById('end_date_required');

    function toggleEndDate() {
        if (contractType.value === 'KPP') {
            endDateField.style.display = 'none';
            endDateInput.removeAttribute('required');
            endDateInput.value = '';
            endDateRequired.style.display = 'none';
        } else {
            endDateField.style.display = 'block';
            if (contractType.value === 'Kontrak') {
                endDateInput.setAttribute('required', 'required');
                endDateRequired.style.display = 'inline';
            }
        }
    }

    contractType.addEventListener('change', toggleEndDate);
    toggleEndDate(); // Run on page load
});
</script>
@endsection
