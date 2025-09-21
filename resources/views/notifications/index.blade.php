@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Notifications</h4>
                    @if($notifications->count() > 0)
                        <form method="POST" action="{{ route('notifications.mark-all-read') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                Mark All as Read
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ is_null($notification->read_at) ? 'list-group-item-info' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            @if(isset($notification->data['title']))
                                                {{ $notification->data['title'] }}
                                            @else
                                                Notification
                                            @endif
                                            @if(is_null($notification->read_at))
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endif
                                        </h6>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>

                                    @if(isset($notification->data['message']))
                                        <p class="mb-1">{{ $notification->data['message'] }}</p>
                                    @endif

                                    @if(isset($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-outline-primary mt-2">
                                            View Details
                                        </a>
                                    @endif

                                    @if(is_null($notification->read_at))
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" style="display: inline;" class="mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-bell fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-muted">When you receive notifications, they will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
