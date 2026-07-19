@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Menu Builder')
@section('content')
    <div class="row">
        <!-- Left Side: Menu Structure & Dropdown -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Menu Structure</h5>
                    <!-- The Dropdown Moved Here -->
                    <select id="menu-selector" class="form-select w-auto">
                        @foreach ($menus as $m)
                            <option value="{{ $m->id }}" data-action="{{ route('admin.menus.items.store', $m->id) }}"
                                data-name="{{ $m->name }}"
                                {{ $activeMenu && $activeMenu->id == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body p-4">
                    @foreach ($menus as $m)
                        <div class="menu-list-container" id="menu-list-{{ $m->id }}"
                            style="display: {{ $activeMenu && $activeMenu->id == $m->id ? 'block' : 'none' }};">
                            <ul class="list-group">
                                @forelse($m->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $item->title }}</strong>
                                            <span class="badge bg-secondary ms-2">{{ ucfirst($item->type) }}</span>
                                            <small class="text-muted ms-2">
                                                @if ($item->type == 'custom')
                                                    {{ $item->url }}
                                                @elseif($item->type == 'system')
                                                    Route: {{ $item->reference_id }}
                                                @else
                                                    ID: {{ $item->reference_id }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-primary edit-menu-btn"
                                                data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                                data-url="{{ $item->url }}" data-type="{{ $item->type }}"
                                                data-bs-toggle="modal" data-bs-target="#editMenuModal">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <a href="{{ route('admin.menus.items.destroy', $item->id) }}"
                                                class="btn btn-danger deleteBtn btn-sm" title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No items added to this menu yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                    <small class="text-muted d-block mt-4"><i class="mdi mdi-information-outline"></i> <em>Note: Drag and
                            drop features will be added soon. Currently sorting by order added.</em></small>
                </div>
            </div>
        </div>

        <!-- Right Side: Add Menu Item Form -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header pt-4">
                    <h5 id="add-menu-item-title">Add Menu Item to "{{ $activeMenu ? $activeMenu->name : 'Menu' }}"</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="system-tab" data-bs-toggle="tab" data-bs-target="#system"
                                type="button" role="tab">System Pages</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pages-tab" data-bs-toggle="tab" data-bs-target="#pages"
                                type="button" role="tab">Dynamic Pages</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories"
                                type="button" role="tab">Categories</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="custom-tab" data-bs-toggle="tab" data-bs-target="#custom"
                                type="button" role="tab">Custom Link</button>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content mt-4">
                        <!-- System Pages -->
                        <div class="tab-pane active" id="system" role="tabpanel">
                            <form class="menu-add-form"
                                action="{{ route('admin.menus.items.store', $activeMenu ? $activeMenu->id : 0) }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="type" value="system">
                                <x-select name="reference_id" label="Select System Page" required="true">
                                    <option value="root">Home</option>
                                    <option value="shop">Shop</option>
                                    <option value="about">About Us</option>
                                    <option value="contact">Contact Us</option>
                                    <option value="faq">FAQs</option>
                                    <option value="orderTracking">Order Tracking</option>
                                </x-select>
                                <x-input name="title" label="Link Title" placeholder="Leave empty to use default name"
                                    :required="true" />
                                <button type="submit" class="btn btn-primary btn-sm">Add to Menu</button>
                            </form>
                        </div>

                        <!-- Dynamic Pages -->
                        <div class="tab-pane" id="pages" role="tabpanel">
                            <form class="menu-add-form"
                                action="{{ route('admin.menus.items.store', $activeMenu ? $activeMenu->id : 0) }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="type" value="page">
                                <x-select name="reference_id" label="Select Page" required="true">
                                    @foreach ($pages as $page)
                                        <option value="{{ $page->id }}">{{ $page->title }}</option>
                                    @endforeach
                                </x-select>
                                <x-input name="title" label="Link Title" placeholder="Page Link" required="true" />
                                <button type="submit" class="btn btn-primary btn-sm">Add to Menu</button>
                            </form>
                        </div>

                        <!-- Categories -->
                        <div class="tab-pane" id="categories" role="tabpanel">
                            <form class="menu-add-form"
                                action="{{ route('admin.menus.items.store', $activeMenu ? $activeMenu->id : 0) }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="type" value="category">
                                <x-select name="reference_id" label="Select Category" required="true">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input name="title" label="Link Title" placeholder="Category Link"
                                    required="true" />
                                <button type="submit" class="btn btn-primary btn-sm">Add to Menu</button>
                            </form>
                        </div>

                        <!-- Custom Link -->
                        <div class="tab-pane" id="custom" role="tabpanel">
                            <form class="menu-add-form"
                                action="{{ route('admin.menus.items.store', $activeMenu ? $activeMenu->id : 0) }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="type" value="custom">
                                <x-input name="url" label="URL" placeholder="https://" required="true" />
                                <x-input name="title" label="Link Title" placeholder="Menu Link" required="true" />
                                <button type="submit" class="btn btn-primary btn-sm">Add to Menu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="edit-menu-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMenuModalLabel">Edit Menu Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <x-input name="title" id="edit-title" label="Link Title" required="true" />
                        <div id="edit-url-container" style="display: none;">
                            <x-input name="url" id="edit-url" label="URL" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuSelector = document.getElementById('menu-selector');
            const menuContainers = document.querySelectorAll('.menu-list-container');
            const menuForms = document.querySelectorAll('.menu-add-form');
            const addMenuItemTitle = document.getElementById('add-menu-item-title');

            // No-reload Menu Switching
            menuSelector.addEventListener('change', function() {
                const selectedMenuId = this.value;
                const selectedOption = this.options[this.selectedIndex];
                const selectedActionUrl = selectedOption.getAttribute('data-action');
                const selectedMenuName = selectedOption.getAttribute('data-name');

                // Hide all lists, show the selected one
                menuContainers.forEach(container => {
                    container.style.display = 'none';
                });
                const activeContainer = document.getElementById('menu-list-' + selectedMenuId);
                if (activeContainer) {
                    activeContainer.style.display = 'block';
                }

                // Update all forms' action URL so new items go to the right menu
                menuForms.forEach(form => {
                    form.setAttribute('action', selectedActionUrl);
                });

                // Update the title on the left card so the user knows exactly where it's being added
                if (addMenuItemTitle) {
                    addMenuItemTitle.innerText = 'Add Menu Item to "' + selectedMenuName + '"';
                }
            });

            // Edit Modal Data Binding
            const editButtons = document.querySelectorAll('.edit-menu-btn');
            const editForm = document.getElementById('edit-menu-form');
            const editTitle = document.getElementById('edit-title');
            const editUrl = document.getElementById('edit-url');
            const editUrlContainer = document.getElementById('edit-url-container');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    const type = this.getAttribute('data-type');
                    const url = this.getAttribute('data-url');

                    // Update form action with correct item ID
                    editForm.setAttribute('action', `/admin/menus/items/${id}`);

                    editTitle.value = title;

                    if (type === 'custom') {
                        editUrlContainer.style.display = 'block';
                        editUrl.value = url;
                    } else {
                        editUrlContainer.style.display = 'none';
                        editUrl.value = '';
                    }
                });
            });
        });
    </script>
@endpush
