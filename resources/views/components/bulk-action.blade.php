@props(['url', 'param' => 'ids'])

<div id="bulkActionContainer" class="d-none">
    <div class="d-flex align-items-center bg-primary ps-2 text-white">
        <span class="me-3 fw-medium"><span id="selectedCount">0</span> items selected</span>
        <button type="button" class="btn btn-sm btn-danger rounded-0 bulkDeleteBtn" data-url="{{ $url }}" data-param="{{ $param }}">
            <i class="mdi mdi-delete me-1"></i>
        </button>
    </div>
</div>