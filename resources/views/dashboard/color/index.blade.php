@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header pt-4">
                    <h5>All Colors</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="colorTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th>Name</th>
                                <th>Color Code</th>
                                <th class="text-center">Color</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($colors ?? [] as $key => $color)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td> {{ $color->name }} </td>
                                    <td> {{ $color->color_code }} </td>
                                    <td class="text-center">
                                        <div class="mx-auto d-block" style="background-color: {{ $color->color_code }}; border: 2px solid #ccc; width: 30px; height: 30px; border-radius: 50%;">
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('color.edit', $color?->id) }}" class="btn btn-info btn-sm">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        <a href="{{ route('color.destroy', $color?->id) }}"
                                            class="btn btn-danger btn-sm deleteBtn">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No Colors Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <form action="{{ route('color.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header pt-4">
                        <h4 class="card-title">Add New Color</h4>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" placeholder="Enter color name"
                            :required='true' />
                        <x-colorpicker name="color_code" label="Color Code" palaceholder="Select Color" :required='true' />
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Add Color</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        #colorPicker{
            width: 50px;
            height: 50px;
            border-radius: 50% !important;
            border: 1px solid #ddd;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#colorTable').DataTable();
        })
    </script>
@endpush
