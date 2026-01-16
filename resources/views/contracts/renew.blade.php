@extends('layouts.bootstrap')

@section('title', 'Renew Contract')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-arrow-repeat"></i> Renew Contract</h2>
                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Current Contract Info -->
    <div class="card shadow mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-file-text"></i> Current Contract Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Employee:</strong> {{ $contract->employee_name }}</p>
                    <p><strong>NIK:</strong> {{ $contract->nik }}</p>
                    <p><strong>Job Position:</strong> {{ $contract->job_position }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Current Start Date:</strong> {{ $contract->start_date->format('d M Y') }}</p>
                    <p><strong>Current End Date:</strong> {{ $contract->end_date ? $contract->end_date->format('d M Y') : 'Permanent (KPP)' }}</p>
                    <p><strong>Department:</strong> {{ $contract->department }}</p>
                </div>
            </div>
            
            @if($contract->histories()->count() > 0)
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle"></i> This contract has <strong>{{ $contract->histories()->count() }}</strong> history record(s).
                    <a href="{{ route('contract-histories.by-nik', ['nik' => $contract->nik]) }}" class="alert-link">View History</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Renewal Form -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> New Contract Period</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('contracts.process-renewal', $contract) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Note:</strong> Renewing this contract will save the current contract information to history and update with new dates.
                        </div>

                        <div class="mb-3">
                            <label for="nomor_kontrak" class="form-label">New Contract Number <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nomor_kontrak') is-invalid @enderror" 
                                   id="nomor_kontrak" 
                                   name="nomor_kontrak" 
                                   value="{{ old('nomor_kontrak') }}" 
                                   placeholder="e.g., CTR-2025-002"
                                   required>
                            <small class="text-muted">Current: <strong>{{ $contract->nomor_kontrak }}</strong></small>
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
                                <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('department') is-invalid @enderror" 
                                       id="department" 
                                       name="department" 
                                       value="{{ old('department', $contract->department) }}" 
                                       placeholder="e.g., IT, Finance"
                                       required>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="work_location" class="form-label">Work Location <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('work_location') is-invalid @enderror" 
                                       id="work_location" 
                                       name="work_location" 
                                       value="{{ old('work_location', $contract->work_location) }}" 
                                       placeholder="e.g., Head Office"
                                       required>
                                @error('work_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">New Start Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date', $contract->end_date ? $contract->end_date->addDay()->format('Y-m-d') : '') }}" 
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($contract->end_date)
                                    <small class="text-muted">Suggested: {{ $contract->end_date->addDay()->format('d M Y') }}</small>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3" id="end_date_field">
                                <label for="end_date" class="form-label">New End Date <span class="text-danger" id="end_date_required">*</span></label>
                                <input type="date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date', $contract->end_date ? $contract->end_date->addYear()->format('Y-m-d') : '') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($contract->end_date)
                                    <small class="text-muted">Suggested: {{ $contract->end_date->addYear()->format('d M Y') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="file_contract" class="form-label">New Contract File (PDF)</label>
                            @if($contract->file_contract)
                                <div class="mb-2">
                                    <span class="text-muted">Current PDF:</span>
                                    <a href="{{ asset('storage/' . $contract->file_contract) }}" target="_blank" class="btn btn-sm btn-info ms-2">
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

                        <div class="mb-3">
                            <label for="notes" class="form-label">Renewal Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Add any notes about this renewal...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('contracts.index') }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Renew Contract
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
                endDateRequired.style.display = 'none';
            } else {
                endDateField.style.display = 'block';
                endDateInput.setAttribute('required', 'required');
                endDateRequired.style.display = 'inline';
            }
        }

        contractType.addEventListener('change', toggleEndDate);
        toggleEndDate(); // Run on page load
    });
</script>
@endsection
