@php
    $sliders = App\Models\Slider::where('is_active', 1)->orderBy('order')->get();
@endphp
<div id="header-carousel" class="carousel slide rounded-0" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach ($sliders as $key => $slider)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" style="height: 410px;">
                <img class="img-fluid" src="{{ Storage::url($slider->media?->src) }}" alt="Image"
                    @if($key == 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif>
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 700px; text-shadow: 0 4px 15px rgba(0,0,0,0.8);">
                        <h4 class="text-light text-uppercase font-weight-medium mb-3">{{ $slider->title }}
                        </h4>
                        <h3 class="display-4 text-white font-weight-semi-bold mb-4">{{ $slider->subtitle }}</h3>
                        <a href="{{ $slider->link }}"
                            class="btn btn-primary py-2 px-3">{{ $slider->button_text }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#header-carousel" data-bs-slide="prev">
        <div class="btn slider-btn d-flex align-items-center border-0" style="width: 45px; height: 45px;">
            <span class="carousel-control-prev-icon mb-n2"></span>
        </div>
    </a>
    <a class="carousel-control-next" href="#header-carousel" data-bs-slide="next">
        <div class="btn slider-btn d-flex align-items-center border-0" style="width: 45px; height: 45px;">
            <span class="carousel-control-next-icon mb-n2"></span>
        </div>
    </a>
</div>
</div>

@push('styles')
<style>
    .slider-btn {
        background-color: var(--primary) !important;
        color: white !important;
        transition: background-color 0.3s ease;
    }
    .slider-btn:hover {
        background-color: var(--primary-dark) !important;
    }
</style>
@endpush
