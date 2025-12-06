@extends('layouts.app')

@section('title', 'Quotations - Quotation Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Quotations</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Quotations</h1>
    <a href="{{ route('quotations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Quotation
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($quotations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Quotation #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Valid Until</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotations as $quotation)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $quotation->quotation_number }}</span>
                                </td>
                                <td>{{ $quotation->customer->name }}</td>
                                <td>{{ $quotation->quotation_date->format('d M Y') }}</td>
                                <td>{{ $quotation->validity_date->format('d M Y') }}</td>
                                <td class="fw-semibold">₹{{ number_format($quotation->total_amount, 2) }}</td>
                                <td>
                                    @switch($quotation->status)
                                        @case('draft')
                                            <span class="badge bg-secondary">Draft</span>
                                            @break
                                        @case('sent')
                                            <span class="badge bg-primary">Sent</span>
                                            @break
                                        @case('accepted')
                                            <span class="badge bg-success">Accepted</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('quotations.print', $quotation) }}" class="btn btn-sm btn-success" title="Print" target="_blank">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <form action="{{ route('quotations.destroy', $quotation) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quotation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($quotations->hasPages())
                <div class="card-footer bg-white">
                    {{ $quotations->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-receipt fs-1 text-muted"></i>
                <p class="text-muted mt-3">No quotations found. Create your first quotation!</p>
                <a href="{{ route('quotations.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>New Quotation
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

