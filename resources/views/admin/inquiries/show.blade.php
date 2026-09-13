@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Inquiry Details</h2>
    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to List</a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body p-4">
        <h4 class="mb-4">Message from <strong>{{ $inquiry->name }}</strong></h4>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="mb-1 text-muted small text-uppercase">Email</p>
                <p class="fw-bold"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></p>
            </div>
            <div class="col-md-6">
                <p class="mb-1 text-muted small text-uppercase">Phone / WhatsApp</p>
                <p class="fw-bold">{{ $inquiry->phone ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1 text-muted small text-uppercase">Date Received</p>
                <p class="fw-bold">{{ $inquiry->created_at->format('l, d F Y H:i A') }}</p>
            </div>
        </div>
        
        <div class="bg-light p-4 rounded border">
            <p class="mb-1 text-muted small text-uppercase">Message Content</p>
            <p class="mb-0" style="white-space: pre-wrap; font-size: 1.1rem; line-height: 1.6;">{{ $inquiry->message }}</p>
        </div>
        
        <div class="mt-4 pt-3 border-top d-flex gap-2">
            <a href="mailto:{{ $inquiry->email }}" class="btn btn-primary"><i class="bi bi-envelope"></i> Reply via Email</a>
            @if($inquiry->phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}" target="_blank" class="btn btn-success"><i class="bi bi-whatsapp"></i> Reply via WhatsApp</a>
            @endif
            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="ms-auto">
                @csrf @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Delete this message?')"><i class="bi bi-trash"></i> Delete Message</button>
            </form>
        </div>
    </div>
</div>
@endsection