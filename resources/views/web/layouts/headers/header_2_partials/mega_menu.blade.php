<div class="mega shadow-sm" style="display: flex; padding: 25px; background: #fff; width: max-content; max-width: 100%; overflow-x: auto; border-radius: 16px; margin-top: 10px;">
    @foreach ($categories->take(20)->chunk(10) as $chunkIndex => $chunk)
        <div class="mega-pair" style="display: flex; gap: 30px; padding-right: 30px; {{ $chunkIndex > 0 ? 'border-left: 1px solid var(--line); padding-left: 30px;' : '' }}">
            
            <!-- Categories Column -->
            <div class="mega-cats-col" style="min-width: 150px;">
                <p class="mega-col-title" style="margin-bottom: 15px; font-weight: 700; color: var(--ink);">
                    Categories
                </p>
                <ul class="mega-cats" style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($chunk as $category)
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('category.products', $category?->slug) }}" class="mega-cat-trigger" data-target="mega-sub-{{ $category->id }}" style="display: inline-block; font-size: 14px; color: var(--ink-soft); text-decoration: none; transition: all 0.2s;">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Subcategories Column -->
            <div class="mega-sub-col" style="min-width: 150px;">
                <p class="mega-col-title" style="margin-bottom: 15px; font-weight: 700; color: var(--ink);">
                    Sub-Categories
                </p>
                @foreach ($chunk as $index => $category)
                    <div class="mega-sub" id="mega-sub-{{ $category->id }}" style="display: {{ ($chunkIndex == 0 && $index == 0) ? 'grid' : 'none' }}; grid-template-columns: 1fr; gap: 15px;">
                        @foreach ($category->subCategories as $subCat)
                            <a href="{{ route('subcategory.products', $subCat?->slug) }}" style="font-size: 14px; color: var(--ink); text-decoration: none; transition: color 0.2s;">{{ $subCat->name }}</a>
                        @endforeach
                        @if ($category->subCategories->count() == 0)
                            <span style="color: var(--ink-soft); font-size: 13px;">No sub-categories</span>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    @endforeach
</div>
