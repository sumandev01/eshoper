@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Payment Gateways Settings')
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header py-4">
                <div class="page-title-box">
                    <h3 class="mb-0">Payment Gateways</h3>
                    <p class="text-muted fw-bold mt-2 mb-0">Enable or disable payment methods for checkout</p>
                </div>
            </div>
            <div class="card-body py-3 px-md-4 px-2">

                <form action="{{ route('admin.settings.payment-gateways.update') }}" method="POST">
                    @csrf
                    <div class="container mt-4">

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0 fw-bold fs-5 text-primary">Stripe Payment</label>
                                <p class="text-muted small mb-0">Allow users to pay with Credit/Debit cards via Stripe.</p>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="payment_stripe_status" value="1"
                                        id="payment_stripe_status" {{ ($siteSettings->payment_stripe_status ?? '0') == '1' ? 'checked' : '' }} style="transform: scale(1.5); margin-left: -1.5rem;">
                                    <label class="form-check-label ms-3" for="payment_stripe_status">Enable Stripe</label>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0 fw-bold fs-5 text-success">SSLCommerz</label>
                                <p class="text-muted small mb-0">Allow payments via Mobile Banking (bKash, Nagad) & Local Cards.</p>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="payment_sslcommerz_status" value="1"
                                        id="payment_sslcommerz_status" {{ ($siteSettings->payment_sslcommerz_status ?? '0') == '1' ? 'checked' : '' }} style="transform: scale(1.5); margin-left: -1.5rem;">
                                    <label class="form-check-label ms-3" for="payment_sslcommerz_status">Enable SSLCommerz</label>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0 fw-bold fs-5 text-warning">Cash on Delivery</label>
                                <p class="text-muted small mb-0">Allow users to pay after receiving the product.</p>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="payment_cod_status" value="1"
                                        id="payment_cod_status" {{ ($siteSettings->payment_cod_status ?? '0') == '1' ? 'checked' : '' }} style="transform: scale(1.5); margin-left: -1.5rem;">
                                    <label class="form-check-label ms-3" for="payment_cod_status">Enable Cash on Delivery</label>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0 fw-bold fs-5 text-info">Manual Payment</label>
                                <p class="text-muted small mb-0">Direct bKash/Nagad send money instructions for users.</p>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="payment_manual_status" value="1"
                                        id="payment_manual_status" {{ ($siteSettings->payment_manual_status ?? '0') == '1' ? 'checked' : '' }} style="transform: scale(1.5); margin-left: -1.5rem;">
                                    <label class="form-check-label ms-3" for="payment_manual_status">Enable Manual Payment</label>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="payment_manual_instruction" class="form-label fw-bold">Instructions for Users</label>
                                    <textarea name="payment_manual_instruction" id="payment_manual_instruction" class="form-control" rows="4" placeholder="e.g. Please send money to our bKash number 017XXXXXX and enter your Transaction ID below.">{{ $siteSettings->payment_manual_instruction ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold"></div>
                            <div class="col-md-8 text-end">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">Save Payment Settings</button>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
