@extends('layouts.app')

@section('title', 'Items - Quotation Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Items</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
    .barcode {
        max-width: 150px;
        height: auto;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Items</h1>
    <a href="{{ route('items.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Item
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($items->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Cost Price</th>
                            <th>Sale Price</th>
                            <th>GST %</th>
                            <th>Barcode</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $item->name }}</span>
                                </td>
                                <td>
                                    @if($item->category)
                                        <span class="badge bg-secondary">{{ $item->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>₹{{ number_format($item->cost_price, 2) }}</td>
                                <td class="fw-semibold text-success">₹{{ number_format($item->sale_price, 2) }}</td>
                                <td>{{ $item->gst_percentage }}%</td>
                                <td>
                                    @if($item->barcode)
                                        <svg class="barcode" data-barcode="{{ $item->barcode }}"></svg>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
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
            
            @if($items->hasPages())
                <div class="card-footer bg-white">
                    {{ $items->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-box-seam fs-1 text-muted"></i>
                <p class="text-muted mt-3">No items found. Add your first item!</p>
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Add Item
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.barcode').forEach(function(svg) {
        const barcodeValue = svg.dataset.barcode;
        if (barcodeValue) {
            try {
                JsBarcode(svg, barcodeValue, {
                    format: "CODE128",
                    width: 1.5,
                    height: 40,
                    displayValue: true,
                    fontSize: 12,
                    margin: 5
                });
            } catch (e) {
                svg.outerHTML = '<code>' + barcodeValue + '</code>';
            }
        }
    });
});
</script>
@endpush
