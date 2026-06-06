@extends('dashboard.layouts.app')
@section('title', $siteSettings?->site_title . ' - ' . 'Contact Us')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header py-3">
                <h3>Contact Us</h3>
                <p class="mb-0 text-muted">List of all contact messages</p>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="contactTable">
                        <thead class="table-light">
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th class="text-end">Date</th>
                            <th class="text-end">Action</th>
                        </thead>
                        <tbody>
                            @forelse ($contacts ?? [] as $key => $contact)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ $contact->subject }}</td>
                                <td>{{ $contact->message }}</td>
                                <td class="text-end">{{ $contact->created_at->format('d-M-Y') }}</td>
                                <td class="text-end">
                                    <a href=""
                                        class="btn btn-primary btn-sm">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <a href=""
                                        class="btn btn-danger btn-sm deleteBtn">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No Data Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#contactTable').DataTable();
        });
    </script>
@endpush