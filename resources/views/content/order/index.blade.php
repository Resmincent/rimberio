@extends('layouts.v_template')
<style>
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

    .status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .status-process {
        background-color: #17a2b8;
        color: white;
    }

    .status-cancelled {
        background-color: #dc3545;
        color: white;
    }

    .status-completed {
        background-color: #28a745;
        color: white;
    }

    .order-details {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

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

</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Management</h3>
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

                <div class="card-body">
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" href="{{ route('orders.index') }}">
                                All Orders
                                <span class="badge badge-primary ml-2">
                                    {{ array_sum($statusCounts) }}
                                </span>
                            </a>
                        </li>
                        @php
                        $statusLabels = [
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'process' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled'
                        ];
                        @endphp
                        @foreach($statusLabels as $key => $label)
                        <li class="nav-item">
                            <a class="nav-link {{ $status === $key ? 'active' : '' }}" href="{{ route('orders.index', ['status' => $key]) }}">
                                {{ $label }}
                                <span class="badge badge-secondary ml-2">
                                    {{ $statusCounts[$key] ?? 0 }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                    </ul>

                    @if($orders->isEmpty())
                    <div class="text-center">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
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
                    <div class="card order-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Order #{{ $order->order_id }}</h5>
                                <small class="text-muted">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-status status-{{ $order->status }} mr-3">
                                    {{ ucfirst($order->status) }}
                                </span>

                                @if(Auth::user()->is_admin)
                                @include('content.order.partials.admin_actions', ['order' => $order])
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Customer Details</h6>
                                    <p class="mb-1">
                                        <i class="fas fa-user mr-2"></i>{{ $order->customer_name }}
                                    </p>
                                    <p>
                                        <i class="fas fa-envelope mr-2"></i>{{ $order->customer_email }}
                                    </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h6>Order Summary</h6>
                                    <p class="mb-1">Total Items: {{ $order->orderItems->count() }}</p>
                                    <p>Total Amount: {{ formatRupiah($order->total_amount) }}</p>
                                </div>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
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
                                            <td colspan="3" class="text-right"><strong>Total</strong></td>
                                            <td class="text-right"><strong>{{ formatRupiah($order->total_amount) }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }}
                            of {{ $orders->total() }} entries
                        </div>
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success'
            , title: 'Success'
            , text: '{{ session('
            success ') }}'
            , showConfirmButton: false
            , timer: 3000
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error'
            , title: 'Error'
            , text: '{{ session('
            error ') }}'
            , showConfirmButton: true
        });
        @endif

        // Confirmation for status change forms
        $('.btn-sm').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionText = $(this).text().toLowerCase();

            Swal.fire({
                title: 'Are you sure?'
                , text: `Do you want to ${actionText} this order?`
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

</script>

@endsection
