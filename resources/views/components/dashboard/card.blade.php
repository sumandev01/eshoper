@props([
    'color' => 'primary',
    'title',
    'icon' => 'mdi-bookmark-outline',
    'value',
    'percentage' => null,
    'increasedText' => 'Increased by',
    'decreasedText' => 'Decreased by',
    'neutralText' => 'No change',
    'cardId' => '',
    'titleId' => '',
    'valueId' => ''
])

<div class="col-md-3 stretch-card grid-margin">
    <div {{ $cardId ? 'id='.$cardId : '' }} class="card bg-gradient-{{ $color }} card-img-holder text-white" style="transition: all 0.3s ease;">
        <div class="card-body">
            <img src="{{ asset('dashboard/assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3 d-flex justify-content-between align-items-center">
                <span>
                    <span {{ $titleId ? 'id='.$titleId : '' }}>{{ $title }}</span> 
                    <i class="mdi {{ $icon }} mdi-24px ms-2"></i>
                </span>
                
                @if(isset($dropdown))
                    {{ $dropdown }}
                @endif
            </h4>
            <h2 class="mb-5" {{ $valueId ? 'id='.$valueId : '' }}>{{ $value }}</h2>
            
            @if($percentage !== null)
                <h6 class="card-text">
                    @if ($percentage > 0)
                        {{ $increasedText }} {{ $percentage }}%
                    @elseif($percentage < 0)
                        {{ $decreasedText }} {{ abs($percentage) }}%
                    @else
                        {{ $neutralText }} 0%
                    @endif
                </h6>
            @endif
        </div>
    </div>
</div>
