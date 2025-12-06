@extends('layouts.app')

@section('title', 'Dashboard - Quotation Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Dashboard</h1>
    <a href="{{ route('quotations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Quotation
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card position-relative">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-value">{{ number_format($stats['customers']) }}</div>
            <div class="stat-label">Total Customers</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card success position-relative">
            <i class="bi bi-box-seam stat-icon"></i>
            <div class="stat-value">{{ number_format($stats['items']) }}</div>
            <div class="stat-label">Total Items</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card warning position-relative">
            <i class="bi bi-receipt stat-icon"></i>
            <div class="stat-value">{{ number_format($stats['quotations']) }}</div>
            <div class="stat-label">Total Quotations</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card info position-relative">
            <i class="bi bi-currency-rupee stat-icon"></i>
            <div class="stat-value">₹{{ number_format($stats['total_value'], 0) }}</div>
            <div class="stat-label">Total Value</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('customers.create') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <i class="bi bi-person-plus fs-1 text-primary mb-3 d-block"></i>
                <h5 class="card-title text-dark">Add Customer</h5>
                <p class="card-text text-muted small">Create a new customer record</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('items.create') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <i class="bi bi-plus-square fs-1 text-success mb-3 d-block"></i>
                <h5 class="card-title text-dark">Add Item</h5>
                <p class="card-text text-muted small">Add a new item to inventory</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('quotations.create') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <i class="bi bi-file-plus fs-1 text-warning mb-3 d-block"></i>
                <h5 class="card-title text-dark">New Quotation</h5>
                <p class="card-text text-muted small">Create a new quotation</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('terms.create') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <i class="bi bi-file-earmark-text fs-1 text-info mb-3 d-block"></i>
                <h5 class="card-title text-dark">Add Terms</h5>
                <p class="card-text text-muted small">Add terms & conditions</p>
            </div>
        </a>
    </div>
</div>

<!-- Recent Quotations -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Recent Quotations</span>
        <a href="{{ route('quotations.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        @if($recentQuotations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Quotation #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentQuotations as $quotation)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $quotation->quotation_number }}</span>
                                </td>
                                <td>{{ $quotation->customer->name }}</td>
                                <td>{{ $quotation->quotation_date->format('d M Y') }}</td>
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
                                    <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-3">No quotations yet. Create your first quotation!</p>
                <a href="{{ route('quotations.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Create Quotation
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

