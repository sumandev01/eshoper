@extends('dashboard.layouts.app')
@section('title', 'Shipping Costs')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1 fw-semibold">Shipping Costs</h5>
                </div>
                <a href="{{ route('admin.shipping_cost.create') }}" class="btn btn-primary btn-sm">Add New</a>
            </div>
        </div>
        <div class="card-body pt-3">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name (Zone)</th>
                        <th>Price</th>
                        <th>Assigned States</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shippingCosts as $sc)
                        <tr>
                            <td>{{ $sc->location }}</td>
                            <td>{{ number_format($sc->price, 2) }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($sc->states as $state)
                                        <span class="badge bg-secondary">{{ $state->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.shipping_cost.edit', $sc->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                                <a href="{{ route('admin.shipping_cost.destroy', $sc->id) }}" class="btn btn-sm btn-danger deleteBtn">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
