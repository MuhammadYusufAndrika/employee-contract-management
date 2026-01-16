@extends('layouts.bootstrap')

@section('title', 'Edit Contract')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-pencil"></i> Edit Contract</h2>
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
                    <form action="{{ route('contracts.update', $contract) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Employee Selection -->
                        <h5 class="mb-3"><i class="bi bi-person"></i> Employee Selection</h5>
                        
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Select Employee <span class="text-danger">*</span></label>
                            <select class="form-select select2-employee @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" 
                                    name="employee_id" 
                                    required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            data-nik="{{ $employee->nik }}"
                                            {{ old('employee_id', $contract->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->employee_name }} (NIK: {{ $employee->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i> Change the employee associated with this contract
                            </small>
                        </div>

                        <hr class="my-4">

                        <!-- Contract Information -->
                        <h5 class="mb-3"><i class="bi bi-briefcase"></i> Contract Information</h5>
                        
                        <div class="mb-3">
                            <label for="nomor_kontrak" class="form-label">Contract Number <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nomor_kontrak') is-invalid @enderror" 
                                   id="nomor_kontrak" 
                                   name="nomor_kontrak" 
                                   value="{{ old('nomor_kontrak', $contract->nomor_kontrak) }}" 
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
                                   value="{{ old('job_position', $contract->job_position) }}" 
                                   placeholder="e.g., Software Engineer"
                                   required>
                            @error('job_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="point_of_hire" class="form-label">Point of Hire <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('point_of_hire') is-invalid @enderror" 
                                   id="point_of_hire" 
                                   name="point_of_hire" 
                                   value="{{ old('point_of_hire', $contract->point_of_hire) }}" 
                                   placeholder="e.g., Head Office, Branch A"
                                   required>
                            @error('point_of_hire')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contract_type" class="form-label">Contract Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('contract_type') is-invalid @enderror" 
                                    id="contract_type" 
                                    name="contract_type" 
                                    required>
                                <option value="">Select Contract Type</option>
                                <option value="Kontrak" {{ old('contract_type', $contract->contract_type) == 'Kontrak' ? 'selected' : '' }}>Kontrak (Fixed Term)</option>
                                <option value="KPP" {{ old('contract_type', $contract->contract_type) == 'KPP' ? 'selected' : '' }}>KPP (Permanent)</option>
                            </select>
                            @error('contract_type')
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
                                       value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" 
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
                                       value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('department') is-invalid @enderror" 
                                   id="department" 
                                   name="department" 
                                   value="{{ old('department', $contract->department) }}" 
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
                                   value="{{ old('work_location', $contract->work_location) }}" 
                                   placeholder="e.g., Head Office, Branch A"
                                   required>
                            @error('work_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file_contract" class="form-label">Contract File (PDF)</label>
                            @if($contract->file_contract)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $contract->file_contract) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="bi bi-file-pdf"></i> View Current PDF
                                    </a>
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('file_contract') is-invalid @enderror" 
                                   id="file_contract" 
                                   name="file_contract" 
                                   accept=".pdf">
                            <small class="form-text text-muted">Upload new contract PDF file (max 5MB) - Leave empty to keep current file</small>
                            @error('file_contract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('contracts.index') }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Contract
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for employee dropdown
    $('.select2-employee').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Search Employee by Name or NIK --',
        allowClear: true,
        width: '100%',
        matcher: function(params, data) {
            // If there are no search terms, return all data
            if ($.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // Search in both name and NIK
            var searchText = params.term.toLowerCase();
            var optionText = data.text.toLowerCase();
            
            if (optionText.indexOf(searchText) > -1) {
                return data;
            }

            return null;
        }
    });

    // Contract type and end date toggle
    const contractType = document.getElementById('contract_type');
    const endDateField = document.getElementById('end_date_field');
    const endDateInput = document.getElementById('end_date');
    const endDateRequired = document.getElementById('end_date_required');

    function toggleEndDate() {
        if (contractType.value === 'KPP') {
            endDateField.style.display = 'none';
            endDateInput.removeAttribute('required');
            endDateInput.value = '';
            if (endDateRequired) {
                endDateRequired.style.display = 'none';
            }
        } else {
            endDateField.style.display = 'block';
            if (contractType.value === 'Kontrak') {
                endDateInput.setAttribute('required', 'required');
                if (endDateRequired) {
                    endDateRequired.style.display = 'inline';
                }
            }
        }
    }

    if (contractType) {
        contractType.addEventListener('change', toggleEndDate);
        toggleEndDate(); // Run on page load
    }
});
</script>
@endsection
