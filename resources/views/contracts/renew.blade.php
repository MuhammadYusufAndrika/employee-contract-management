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
                    <p><strong>Current End Date:</strong> {{ $contract->end_date->format('d M Y') }}</p>
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
                    <form action="{{ route('contracts.process-renewal', $contract) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Note:</strong> Renewing this contract will save the current contract information to history and update with new dates.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">New Start Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date', $contract->end_date->addDay()->format('Y-m-d')) }}" 
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Suggested: {{ $contract->end_date->addDay()->format('d M Y') }}</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">New End Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date', $contract->end_date->addYear()->format('Y-m-d')) }}" 
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Suggested: {{ $contract->end_date->addYear()->format('d M Y') }}</small>
                            </div>
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
@endsection
