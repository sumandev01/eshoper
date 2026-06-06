@extends('dashboard.layouts.app')
@section('title', $siteSettings?->site_title . ' - ' . 'Edit Team Member - ' . $teamMember?->name)
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Edit Team Member</h4>
                            <p class="mb-0 text-muted">Update Team Member</p>
                        </div>
                        <button onclick="history.back()" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                            <i class="mdi mdi-arrow-left"></i>
                            <span>Back to List</span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.team-member.update', $teamMember->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <x-input name="name" label="Name" :value="old('name') ?? $teamMember?->name" class="fs-6" type="text"
                                    :required="true" />
                            </div>
                            <div class="col-md-12">
                                <x-input name="position" label="Position" :value="old('position') ?? $teamMember?->position" class="fs-6" type="text"
                                    :required="true" />
                            </div>
                            <div class="col-md-12">
                                @php
                                    $teamMemberImage = Storage::url($teamMember->media->src);
                                @endphp
                                <x-media-thumbnail label="Profile Image" button_label="Select Image" input_name="media_id" :existing_image="$teamMemberImage" :existing_id="$teamMember->media_id" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
