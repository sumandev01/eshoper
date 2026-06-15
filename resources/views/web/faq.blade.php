@extends('web.layouts.app')
@section('title', 'FAQ' . ' - ' . $siteSettings?->site_title)
@section('content')

    <div class="hero-section text-center pt-5">
        <div class="container">
            <h1 class="display-4">FAQ</h1>
            <p class="lead text-muted">Frequently Asked Questions</p>
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 mb-5 mb-md-0">
                    <div class="accordion custom-bs5-accordion" id="accordionExample">
                        @foreach ($faqs ?? [] as $key => $faq)
                            <div class="card">
                                <div class="card-header" id="heading-{{ $faq?->id }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left {{ $key == 0 ? '' : 'collapsed' }}"
                                            type="button" data-toggle="collapse"
                                            data-target="#collapse-{{ $faq?->id }}"
                                            aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse-{{ $faq?->id }}">
                                            {{ $faq?->question }}
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse-{{ $faq?->id }}" class="collapse {{ $key == 0 ? 'show' : '' }}"
                                    aria-labelledby="heading-{{ $faq?->id }}" data-parent="#accordionExample">
                                    <div class="card-body">
                                       {!! $faq?->answer !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .custom-bs5-accordion .card {
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 0;
            margin-bottom: -1px;
        }

        .custom-bs5-accordion .card:first-of-type {
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }

        .custom-bs5-accordion .card:last-of-type {
            border-bottom-left-radius: .25rem;
            border-bottom-right-radius: .25rem;
        }

        .custom-bs5-accordion .card-header {
            padding: 0;
            margin-bottom: 0;
            background-color: #fff;
            border-bottom: 0;
        }

        .custom-bs5-accordion .card-header h2 {
            margin-bottom: 0;
        }

        .custom-bs5-accordion .btn-link {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            background-color: #fff;
            border: 0;
            border-radius: 0;
            text-decoration: none;
            transition: color .15s ease-in-out, background-color .15s ease-in-out;
        }

        .custom-bs5-accordion .btn-link:focus,
        .custom-bs5-accordion .btn-link:hover {
            text-decoration: none;
            box-shadow: none;
        }

        .custom-bs5-accordion .btn-link[aria-expanded="true"] {
            color: #e7f1ff !important;
            background-color: #D19C97;
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .125);
        }

        .custom-bs5-accordion .btn-link::after {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            margin-left: auto;
            content: "";
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-size: 1.25rem;
            transition: transform .2s ease-in-out;
        }

        .custom-bs5-accordion .btn-link[aria-expanded="true"]::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230c63e4'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transform: rotate(-180deg);
        }

        .custom-bs5-accordion .card-body {
            padding: 1rem 1.25rem;
        }

        .custom-bs5-accordion .collapse.show {
            border-top: 1px solid rgba(0, 0, 0, .125);
        }
    </style>
@endpush
