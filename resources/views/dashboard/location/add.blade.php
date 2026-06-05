@extends('dashboard.layouts.app')
@section('title', $siteSettings?->site_title . ' - ' . 'Add Location')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-semibold">Add New Location</h5>
                            <p class="text-muted small mb-0">Fill in the details to create a new location</p>
                        </div>
                        <a href="{{ route('admin.location.index') }}"
                            class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                            <i class="mdi mdi-arrow-left"></i>
                            <span>Back to List</span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.location.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="division" class="form-label">Division</label>
                            <input list="division_list" class="form-control" id="division" name="division"
                                value="{{ old('division') }}" placeholder="Enter or Select Your Division Name" required
                                autocomplete="off">
                            <input type="hidden" value="{{ old('division_id') }}" name="division_id" id="division_id">
                            <datalist id="division_list">
                                @foreach ($divisions as $data)
                                    <option data-id="{{ $data->id }}" value="{{ $data->name }}">
                                @endforeach
                            </datalist>
                            @error('division')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="district" class="form-label">District</label>
                            <input list="district_list" class="form-control" id="district" name="district" required
                                autocomplete="off" value="{{ old('district') }}"
                                placeholder="Enter or Select Your District Name">
                            <input type="hidden" value="{{ old('district_id') }}" name="district_id" id="district_id">
                            <datalist id="district_list">
                                @foreach ($districts as $data)
                                    <option data-id="{{ $data->id }}" value="{{ $data->name }}">
                                @endforeach
                            </datalist>
                            @error('district')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="thana" class="form-label">Thana</label>
                            <input type="text" class="form-control" id="thana" name="thana"
                                value="{{ old('thana') }}" placeholder="Enter Your Thana Name" required>
                            @error('thana')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('input change', '#division', function() {
                let inputValue = $(this).val();
                let $hiddenInput = $('#division_id');
                let matchFound = false;

                $('#division_list option').each(function() {
                    if ($(this).val() === inputValue) {
                        $hiddenInput.val($(this).data('id'));
                        matchFound = true;
                        return false;
                    }
                });

                if (!matchFound) {
                    $hiddenInput.val('');
                }

                console.log('Text:', inputValue, ' | ID:', $hiddenInput.val());
            });

            $(document).on('input change', '#district', function() {
                let inputValue = $(this).val();
                let $hiddenInput = $('#district_id');
                let matchFound = false;

                $('#district_list option').each(function() {
                    if ($(this).val() === inputValue) {
                        $hiddenInput.val($(this).data('id'));
                        matchFound = true;
                        return false;
                    }
                });

                if (!matchFound) {
                    $hiddenInput.val('');
                }

                console.log('Text:', inputValue, ' | ID:', $hiddenInput.val());
            });
        });
    </script>
@endpush
