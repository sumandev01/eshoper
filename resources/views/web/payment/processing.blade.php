@extends('web.layouts.app')
@section('title', 'Confirming Payment - ' . ($siteSettings->site_title ?? 'E-Shopper'))

@section('content')
<div class="container py-5 min-vh-100 d-flex flex-column justify-content-center align-items-center text-center">
    
    <div id="loading-section">
        <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h2 class="mt-4 fw-bold text-dark">Processing Your Payment</h2>
        <p class="text-muted fs-5">Please wait while we confirm your payment securely.</p>
        <p class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Do not close or refresh this page.</p>
    </div>

    <div id="error-section" class="d-none">
        <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
        <h2 class="mt-4 fw-bold text-dark">Payment Verification Taking Too Long</h2>
        <p class="text-muted fs-5">Your payment is still being processed in the background.</p>
        <p>We will email you once the payment is fully confirmed.</p>
        <a href="{{ route('user.orders') }}" class="btn btn-primary mt-3">Go to My Orders</a>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const orderNumber = "{{ $order->order_number }}";
        const statusUrl = "{{ route('stripe.status', ['orderNumber' => $order->order_number]) }}";
        let attempts = 0;
        const maxAttempts = 20; // Will poll for about 60 seconds (every 3 seconds)

        const checkPaymentStatus = setInterval(() => {
            attempts++;

            fetch(statusUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.payment_status === 'paid') {
                        clearInterval(checkPaymentStatus);
                        // Redirect to the actual thank you page
                        window.location.href = data.redirect_url;
                    } else if (attempts >= maxAttempts) {
                        clearInterval(checkPaymentStatus);
                        document.getElementById('loading-section').classList.add('d-none');
                        document.getElementById('error-section').classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error fetching payment status:', error);
                });

        }, 3000); // 3 seconds interval
    });
</script>
@endpush


