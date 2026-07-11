@extends('dashboard.layouts.app')
@section('title', 'Add Shipping Cost')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom py-3">
            <h5 class="mb-1 fw-semibold">Add Shipping Cost Zone</h5>
        </div>
        <div class="card-body pt-3">
            <form action="{{ route('admin.shipping_cost.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Zone Name (e.g., Inside Dhaka)</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" value="0" min="0" step="1" required>
                    </div>
                </div>
                
                <h6 class="mt-4 mb-3 fw-bold">Select States for this Zone</h6>
                
                @foreach($states->groupBy('country.name') as $countryName => $countryStates)
                    <h6 class="mt-3 mb-2 fw-bold text-primary">{{ $countryName }}</h6>
                    <div class="row country-group-{{ Str::slug($countryName) }}">
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input select-all-country" type="checkbox" id="selectAll-{{ Str::slug($countryName) }}" data-country="{{ Str::slug($countryName) }}">
                                <label class="form-check-label text-primary fw-bold" for="selectAll-{{ Str::slug($countryName) }}" style="cursor: pointer;">
                                    Select All
                                </label>
                            </div>
                        </div>
                        @foreach($countryStates as $state)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="state_ids[]" value="{{ $state->id }}" id="state-{{ $state->id }}">
                                    <label class="form-check-label" for="state-{{ $state->id }}">
                                        {{ $state->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                
                <button type="submit" class="btn btn-primary mt-4">Save Shipping Cost</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.select-all-country').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const countrySlug = this.getAttribute('data-country');
            const stateCheckboxes = document.querySelectorAll(`.country-group-${countrySlug} input[name="state_ids[]"]`);
            stateCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });
</script>
@endpush
