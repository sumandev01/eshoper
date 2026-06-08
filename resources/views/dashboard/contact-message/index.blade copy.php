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
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th class="text-end">Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contacts ?? [] as $key => $contact)
                                    <tr class="{{ $contact->status == 0 ? 'table-warning' : '' }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->phone }}</td>
                                        <td>{{ $contact->subject }}</td>
                                        <td>{{ $contact->message }}</td>
                                        <td class="text-end">{{ $contact->created_at->format('d-M-Y') }}</td>
                                        <td class="text-end">
                                            <button data-id="{{ $contact->id }}" data-name="{{ $contact->name }}"
                                                data-email="{{ $contact->email }}" data-phone="{{ $contact->phone }}"
                                                data-subject="{{ $contact->subject }}"
                                                data-message="{{ $contact->message }}"
                                                data-date="{{ $contact->created_at->format('d-M-Y') }}"
                                                data-status="{{ $contact->status }}"
                                                class="btn btn-primary btn-sm viewBtn">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                            <a href="" class="btn btn-danger btn-sm deleteBtn">
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

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel">
        <div class="modal-dialog">
            <div class="modal-content p-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewModalLabel">Contact Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-2 col-8 fw-bold">Name</div>
                        <div class="col-auto fw-bold">:</div>
                        <div class="col-md-8 col-12"><span id="modalName"></span></div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-2 col-8 fw-bold">Email</div>
                        <div class="col-auto fw-bold">:</div>
                        <div class="col-md-8 col-12"><span id="modalEmail"></span></div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-2 col-8 fw-bold">Phone</div>
                        <div class="col-auto fw-bold">:</div>
                        <div class="col-md-8 col-12"><span id="modalPhone"></span></div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-2 col-8 fw-bold">Subject</div>
                        <div class="col-auto fw-bold">:</div>
                        <div class="col-md-8 col-12"><span id="modalSubject"></span></div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-2 col-8 fw-bold">Message</div>
                        <div class="col-auto fw-bold">:</div>
                        <div class="col-md-8 col-12"><span id="modalMessage"></span></div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-2 col-8 fw-bold">Date</div>
                        <div class="col-auto fw-bold">:</div>
                        <div class="col-md-8 col-12"><span id="modalDate"></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#contactTable').DataTable();

            $('.viewBtn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                let subject = $(this).data('subject');
                let message = $(this).data('message');
                let status = $(this).data('status');
                let date = $(this).data('date');

                $('#viewModal').modal('show');

                // Map data strings to elements safely first
                $('#modalName').text(name);
                $('#modalEmail').text(email);
                $('#modalPhone').text(phone);
                $('#modalSubject').text(subject);
                $('#modalMessage').text(message);
                $('#modalDate').text(date);

                if (status == 0) {
                    $.ajax({
                        url: "{{ route('admin.contact-message.view', ':id') }}",
                        method: 'GET',
                        data: {
                            id: id
                        },
                        success: function(response) {}
                    });
                }
            });

            $('#viewModal').on('hide.bs.modal', function() {
                if (document.activeElement) {
                    document.activeElement.blur();
                }
            });
        });
    </script>
@endpush
