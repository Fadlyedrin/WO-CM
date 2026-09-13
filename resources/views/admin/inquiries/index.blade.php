@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Inquiries & Messages</h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-top">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 border-0 py-3 text-muted fw-semibold">Date</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Name</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Email</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Message Preview</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Status</th>
                        <th class="border-0 py-3 text-muted fw-semibold text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($inquiries as $msg)
                    <tr class="{{ !$msg->is_read ? 'bg-light border-start border-4 border-primary' : '' }}">
                        <td class="ps-4 py-3 text-muted small">{{ $msg->created_at->format('d M Y, H:i') }}</td>
                        <td class="py-3 fw-bold text-dark">{{ $msg->name }}</td>
                        <td class="py-3 text-muted"><a href="mailto:{{ $msg->email }}" class="text-decoration-none">{{ $msg->email }}</a></td>
                        <td class="py-3 text-muted">{{ Str::limit($msg->message, 40) }}</td>
                        <td class="py-3">
                            @if($msg->is_read)
                                <span class="badge bg-light text-secondary border px-3 py-2 fw-medium">Read</span>
                            @else
                                <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium">New</span>
                            @endif
                        </td>
                        <td class="text-end pe-4 py-3">
                            <a href="{{ route('admin.inquiries.show', $msg) }}" class="btn btn-sm btn-light text-info fw-medium"><i class="bi bi-eye"></i></a>
                            @if(!$msg->is_read)
                            <form action="{{ route('admin.inquiries.markAsRead', $msg) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-light text-success fw-medium"><i class="bi bi-check-all"></i></button>
                            </form>
                            @endif
                            <form action="{{ route('admin.inquiries.destroy', $msg) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger fw-medium" onclick="return confirm('Delete this message?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No messages yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection