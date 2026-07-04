@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Newsletters')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header pt-4">
                    <h5>All Subscribed Emails</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="newsletterTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th>Email</th>
                                <th>Subscribed At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($newsletters ?? [] as $key => $newsletter)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td>{{ $newsletter->email }}</td>
                                    <td>{{ $newsletter->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.newsletter.destroy', $newsletter->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger border-0" onclick="return confirm('Are you sure you want to delete this subscriber?')">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Subscriber Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $newsletters->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
