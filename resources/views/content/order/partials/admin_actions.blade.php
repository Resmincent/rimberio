@php
$statusTransitions = [
'pending' => [
['status' => 'paid', 'label' => 'Mark Paid', 'class' => 'btn-success'],
['status' => 'cancelled', 'label' => 'Cancel', 'class' => 'btn-danger']
],
'paid' => [
['status' => 'process', 'label' => 'Process', 'class' => 'btn-primary'],
['status' => 'cancelled', 'label' => 'Cancel', 'class' => 'btn-danger']
],
'process' => [
['status' => 'completed', 'label' => 'Complete', 'class' => 'btn-info'],
['status' => 'cancelled', 'label' => 'Cancel', 'class' => 'btn-danger']
]
];
@endphp
@if(isset($statusTransitions[$order->status]))
@foreach($statusTransitions[$order->status] as $transition)
<form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline mr-2 status-change-form">
    @csrf
    @method('PATCH')
    <button type="submit" name="status" value="{{ $transition['status'] }}" class="btn btn-sm {{ $transition['class'] }} status-change-btn">
        {{ $transition['label'] }} Order
    </button>
</form>
@endforeach
@endif
