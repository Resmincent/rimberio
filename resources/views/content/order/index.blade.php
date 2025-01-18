@extends('layouts.v_template')

@section('styles')
<style>
    /* Card Styles */
    .order-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Tab Styles */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 1.5rem;
    }

    .nav-tabs .nav-link {
        border-radius: 0;
        padding: 1rem 1.5rem;
        font-weight: 500;
        color: #6c757d;
        position: relative;
        border: none;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: #007bff;
        background: transparent;
        border: none;
    }

    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #007bff;
    }

    /* Status Badge Styles */
    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .status-paid {
        background-color: #28a745;
        color: white;
    }

    .status-process {
        background-color: #ffc107;
        color: #000;
    }

    .status-completed {
        background-color: #17a2b8;
        color: white;
    }

    .status-cancelled {
        background-color: #dc3545;
        color: white;
    }

    /* Table Styles */
    .table thead th {
        background-color: #f8f9fa;
        border-top: none;
        font-weight: 600;
    }

    .table td {
        vertical-align: middle;
    }

    /* Button Styles */
    .btn-action {
        padding: 0.375rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Empty State Styles */
    .empty-state {
        text-align: center;
        padding: 3rem 0;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    /* Pagination Styles */
    .pagination {
        margin: 0;
    }

    .page-link {
        border-radius: 50px;
        margin: 0 0.25rem;
        padding: 0.5rem 1rem;
    }

</style>
@endsection

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Order Management</h1>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Status Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" href="{{ route('orders.index') }}">
                    All Orders
                    <span class="badge badge-primary ml-2">{{ array_sum($statusCounts) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'paid' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'paid']) }}">
                    Paid
                    <span class="badge badge-success ml-2">{{ $statusCounts['paid'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'process' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'process']) }}">
                    Processing
                    <span class="badge badge-warning ml-2">{{ $statusCounts['process'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'completed' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'completed']) }}">
                    Completed
                    <span class="badge badge-info ml-2">{{ $statusCounts['completed'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'cancelled' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'cancelled']) }}">
                    Cancelled
                    <span class="badge badge-danger ml-2">{{ $statusCounts['cancelled'] ?? 0 }}</span>
                </a>
            </li>
        </ul>

        @if($orders->isEmpty())
        <div class="empty-state text-center py-5">
            <i class="fas fa-shopping-cart"></i>
            <h4>No Orders Found</h4>
            <p class="text-muted">
                @if($status !== 'all')
                No orders with status "{{ ucfirst($status) }}"
                @else
                No orders available at the moment
                @endif
            </p>
        </div>
        @else
        @foreach($orders as $order)
        <div class="order-card card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Order #{{ $order->order_id }}</h5>
                        <div class="text-muted">
                            <small>
                                <i class="fas fa-user mr-1"></i> {{ $order->customer_name }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-envelope mr-1"></i> {{ $order->customer_email }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar mr-1"></i> {{ $order->created_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-status status-{{ $order->status }} mr-3">
                            {{ ucfirst($order->status) }}
                        </span>
                        @if($order->status === 'paid')
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to mark this order as Processing?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="process" class="btn btn-warning btn-action">
                                <i class="fas fa-cogs mr-1"></i> Process Order
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">{{ formatRupiah($item->price) }}</td>
                                <td class="text-right">{{ formatRupiah($item->quantity * $item->price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                                <td class="text-right"><strong>{{ formatRupiah($order->total_amount) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-3 text-right">
                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-action">
                            <i class="fas fa-trash mr-1"></i> Delete Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }}
                of {{ $orders->total() }} orders
            </div>
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Flash message auto-hide
        $('.alert').delay(5000).fadeOut(500);
    });

</script>
@endsection
