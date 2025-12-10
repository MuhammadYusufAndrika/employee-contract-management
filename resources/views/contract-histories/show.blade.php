@extends('layouts.bootstrap')

@section('title', 'Contract History - ' . $contract->employee_name)

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    <i class="bi bi-clock-history"></i> Contract History
                </h2>
                <div>
                    <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Contracts
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Info Card -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Employee Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Name:</th>
                            <td><strong>{{ $contract->employee_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>NIK:</th>
                            <td>{{ $contract->nik }}</td>
                        </tr>
                        <tr>
                            <th>Birth Date:</th>
                            <td>{{ $contract->birthdate->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Birth Place:</th>
                            <td>{{ $contract->birthplace }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Job Position:</th>
                            <td>{{ $contract->job_position }}</td>
                        </tr>
                        <tr>
                            <th>Point of Hire:</th>
                            <td>{{ $contract->point_of_hire }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $contract->department }}</td>
                        </tr>
                        <tr>
                            <th>Work Location:</th>
                            <td>{{ $contract->work_location }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $contract->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Contract Status -->
    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Current Contract Status</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Contract Number:</strong><br>
                    <span class="text-primary"><strong>{{ $contract->nomor_kontrak }}</strong></span>
                </div>
                <div class="col-md-3">
                    <strong>Start Date:</strong><br>
                    <span class="text-muted">{{ $contract->start_date->format('d M Y') }}</span>
                </div>
                <div class="col-md-3">
                    <strong>End Date:</strong><br>
                    <span class="text-muted">{{ $contract->end_date ? $contract->end_date->format('d M Y') : 'Permanent (KPP)' }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong><br>
                    @if(!$contract->end_date)
                        <span class="badge bg-info fs-6">Permanent</span>
                    @elseif($contract->end_date < now())
                        <span class="badge bg-danger fs-6">Expired</span>
                    @elseif($contract->isExpiringSoon())
                        <span class="badge bg-warning text-dark fs-6">
                            Expiring in {{ $contract->daysUntilExpiration() }} days
                        </span>
                    @else
                        <span class="badge bg-success fs-6">Active</span>
                    @endif
                </div>
            </div>
            @if($contract->file_contract)
                <div class="row mt-3">
                    <div class="col-md-12">
                        <strong>Contract PDF:</strong><br>
                        <a href="{{ asset('storage/' . $contract->file_contract) }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-file-pdf"></i> View Current Contract PDF
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- History Timeline -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                <i class="bi bi-clock-history"></i> Change History 
                <span class="badge bg-secondary">{{ $histories->count() }} Records</span>
            </h5>
        </div>
        <div class="card-body">
            @if($histories->count() > 0)
                <div class="timeline">
                    @foreach($histories as $history)
                        <div class="timeline-item mb-4 pb-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($history->action_type === 'created')
                                            <span class="badge bg-success me-2">
                                                <i class="bi bi-plus-circle"></i> Created
                                            </span>
                                        @elseif($history->action_type === 'renewed')
                                            <span class="badge bg-info me-2">
                                                <i class="bi bi-arrow-clockwise"></i> Renewed
                                            </span>
                                        @elseif($history->action_type === 'updated')
                                            <span class="badge bg-warning text-dark me-2">
                                                <i class="bi bi-pencil"></i> Updated
                                            </span>
                                        @endif
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $history->created_at->format('d M Y, H:i') }}
                                            <span class="ms-2">({{ $history->created_at->diffForHumans() }})</span>
                                        </small>
                                    </div>

                                    @if($history->notes)
                                        <div class="alert alert-light mb-2">
                                            <i class="bi bi-info-circle"></i> <strong>Notes:</strong> {{ $history->notes }}
                                        </div>
                                    @endif

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th width="45%">Contract Number:</th>
                                                    <td><span class="text-primary"><strong>{{ $history->nomor_kontrak }}</strong></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Department:</th>
                                                    <td>{{ $history->department }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Work Location:</th>
                                                    <td>{{ $history->work_location }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Job Position:</th>
                                                    <td>{{ $history->job_position }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th width="45%">Start Date:</th>
                                                    <td>{{ \Carbon\Carbon::parse($history->start_date)->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>End Date:</th>
                                                    <td>{{ \Carbon\Carbon::parse($history->end_date)->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Duration:</th>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($history->start_date)->diffInDays(\Carbon\Carbon::parse($history->end_date)) }} days
                                                    </td>
                                                </tr>
                                                @if($history->file_contract)
                                                    <tr>
                                                        <th>Contract PDF:</th>
                                                        <td>
                                                            <a href="{{ asset('storage/' . $history->file_contract) }}" target="_blank" class="btn btn-sm btn-info">
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
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">No history records found for this contract.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 0;
}

.timeline-item {
    position: relative;
}

.timeline-item:last-child {
    border-bottom: none !important;
}
</style>
@endsection
