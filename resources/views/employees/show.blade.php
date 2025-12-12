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
                            <th class="empd-info-label">NIK:</th>
                            <td class="empd-info-value">{{ $employee->nik }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Birth Date:</th>
                            <td class="empd-info-value">{{ $employee->birthdate->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Birth Place:</th>
                            <td class="empd-info-value">{{ $employee->birthplace }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless empd-info-table">
                        <tr>
                            <th width="40%" class="empd-info-label">Job Position:</th>
                            <td class="empd-info-value">{{ $employee->job_position }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Point of Hire:</th>
                            <td class="empd-info-value">{{ $employee->point_of_hire }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">TMT Awal:</th>
                            <td class="empd-info-value">{{ $employee->TMT_awal ? $employee->TMT_awal->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Department:</th>
                            <td class="empd-info-value">{{ $employee->department }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Work Location:</th>
                            <td class="empd-info-value">{{ $employee->work_location }}</td>
                        </tr>
                        <tr>
                            <th class="empd-info-label">Address:</th>
                            <td class="empd-info-value">{{ $employee->address }}</td>
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
    <div class="card empd-contract-card shadow mb-4">
        <div class="card-header empd-card-header-contract">
            <h5 class="mb-0 empd-card-title"><i class="bi bi-calendar-check"></i> Current Contract Status</h5>
        </div>
        <div class="card-body empd-card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">Contract Number:</strong><br>
                        <span class="empd-contract-number">{{ $employee->nomor_kontrak }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">Start Date:</strong><br>
                        <span class="empd-contract-date">{{ $employee->start_date->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">End Date:</strong><br>
                        <span class="empd-contract-date">{{ $employee->end_date ? $employee->end_date->format('d M Y') : 'Permanent (KPP)' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="empd-contract-item">
                        <strong class="empd-contract-label">Status:</strong><br>
                        @if(!$employee->end_date)
                            <span class="empd-status-badge empd-status-permanent">Permanent</span>
                        @elseif($employee->end_date < now())
                            <span class="empd-status-badge empd-status-expired">Expired</span>
                        @elseif($employee->isExpiringSoon())
                            <span class="empd-status-badge empd-status-expiring">
                                Expiring in {{ $employee->daysUntilExpiration() }} days
                            </span>
                        @else
                            <span class="empd-status-badge empd-status-active">Active</span>
                        @endif
                    </div>
                </div>
            </div>
            @if($employee->file_contract)
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="empd-file-section">
                            <strong class="empd-file-label">Contract PDF:</strong><br>
                            <a href="{{ asset('storage/' . $employee->file_contract) }}" target="_blank" class="btn empd-btn-file mt-2">
                                <i class="bi bi-file-pdf"></i> View Current Contract PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- History Timeline -->
    <div class="card empd-history-card shadow">
        <div class="card-header empd-card-header-history">
            <h5 class="mb-0 empd-card-title">
                <i class="bi bi-clock-history"></i> Contract Change History 
                <span class="empd-history-count">{{ $histories->count() }} Records</span>
            </h5>
        </div>
        <div class="card-body empd-card-body">
            @if($histories->count() > 0)
                <div class="empd-timeline">
                    @foreach($histories as $history)
                        <div class="empd-timeline-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2 empd-timeline-header">
                                        @if($history->action_type === 'created')
                                            <span class="empd-action-badge empd-action-created">
                                                <i class="bi bi-plus-circle"></i> Created
                                            </span>
                                        @elseif($history->action_type === 'renewed')
                                            <span class="empd-action-badge empd-action-renewed">
                                                <i class="bi bi-arrow-clockwise"></i> Renewed
                                            </span>
                                        @elseif($history->action_type === 'updated')
                                            <span class="empd-action-badge empd-action-updated">
                                                <i class="bi bi-pencil"></i> Updated
                                            </span>
                                        @endif
                                        <small class="empd-timeline-date">
                                            <i class="bi bi-clock"></i> {{ $history->created_at->format('d M Y, H:i') }}
                                            <span class="empd-timeline-relative">({{ $history->created_at->diffForHumans() }})</span>
                                        </small>
                                    </div>

                                    @if($history->notes)
                                        <div class="empd-history-notes">
                                            <i class="bi bi-info-circle"></i> <strong>Notes:</strong> {{ $history->notes }}
                                        </div>
                                    @endif

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless empd-history-table">
                                                <tr>
                                                    <th width="45%" class="empd-history-label">Contract Number:</th>
                                                    <td class="empd-history-value">{{ $history->nomor_kontrak }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">Department:</th>
                                                    <td class="empd-history-value">{{ $history->department }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">Work Location:</th>
                                                    <td class="empd-history-value">{{ $history->work_location }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">Job Position:</th>
                                                    <td class="empd-history-value">{{ $history->job_position }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless empd-history-table">
                                                <tr>
                                                    <th width="45%" class="empd-history-label">Start Date:</th>
                                                    <td class="empd-history-value">{{ \Carbon\Carbon::parse($history->start_date)->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">End Date:</th>
                                                    <td class="empd-history-value">{{ \Carbon\Carbon::parse($history->end_date)->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="empd-history-label">Duration:</th>
                                                    <td class="empd-history-value">
                                                        {{ \Carbon\Carbon::parse($history->start_date)->diffInDays(\Carbon\Carbon::parse($history->end_date)) }} days
                                                    </td>
                                                </tr>
                                                @if($history->file_contract)
                                                    <tr>
                                                        <th class="empd-history-label">Contract PDF:</th>
                                                        <td class="empd-history-value">
                                                            <a href="{{ asset('storage/' . $history->file_contract) }}" target="_blank" class="btn empd-btn-history-file">
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
