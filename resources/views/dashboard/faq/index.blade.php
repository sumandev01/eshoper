@extends('dashboard.layouts.app')
@section('title', $siteSettings?->site_title . ' - ' . 'Faq')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header py-3">
                        <h4 class="title mb-0">Faq List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-start" style="width: 60px">Sl</th>
                                        <th>Question</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="faqSortable">
                                    @forelse ($faqs ?? [] as $faq)
                                        <tr data-id="{{ $faq->id }}">
                                            <td class="text-start" style="width: 60px">{{ $loop?->iteration }}</td>
                                            <td>{{ $faq?->question }}</td>
                                            <td class="text-end">
                                                <button class="btn btn-primary btn-sm viewBtn" data-id="{{ $faq->id }}"
                                                    data-question="{{ $faq->question }}" data-answer="{{ $faq->answer }}"
                                                    data-bs-toggle="modal" data-bs-target="#viewFaqModal">
                                                    <i class="mdi mdi-eye btn-icon-prepend"></i>
                                                </button>
                                                <a href="{{ route('admin.faq.edit', $faq->id) }}"
                                                    class="btn btn-primary btn-sm editBtn">
                                                    <i class="mdi mdi-pencil btn-icon-prepend"></i>
                                                </a>
                                                <a href="{{ route('admin.faq.destroy', $faq->id) }}"
                                                    class="btn btn-danger btn-sm deleteBtn">
                                                    <i class="mdi mdi-delete btn-icon-prepend"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No Faq Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h4 class="title mb-0">Add Faq</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.faq.store') }}" method="post">
                            @csrf
                            <x-input label="Question" name="question" type="text" placeholder="Enter question"
                                :required='true' />
                            <x-textarea label="Answer" :editor="true" name="answer" placeholder="Enter answer"
                                :required='true' />
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                                <span>Add Faq</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal View -->
    <div class="modal fade" id="viewFaqModal" aria-labelledby="viewFaqModalLabel">
        <div class="modal-dialog">
            <div class="modal-content p-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewFaqModalLabel">View Faq</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="viewFaqModal">
                        <p class="mb-2"><strong>Question:</strong> <span class="question"></span></p>
                        <p class="mb-2"><strong>Answer:</strong> <span class="answer"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#faqTable').DataTable();

            $('.viewBtn').on('click', function() {
                var question = $(this).data('question');
                var answer = $(this).data('answer');
                $('.question').html(question);
                $('.answer').html(answer);
            });

            Sortable.create(document.getElementById('faqSortable'), {
            animation: 150,
            onEnd: function(evt) {
                let order = [];
                $('#faqSortable tr').each(function(index) {
                    order.push($(this).data('id'));
                });
                // Send the new order to the server via AJAX
                $.ajax({
                    url: '{{ route('admin.faq.reorder') }}',
                    method: 'POST',
                    data: {
                        order: order,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#faqSortable tr').each(function(index) {
                            $(this).find('td:first').text(index + 1);
                        });
                    },
                    error: function(xhr) {}
                });
            }
        })
        });
    </script>
@endpush
