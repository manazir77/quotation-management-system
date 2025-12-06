@extends('layouts.app')

@section('title', 'Quotation ' . $quotation->quotation_number)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Quotations</a></li>
        <li class="breadcrumb-item active">{{ $quotation->quotation_number }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Quotation: {{ $quotation->quotation_number }}</h1>
    <div>
        <a href="{{ route('quotations.print', $quotation) }}" class="btn btn-success" target="_blank">
            <i class="bi bi-printer me-2"></i>Print
        </a>
        <a href="{{ route('quotations.index') }}" class="btn btn-outline-secondary ms-2">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Quotation Details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Quotation Details</span>
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
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Quotation #:</td>
                                <td class="fw-bold">{{ $quotation->quotation_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date:</td>
                                <td>{{ $quotation->quotation_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Time:</td>
                                <td>{{ date('h:i A', strtotime($quotation->quotation_time)) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Valid Until:</td>
                                <td>{{ $quotation->validity_date->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        @if($quotation->notes)
                            <h6 class="text-muted mb-2">Notes:</h6>
                            <p class="mb-0">{{ $quotation->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-list-ul me-2"></i>Items
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-center">GST %</th>
                                <th class="text-end">GST Amount</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotation->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $item->item_name }}</strong></td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center">{{ $item->gst_percentage }}%</td>
                                    <td class="text-end">₹{{ number_format($item->gst_amount, 2) }}</td>
                                    <td class="text-end fw-semibold">₹{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="5"></td>
                                <td class="text-end fw-semibold">Subtotal:</td>
                                <td class="text-end fw-semibold">₹{{ number_format($quotation->subtotal, 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="5"></td>
                                <td class="text-end fw-semibold">Total GST:</td>
                                <td class="text-end fw-semibold">₹{{ number_format($quotation->total_gst, 2) }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="5"></td>
                                <td class="text-end fw-bold">Grand Total:</td>
                                <td class="text-end fw-bold fs-5">₹{{ number_format($quotation->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        @if($quotation->terms_conditions)
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-file-text me-2"></i>Terms & Conditions
                </div>
                <div class="card-body">
                    <div style="white-space: pre-wrap;">{{ $quotation->terms_conditions }}</div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Customer Details -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person me-2"></i>Customer Details
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-3">{{ $quotation->customer->name }}</h5>
                @if($quotation->customer->company)
                    <p class="mb-2"><i class="bi bi-building me-2 text-muted"></i>{{ $quotation->customer->company }}</p>
                @endif
                @if($quotation->customer->email)
                    <p class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i>{{ $quotation->customer->email }}</p>
                @endif
                @if($quotation->customer->phone)
                    <p class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i>{{ $quotation->customer->phone }}</p>
                @endif
                @if($quotation->customer->gst_number)
                    <p class="mb-2"><i class="bi bi-hash me-2 text-muted"></i>GST: {{ $quotation->customer->gst_number }}</p>
                @endif
                @if($quotation->customer->address)
                    <p class="mb-0"><i class="bi bi-geo-alt me-2 text-muted"></i>{{ $quotation->customer->address }}</p>
                @endif
            </div>
        </div>

        <!-- Summary Card -->
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-4">
                <h6 class="text-white-50 mb-2">Total Amount</h6>
                <h2 class="mb-0">₹{{ number_format($quotation->total_amount, 2) }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection

