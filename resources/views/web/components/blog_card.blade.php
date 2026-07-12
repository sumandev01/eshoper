<div class="card border-0 mb-4 theme-shadow hover-up transition-all h-100" style="border-radius: 12px; overflow: hidden;">
    <div class="position-relative overflow-hidden">
        <a href="{{ route('web.blogs.show', $blog->slug) }}">
            <img class="img-fluid w-100" src="{{ $blog->thumbnail }}" alt="{{ $blog->title }}" style="aspect-ratio: 4/3; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        </a>
        <div class="position-absolute bg-primary text-white px-3 py-1 rounded" style="bottom: 10px; left: 10px; font-size: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            {{ $blog->created_at->format('d M Y') }} &bull; {{ $blog->category?->name ?? 'News' }}
        </div>
    </div>
    <div class="card-body bg-white p-4">
        <h5 class="text-truncate" title="{{ $blog->title }}">
            <a href="{{ route('web.blogs.show', $blog->slug) }}" class="text-dark text-decoration-none">{{ $blog->title }}</a>
        </h5>
        <p class="text-muted mb-3" style="font-size: 14px;">{{ Str::limit(strip_tags($blog->content), 80) }}</p>
        <a href="{{ route('web.blogs.show', $blog->slug) }}" class="text-primary text-decoration-none font-weight-bold" style="font-size: 14px;">Read More <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
</div>
