@extends('layouts.bootstrap')

@section('title', 'Employee Contract History')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-person-badge"></i> Employee Contract History</h2>
                <a href="{{ route('contract-histories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to All History
                </a>
            </div>
        </div>
    </div>

    @if($employee)
        <!-- Employee Info Card -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Employee Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> {{ $employee->employee_name }}</p>
                        <p><strong>NIK:</strong> {{ $employee->nik }}</p>
                        <p><strong>Birth Date:</strong> {{ $employee->birthdate->format('d M Y') }}</p>
                        <p><strong>Birth Place:</strong> {{ $employee->birthplace }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Current Position:</strong> {{ $histories->first()->job_position }}</p>
                        <p><strong>Department:</strong> {{ $histories->first()->department }}</p>
                        <p><strong>Work Location:</strong> {{ $histories->first()->work_location }}</p>
                        <p><strong>Total Records:</strong> <span class="badge bg-primary">{{ $histories->count() }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Contract History Timeline</h5>
            </div>
            <div class="card-body">
                @if($histories->count() > 0)
                    <div class="timeline">
                        @foreach($histories as $history)
                            <div class="card mb-3 border-start border-4 
                                {{ $history->action_type == 'created' ? 'border-success' : 
                                   ($history->action_type == 'renewed' ? 'border-warning' : 'border-info') }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title">
                                                @if($history->action_type == 'created')
                                                    <span class="badge bg-success">Created</span>
                                                @elseif($history->action_type == 'renewed')
                                                    <span class="badge bg-warning text-dark">Renewed</span>
                                                @else
                                                    <span class="badge bg-info">Updated</span>
                                                @endif
                                                {{ $history->created_at->format('d F Y, H:i') }}
                                            </h6>
                                        </div>
                                        <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Job Position:</strong></p>
                                            <p>{{ $history->job_position }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Department:</strong></p>
                                            <p>{{ $history->department }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Work Location:</strong></p>
                                            <p>{{ $history->work_location }}</p>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Contract Period:</strong></p>
                                            <p>
                                                <i class="bi bi-calendar-check text-success"></i> {{ $history->start_date->format('d M Y') }} 
                                                <i class="bi bi-arrow-right"></i> 
                                                <i class="bi bi-calendar-x text-danger"></i> {{ $history->end_date ? $history->end_date->format('d M Y') : 'Permanent (KPP)' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Duration:</strong></p>
                                            <p>{{ $history->start_date->diffInMonths($history->end_date) }} months</p>
                                        </div>
                                    </div>

                                    @if($history->notes)
                                        <div class="mt-3">
                                            <p class="mb-1"><strong>Notes:</strong></p>
                                            <p class="text-muted">{{ $history->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                        <p class="mt-3 text-muted">No history records found for this employee.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> No employee found with NIK: <strong>{{ $nik }}</strong>
        </div>
    @endif
</div>
@endsection
