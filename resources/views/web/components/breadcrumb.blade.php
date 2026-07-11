<div class="container mb-4">
    <div class="row">
        <div class="col-12">
            <div class="wpo-breadcumb-wrap">
                <ol class="mb-0 p-0">
                    <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                    @if(isset($breadcrumbs) && is_array($breadcrumbs))
                        @foreach($breadcrumbs as $breadcrumb)
                            @if(isset($breadcrumb['url']) && $breadcrumb['url'])
                                <li><a class="nav-link p-0" href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a></li>
                            @else
                                <li><span>{{ $breadcrumb['name'] }}</span></li>
                            @endif
                        @endforeach
                    @else
                        <li>{{ $title ?? 'Page' }}</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .wpo-breadcumb-wrap ol li,
    .wpo-breadcumb-wrap ol a,
    .wpo-breadcumb-wrap ol span{
        font-size: 14px !important;
        line-height: 1.5 !important;
    }
    .wpo-breadcumb-wrap ol li a {
        color: var(--primary) !important;
        transition: color 0.3s ease;
    }
    .wpo-breadcumb-wrap ol li a:hover {
        color: var(--primary-dark) !important;
    }
    .wpo-breadcumb-wrap ol li span,
    .wpo-breadcumb-wrap ol li {
        color: var(--dark);
    }
</style>
@endpush
