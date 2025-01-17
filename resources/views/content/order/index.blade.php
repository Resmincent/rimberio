@extends('layouts.admin')

@section('styles')
<style>
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85em;
    }

    .status-pending {
        background-color: #ffeeba;
        color: #856404;
    }

    .status-paid {
        background-color: #d4edda;
        color: #155724;
    }

    .status-expired {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-cancelled {
        background-color: #e2e3e5;
        color: #383d41;
    }

</style>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1>Daftar Pesanan</h1>

        <!-- Filter Status -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="statusFilter" data-toggle="dropdown">
                Filter Status
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item {{ !request('status') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                    Semua
                </a>
                <a class="dropdown-item {{ request('status') === 'pending' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'pending']) }}">Pending</a>
                <a class="dropdown-item {{ request('status') === 'paid' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'paid']) }}">Paid</a>
                <a class="dropdown-item {{ request('status') === 'expired' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'expired']) }}">Expired</a>
                <a class="dropdown-item {{ request('status') === 'cancelled' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'cancelled']) }}">Cancelled</a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Email</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_email }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ formatRupiah($order->total_amount) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                Tidak ada pesanan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Confirm delete
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                $(this).closest('form').submit();
            }
        });
    });

</script>
@endsection
