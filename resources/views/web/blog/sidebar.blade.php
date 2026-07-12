            <!-- Sidebar (Right Side) -->
            <div class="col-lg-4 col-md-12 mt-5 mt-lg-0">
                <!-- Search -->
                <div class="bg-white p-4 theme-shadow mb-5" style="border-radius: 12px;">
                    <h5 class="font-weight-semi-bold mb-4" style="color: color-mix(in srgb, var(--primary) 60%, #111);">Search</h5>
                    <form action="{{ route('web.blogs.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control px-3" name="search" value="{{ request('search') }}" placeholder="Keyword" style="border-radius: 8px 0 0 8px; border: 1px solid rgba(0,0,0,0.1); background-color: #f8f9fa;">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary px-3" style="border-radius: 0 8px 8px 0;">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Category -->
                <div class="bg-white p-4 theme-shadow mb-5" style="border-radius: 12px;">
                    <h5 class="font-weight-semi-bold mb-4" style="color: color-mix(in srgb, var(--primary) 60%, #111);">Categories</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 mb-2 transition-all category-link" onmouseover="this.style.paddingLeft='10px'" onmouseout="this.style.paddingLeft='0'">
                            <a href="{{ route('web.blogs.index') }}" class="text-decoration-none transition-all {{ !request('category') ? 'text-primary font-weight-bold' : 'text-muted' }}" style="font-size: 0.95rem;">All Categories</a>
                        </li>
                        @foreach($categories as $category)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 mb-2 transition-all category-link" onmouseover="this.style.paddingLeft='10px'" onmouseout="this.style.paddingLeft='0'">
                                <a href="{{ route('web.blogs.index', ['category' => $category->slug]) }}" class="text-decoration-none transition-all {{ request('category') == $category->slug || (isset($blog) && $blog->category_id == $category->id) ? 'text-primary font-weight-bold' : 'text-muted' }}" style="font-size: 0.95rem;">{{ $category->name }}</a>
                                <span class="badge rounded-pill" style="background-color: color-mix(in srgb, var(--primary) 12%, white); color: var(--primary);">{{ $category->blogs_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Recent Post -->
                <div class="bg-white p-4 theme-shadow mb-5" style="border-radius: 12px;">
                    <h5 class="font-weight-semi-bold mb-4" style="color: color-mix(in srgb, var(--primary) 60%, #111);">Recent Posts</h5>
                    @forelse($recentBlogs as $recent)
                        <div class="d-flex align-items-center mb-4 transition-all hover-up p-2 rounded" style="background-color: white;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                            <a href="{{ route('web.blogs.show', $recent->slug) }}" class="overflow-hidden" style="border-radius: 8px;">
                                <img class="img-fluid" src="{{ $recent->thumbnail }}" style="width: 70px; height: 70px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" alt="{{ $recent->title }}">
                            </a>
                            <div class="d-flex flex-column justify-content-center ps-3">
                                <a href="{{ route('web.blogs.show', $recent->slug) }}" class="text-decoration-none mb-1 transition-all" style="line-height: 1.3; font-size: 0.95rem; color: color-mix(in srgb, var(--primary) 60%, #111); font-weight: 500;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='color-mix(in srgb, var(--primary) 60%, #111)'">{{ Str::limit($recent->title, 40) }}</a>
                                <small class="text-muted"><i class="fa fa-calendar-alt text-primary me-2"></i>{{ $recent->created_at->format('d M, Y') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No recent posts.</p>
                    @endforelse
                </div>
            </div>


