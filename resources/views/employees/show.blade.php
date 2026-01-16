@extends('layouts.bootstrap')

@section('title', 'Employee Details - ' . $employee->employee_name)

@section('content')
<div class="empd-container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center empd-header-wrapper">
                <h2 class="empd-page-title">
                    <i class="bi bi-person-badge-fill"></i> Employee Details
                </h2>
                <div>
                    <a href="{{ route('employees.index') }}" class="btn empd-btn-back">
                        <i class="bi bi-arrow-left"></i> Back to Employee List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Info Card -->
    <div class="card empd-info-card shadow mb-4">
        <div class="card-header empd-card-header-info">
            <h5 class="mb-0 empd-card-title"><i class="bi bi-person-badge"></i> Employee Information</h5>
        </div>
        <div class="card-body empd-card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless empd-info-table">
                        <tr>
                            <th width="40%" class="empd-info-label">Name:</th>
                            <td class="empd-info-value"><strong>{{ $employee->employee_name }}</strong></td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">NIK (ID_Number):</th>
                            <td class="empd-info-value">{{ $employee->nik }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">NID (Company ID):</th>
                            <td class="empd-info-value">{{ $employee->nid ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Birth Date:</th>
                            <td class="empd-info-value">{{ $employee->birthdate ? $employee->birthdate->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Birth Place:</th>
                            <td class="empd-info-value">{{ $employee->birthplace }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">TMT Masuk Dahana:</th>
                            <td class="empd-info-value">{{ $employee->TMT_awal ? $employee->TMT_awal->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Phone Number:</th>
                            <td class="empd-info-value">{{ $employee->nomor_hp ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless empd-info-table">
                        <tr>
                            <th width="40%" class="empd-info-label">Address:</th>
                            <td class="empd-info-value">{{ $employee->address }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Total Contracts:</th>
                            <td class="empd-info-value"><strong>{{ $contracts->count() }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            @if($employee->file_cv)
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="empd-file-section">
                            <strong class="empd-file-label">CV Karyawan:</strong><br>
                            <a href="{{ asset('storage/' . $employee->file_cv) }}" target="_blank" class="btn empd-btn-file mt-2">
                                <i class="bi bi-file-pdf"></i> View CV PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Current Contract Status -->
    @if($contracts->isNotEmpty())
    @php
        $latestContract = $contracts->first();
    @endphp
    <div class="card empd-contract-card shadow mb-4">
        <div class="card-header empd-card-header-contract">
            <h5 class="mb-0 empd-card-title"><i class="bi bi-calendar-check"></i> Latest Contract Status</h5>
        </div>
        <div class="card-body empd-card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">Contract Number:</strong><br>
                        <span class="empd-contract-number">{{ $latestContract->nomor_kontrak }}</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">Job Position:</strong><br>
                        <span class="empd-contract-date">{{ $latestContract->job_position }}</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">Department:</strong><br>
                        <span class="empd-contract-date">{{ $latestContract->department }}</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">Start Date:</strong><br>
                        <span class="empd-contract-date">{{ $latestContract->start_date ? $latestContract->start_date->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">End Date:</strong><br>
                        <span class="empd-contract-date">{{ $latestContract->end_date ? $latestContract->end_date->format('d M Y') : 'Permanent (KPP)' }}</span>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">Status:</strong><br>
                        @if($latestContract->status === 'Layoff')
                            <span class="empd-status-badge empd-status-layoff">
                                <i class="bi bi-person-dash-fill"></i> Layoff
                            </span>
                        @elseif(!$latestContract->end_date)
                            <span class="empd-status-badge empd-status-permanent">Permanent</span>
                        @elseif($latestContract->end_date < now())
                            <span class="empd-status-badge empd-status-expired">Expired</span>
                        @elseif($latestContract->isExpiringSoon())
                            <span class="empd-status-badge empd-status-expiring">
                                Expiring in {{ $latestContract->daysUntilExpiration() }} days
                            </span>
                        @else
                            <span class="empd-status-badge empd-status-active">Active</span>
                        @endif
                    </div>
                </div>
            </div>
            @if($latestContract->file_contract)
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="empd-file-section">
                            <strong class="empd-file-label">Contract PDF:</strong><br>
                            <a href="{{ asset('storage/' . $latestContract->file_contract) }}" target="_blank" class="btn empd-btn-file mt-2">
                                <i class="bi bi-file-pdf"></i> View Current Contract PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @else
    <div class="card empd-contract-card shadow mb-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
            <p class="mt-3 text-muted">No contracts found for this employee.</p>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('contracts.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Add Contract
                </a>
            @endif
        </div>
    </div>
    @endif

    <!-- Contract History Timeline -->
    <div class="card empd-history-card shadow">
        <div class="card-header empd-card-header-history">
            <h5 class="mb-0 empd-card-title">
                <i class="bi bi-clock-history"></i> All Contracts 
                <span class="empd-history-count">{{ $contracts->count() }} Total</span>
            </h5>
        </div>
        <div class="card-body empd-card-body">
            @if($contracts->count() > 0)
                <div class="empd-timeline">
                    @foreach($contracts as $contract)
                        <div class="empd-timeline-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2 empd-timeline-header">
                                        @if($contract->contract_type === 'KPP')
                                            <span class="empd-action-badge empd-action-renewed">
                                                <i class="bi bi-infinity"></i> Permanent
                                            </span>
                                        @else
                                            <span class="empd-action-badge empd-action-created">
                                                <i class="bi bi-file-text"></i> Contract
                                            </span>
                                        @endif
                                        <small class="empd-timeline-date">
                                            <i class="bi bi-calendar"></i> {{ $contract->start_date ? $contract->start_date->format('d M Y') : 'N/A' }}
                                            @if($contract->end_date)
                                                - {{ $contract->end_date->format('d M Y') }}
                                            @endif
                                        </small>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless empd-history-table">
                                                <tr>
                                                    <th width="45%" class="empd-history-label">Contract Number:</th>
                                                    <td class="empd-history-value">{{ $contract->nomor_kontrak }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">Department:</th>
                                                    <td class="empd-history-value">{{ $contract->department }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">Work Location:</th>
                                                    <td class="empd-history-value">{{ $contract->work_location }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">Job Position:</th>
                                                    <td class="empd-history-value">{{ $contract->job_position }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless empd-history-table">
                                                <tr>
                                                    <th width="45%" class="empd-history-label">Start Date:</th>
                                                    <td class="empd-history-value">{{ $contract->start_date ? $contract->start_date->format('d M Y') : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">End Date:</th>
                                                    <td class="empd-history-value">{{ $contract->end_date ? $contract->end_date->format('d M Y') : 'Permanent' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">Duration:</th>
                                                    <td class="empd-history-value">
                                                        @if($contract->end_date && $contract->start_date)
                                                            {{ $contract->start_date->diffInDays($contract->end_date) }} days
                                                        @else
                                                            Permanent
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($contract->file_contract)
                                                    <tr>
                                                        <th class="empd-history-label">Contract PDF:</th>
                                                        <td class="empd-history-value">
                                                            <a href="{{ asset('storage/' . $contract->file_contract) }}" target="_blank" class="btn empd-btn-history-file">
                                                                <i class="bi bi-file-pdf"></i> View PDF
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empd-empty-history text-center py-5">
                    <i class="bi bi-inbox empd-empty-icon"></i>
                    <p class="empd-empty-text mt-3">No history records found for this employee.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Employee Detail Module Unique Styles */
.empd-container {
    padding: 20px;
}

.empd-header-wrapper {
    margin-bottom: 1.5rem;
}

.empd-page-title {
    color: #003DA5;
    font-weight: 700;
    margin-bottom: 0;
}

.empd-btn-back {
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.empd-btn-back:hover {
    background-color: #5a6268;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
}

.empd-info-card,
.empd-contract-card,
.empd-history-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.empd-card-header-info {
    background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
    color: white;
    border-radius: 8px 8px 0 0 !important;
}

.empd-card-header-contract {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    border-radius: 8px 8px 0 0 !important;
}

.empd-card-header-history {
    background: linear-gradient(135deg, #343a40 0%, #23272b 100%);
    color: white;
    border-radius: 8px 8px 0 0 !important;
}

.empd-card-title {
    font-weight: 600;
    font-size: 1.1rem;
}

.empd-card-body {
    padding: 2rem;
}

.empd-info-table {
    margin-bottom: 0;
}

.empd-info-label {
    font-weight: 600;
    color: #495057;
    padding: 0.5rem 0;
}

.empd-info-value {
    color: #212529;
    padding: 0.5rem 0;
}

.empd-file-section {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.empd-file-label {
    color: #495057;
    font-size: 0.95rem;
}

.empd-btn-file {
    background-color: #17a2b8;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1.25rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.empd-btn-file:hover {
    background-color: #138496;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
}

.empd-contract-item {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.empd-contract-label {
    color: #6c757d;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 0.5rem;
}

.empd-contract-number {
    color: #003DA5;
    font-weight: 700;
    font-size: 1.1rem;
}

.empd-contract-date {
    color: #495057;
    font-size: 1rem;
}

.empd-status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
}

.empd-status-permanent {
    background-color: #d1ecf1;
    color: #0c5460;
}

.empd-status-active {
    background-color: #d4edda;
    color: #155724;
}

.empd-status-expiring {
    background-color: #fff3cd;
    color: #856404;
}

.empd-status-expired {
    background-color: #f8d7da;
    color: #721c24;
}

.empd-status-layoff {
    background-color: #343a40;
    color: #ffffff;
    font-weight: 600;
}

.empd-history-count {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
}

.empd-timeline {
    position: relative;
    padding-left: 0;
}

.empd-timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #e9ecef;
}

.empd-timeline-item:last-child {
    border-bottom: none;
}

.empd-timeline-header {
    margin-bottom: 1rem;
}

.empd-action-badge {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-right: 1rem;
}

.empd-action-created {
    background-color: #d4edda;
    color: #155724;
}

.empd-action-renewed {
    background-color: #d1ecf1;
    color: #0c5460;
}

.empd-action-updated {
    background-color: #fff3cd;
    color: #856404;
}

.empd-timeline-date {
    color: #6c757d;
    font-size: 0.9rem;
}

.empd-timeline-relative {
    margin-left: 0.5rem;
    color: #adb5bd;
}

.empd-history-notes {
    background-color: #f8f9fa;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    border-left: 4px solid #003DA5;
    margin-bottom: 1rem;
}

.empd-history-table {
    margin-bottom: 0;
}

.empd-history-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
    padding: 0.4rem 0;
}

.empd-history-value {
    color: #495057;
    padding: 0.4rem 0;
}

.empd-btn-history-file {
    background-color: #17a2b8;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 0.25rem 0.75rem;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.empd-btn-history-file:hover {
    background-color: #138496;
    color: white;
    transform: scale(1.05);
}

.empd-empty-history {
    padding: 4rem 2rem;
}

.empd-empty-icon {
    font-size: 4rem;
    color: #ccc;
}

.empd-empty-text {
    color: #6c757d;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .empd-container {
        padding: 10px;
    }
    
    .empd-page-title {
        font-size: 1.5rem;
    }
    
    .empd-card-body {
        padding: 1rem;
    }
    
    .empd-contract-item {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection
