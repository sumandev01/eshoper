@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Edit Faq - ' . $faq?->question)
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header py-4 d-flex justify-content-between align-items-center">
                        <h4 class="title mb-0">Add Faq</h4>
                        <button onclick="history.back()" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-arrow-left btn-icon-prepend me-1"></i>
                            Back
                        </button>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.faq.update', $faq?->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <x-input label="Question" name="question" type="text" placeholder="Enter question"
                                :required='true' :value="$faq?->question" />
                            <x-textarea label="Answer" :editor="true" name="answer" placeholder="Enter answer"
                                :required='true' :value="$faq?->answer" />
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
@endsection

