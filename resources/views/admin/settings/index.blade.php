@extends('layouts.app')
@section('content')
    <div class="col-lg-9 es-py-8">
        <div class="mb-5">
            <div class="es-text-5xl es-font-mulish-bold">Settings</div>
        </div>

        <form action="{{ url('admin/store') }}" enctype="multipart/form-data" method="post" id="page-design">
            @csrf
            <div class="card border-0 es-mb-6">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8">
                    <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-text-xl">
                        Page Designs
                    </div>
                    <div class="d-flex flex-column es-mt-6">
                        <label for="business_name">Business Name</label>
                        <input id="business_name" name="business_name" type="text" class="form-control es-input mt-2"
                            placeholder="Business Name"
                            value="{{ old('business_name', $settings->business_name ?? '') }}" />
                        <div class="es-input-error-message"></div>
                    </div>

                    <div class="row">
                        <div class="d-flex flex-column col-lg-4 es-mt-6">
                            <label for="business_address_1">Business Address</label>
                            <input id="business_address_1" name="business_address1" type="text"
                                class="form-control es-input mt-2" placeholder="Street 1"
                                value="{{ old('business_address1', $settings->business_address1 ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>

                        <div class="d-flex flex-column col-lg-4 es-mt-6">
                            <label for="business_address_2">Business Address</label>
                            <input id="business_address_2" name="business_address2" type="text"
                                class="form-control es-input mt-2" placeholder="Street 2"
                                value="{{ old('business_address2', $settings->business_address2 ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>

                        <div class="d-flex flex-column col-lg-4 es-mt-6">
                            <label for="city_suburb">City/Suburb</label>
                            <input id="city_suburb" name="city" type="text" class="form-control es-input mt-2"
                                placeholder="City/Suburb" value="{{ old('city', $settings->city ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>

                        <div class="d-flex flex-column col-lg-4 es-mt-6">
                            <label for="state">State</label>
                            <input id="state" name="state" type="text" class="form-control es-input mt-2"
                                placeholder="State" value="{{ old('state', $settings->state ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>

                        <div class="d-flex flex-column col-lg-4 es-mt-6">
                            <label for="postcode">Postcode</label>
                            <input id="postcode" name="postcode" type="text" class="form-control es-input mt-2"
                                placeholder="Postcode" value="{{ old('postcode', $settings->postcode ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>

                        <div class="d-flex flex-column col-lg-4 es-mt-6">
                            <label for="business_website_address">Business Website Address</label>
                            <input id="business_website_address" name="business_website_address" type="text"
                                class="form-control es-input mt-2" placeholder="Business Website Address"
                                value="{{ old('business_website_address', $settings->business_website_address ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>

                        <div class="d-flex flex-column col-lg-4 es-mt-6">
                            <label for="business_phone_number">Business Phone Number</label>
                            <input id="business_phone_number" name="business_phone_number" type="text"
                                class="form-control es-input mt-2" placeholder="Business Phone Number"
                                value="{{ old('business_phone_number', $settings->business_phone_number ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>

                        <div class="d-flex flex-column col-lg-4 es-mt-6">
                            <label for="business_email_address">Business Email Address</label>
                            <input id="business_email_address" name="business_email_address" type="text"
                                class="form-control es-input mt-2" placeholder="Business Email Address"
                                value="{{ old('business_email_address', $settings->business_email_address ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                    </div>

                    <div class="card es-my-6">
                        <div class="card-body overflow-scroll es-h-48">
                            <div class="es-font-mulish-bold es-text-sm mb-2">
                                Payment Terms & Conditions
                            </div>
                            <div style="font-size: 13px">
                                <textarea id="terms_condition" name="terms_condition">
                                {{ old('terms_condition', $settings->terms_condition ?? '') }}
                            </textarea>

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="company_terms" value="1"
                                    {{ old('company_terms', $settings->company_terms ?? '') == 1 ? 'checked' : '' }}
                                    id="flexCheckChecked" />
                                <label class="form-check-label es-text-sm es-font-mulish-bold" for="flexCheckChecked">
                                    I have read and agree to the terms above.
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div>Logo</div>
                        <div class="es-text-gray-500 es-mt-2 es-mb-4">
                            Resolution requirements: 600x100px
                        </div>
                        <div class="mt-2">
                            <input type="file" accept=".jpg,.jpeg,.png" name="logo" hidden
                                id="photo_input_page_logo" />
                            <label for="photo_input_page_logo"
                                class="es-file-input {{ $settings && $settings->logo ? 'd-none' : '' }}"
                                id="photo_label_page_logo">
                                Upload
                                <svg class="es-svg-download" width="16" height="16" viewBox="0 0 16 16"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                        stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M4.6665 6.6665L7.99984 9.99984L11.3332 6.6665" stroke="#984A02"
                                        stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8 10V2" stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </label>
                            <div class="{{ $settings && $settings->logo ? '' : 'd-none' }}"
                                id="file_preview_container_page_logo">
                                <img src="{{ $settings && $settings->logo ? url('images/' . $settings->logo) : '#' }}"
                                    alt="Preview Uploaded Image" id="photo_preview_page_logo"
                                    class="es-h-24 es-mb-3 file-preview img-fluid img600x100" />
                                <div class="d-flex es-gap-8">
                                    <label for="photo_input_page_logo" class="btn border-0 es-text-sm es-font-600 p-0">
                                        Change
                                        <img src="{{ url('public/images/refresh.png') }}" width="14" height="14"
                                            alt="" />
                                    </label>
                                    <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                        id="clear_photo_input_page_logo">
                                        Delete
                                        <img src="{{ url('public/images/trash.png') }}" width="14" height="14"
                                            alt="Trash Icon" />
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="es-mt-6">
                        <div>Registration and Login Page Background</div>
                        <div class="es-text-gray-500 es-mt-2 es-mb-4">
                            Resolution requirements: 960x1080px
                        </div>
                        <div class="mt-2">
                            <input type="file" accept=".jpg,.jpeg,.png" hidden name="page_background_image"
                                id="photo_input_page_background" />
                            <label for="photo_input_page_background"
                                class="es-file-input {{ $settings && $settings->page_background_image ? 'd-none' : '' }}"
                                id="photo_label_page_background">
                                Upload
                                <svg class="es-svg-download" width="16" height="16" viewBox="0 0 16 16"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                        stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M4.6665 6.6665L7.99984 9.99984L11.3332 6.6665" stroke="#984A02"
                                        stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8 10V2" stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </label>
                            <div class="{{ $settings && $settings->page_background_image ? '' : 'd-none' }}"
                                id="file_preview_container_page_background">
                                <img src="{{ $settings && $settings->page_background_image ?  url('images/' . $settings->page_background_image) : '#' }}"
                                    alt="Preview Uploaded Image" id="photo_preview_page_background"
                                    class="es-h-80 es-mb-3 file-preview img960x1080 img-fluid" />
                                <div class="d-flex es-gap-8">
                                    <label for="photo_input_page_background"
                                        class="btn border-0 es-text-sm es-font-600 p-0">
                                        Change
                                        <img src="{{ url('public/images/refresh.png') }}" width="14" height="14"
                                            alt="Refresh Icon" />
                                    </label>
                                    <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                        id="clear_photo_input_page_background">
                                        Delete
                                        <img src="{{ url('public/images/trash.png') }}" width="14" height="14"
                                            alt="Trash Icon" />
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="es-mt-6 col-xl-3">
                        <button type="submit" class="es-btn es-w-full es-h-auto">
                            Save
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{ old('id', $settings->id ?? '') }}" name="id" />
        </form>

        <form action="{{ url('admin/store') }}" enctype="multipart/form-data" method="post" id="login-signup">
            @csrf
            <div class="card border-0 es-mb-6">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8">
                    <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-text-xl">
                        Login/Signup Promotion
                    </div>
                    <div class="row">
                        <div class="col-lg-6 d-flex flex-column es-mt-6">
                            <label for="ratio_1">Ratio 1</label>
                            <input id="ratio_1" name="ratio_1" type="text" class="form-control es-input mt-2"
                                placeholder="Ratio 1" value="{{ old('ratio_1', $settings->ratio_1 ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-lg-6 d-flex flex-column es-mt-6">
                            <label for="ratio_2">Ratio 2</label>
                            <input id="ratio_2" name="ratio_2" type="text" class="form-control es-input mt-2"
                                placeholder="Ratio 2" value="{{ old('ratio_2', $settings->ratio_2 ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-lg-6 d-flex flex-column es-mt-6">
                            <label for="title">Title</label>
                            <input id="title" type="text" name="title" class="form-control es-input mt-2"
                                placeholder="Title" value="{{ old('title', $settings->title ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-lg-6 d-flex flex-column es-mt-6">
                            <label for="subtitle">Subtitle</label>
                            <input id="subtitle" type="text" name="subtitle" class="form-control es-input mt-2"
                                placeholder="Subtitle" value="{{ old('subtitle', $settings->subtitle ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-lg-6 d-flex flex-column es-mt-6">
                            <label for="number">Number</label>
                            <input id="number" name="number" type="text" class="form-control es-input mt-2"
                                placeholder="xxx.xxx" value="{{ old('number', $settings->number ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-lg-6 d-flex flex-column es-mt-6">
                            <label for="countdown_timer">Countdown Timer</label>
                            <input id="countdown_timer" name="countdown_timer" type="text"
                                class="form-control es-input mt-2" placeholder="MM : DD : HH : mm : ss"
                                value="{{ old('countdown_timer', $settings->countdown_timer ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="es-mt-6 col-xl-3">
                            <button type="submit" class="es-btn es-w-full es-h-auto">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="update_ratio_time" value="true" />
            <input type="hidden" value="{{ old('id', $settings->id ?? '') }}" name="id" />
        </form>

        <form action="{{ url('admin/store') }}" enctype="multipart/form-data" method="post" id="payment-gateway">
            @csrf
            <div class="card border-0 es-mb-6">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8">
                    <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-text-xl">
                        Payment Gateway
                    </div>
                    <div class="d-flex flex-column es-mt-6">
                        Payment Gateway
                        <div>
                            <select name="payment_gateway" id="payment_gateway" class="form-select es-select mt-2">
                                {{-- <option value="0" {{  $settings && $settings->payment_gateway  == 0 ? 'selected' : '' }}>PayPal</option>
                            <option value="1" {{ $settings && $settings->payment_gateway == 1 ? 'selected' : '' }}>Stripe</option> --}}
                                <option value="2"
                                    {{ $settings && $settings->payment_gateway == 2 ? 'selected' : '' }}>Credit/Debit Cards
                                    (Square)</option>
                                <option value="3"
                                    {{ $settings && $settings->payment_gateway == 3 ? 'selected' : '' }}>Afterpay (Square)
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="d-none" id="paypalForm">
                        <div class="d-flex flex-column es-mt-6">
                            <label for="client_id">Client ID</label>
                            <input id="client_id" type="text" name="paypal_client_id"
                                class="form-control es-input mt-2" placeholder="Client ID"
                                value="{{ old('paypal_client_id', $settings->paypal_client_id ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="d-flex flex-column es-mt-6">
                            <label for="paypal_secret_key">Secret Key</label>
                            <input id="paypal_secret_key" name="paypal_secret_key" type="text"
                                class="form-control es-input mt-2" placeholder="Secret Key"
                                value="{{ old('paypal_secret_key', $settings->paypal_secret_key ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                    </div>

                    <div id="stripeForm">
                        <div class="d-flex flex-column es-mt-6">
                            <label for="publishable_key">Live/Test Publishable Key</label>
                            <input id="publishable_key" name="stripe_publishable_key" type="text"
                                class="form-control es-input mt-2" placeholder="Live/Test Publishable Key"
                                value="{{ old('stripe_publishable_key', $settings->stripe_publishable_key ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="d-flex flex-column es-mt-6">
                            <label for="secret_key">Live/Test Secret Key</label>
                            <input id="secret_key" name="stripe_secret_key" type="password"
                                class="form-control es-input mt-2" placeholder="Live/Test Secret Key"
                                value="{{ old('stripe_secret_key', $settings->stripe_secret_key ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="d-flex flex-column es-mt-6">
                            <label for="webhook_secret">Webhook Secret</label>
                            <input id="webhook_secret" name="stripe_webhook_secret" type="password"
                                class="form-control es-input mt-2" placeholder="Webhook Secret"
                                value="{{ old('stripe_webhook_secret', $settings->stripe_webhook_secret ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                    </div>

                    <div id="squareForm">
                        <div class="d-flex flex-column es-mt-6">
                            <label for="application_id">Live/Test Application ID</label>
                            <input id="application_id" name="square_application_id" type="text"
                                class="form-control es-input mt-2" placeholder="Live/Test Application ID"
                                value="{{ old('square_application_id', $settings->square_application_id ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="d-flex flex-column es-mt-6">
                            <label for="access_token">Live/Test Access Token</label>
                            <input id="access_token" name="square_access_token" type="password"
                                class="form-control es-input mt-2" placeholder="Live/Test Access Token"
                                value="{{ old('square_access_token', $settings->square_access_token ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="d-flex flex-column es-mt-6">
                            <label for="location_id">Location ID</label>
                            <input id="location_id" name="square_location_id" type="text"
                                class="form-control es-input mt-2" placeholder="Location ID"
                                value="{{ old('square_location_id', $settings->square_location_id ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                    </div>
                    <div class="es-mt-6 col-xl-3">
                        <button type="submit" class="es-btn es-w-full es-h-auto">
                            Save
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{ old('id', $settings->id ?? '') }}" name="id" />
        </form>

        <form action="{{ url('admin/store') }}" enctype="multipart/form-data" method="post" id="email-notification">
            @csrf
            <div class="card border-0 es-mb-6">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8">
                    <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-text-xl">
                        Email Notifications
                    </div>
                    <div>
                        <div>Logo</div>
                        <div class="es-text-gray-500 es-mt-2 es-mb-4">
                            Resolution requirements: 600x100px
                        </div>
                        <div class="mt-2">
                            <input type="file" accept=".jpg,.jpeg,.png" hidden id="photo_input_email_logo"
                                name="email_logo" />
                            <label for="photo_input_email_logo"
                                class="es-file-input {{ $settings && $settings->email_logo ? 'd-none' : '' }}"
                                id="photo_label_email_logo">
                                Upload
                                <svg class="es-svg-download" width="16" height="16" viewBox="0 0 16 16"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                        stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M4.6665 6.6665L7.99984 9.99984L11.3332 6.6665" stroke="#984A02"
                                        stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8 10V2" stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </label>
                            <div class="{{ $settings && $settings->email_logo ? '' : 'd-none' }}"
                                id="file_preview_container_email_logo">
                                <img src="{{ $settings && $settings->email_logo ?  url('images/' . $settings->email_logo) : '#' }}"
                                    alt="Preview Uploaded Image" id="photo_preview_email_logo"
                                    class="es-h-24 es-mb-3 file-preview img600x100 img-fluid" />
                                <div class="d-flex es-gap-8">
                                    <label for="photo_input_email_logo" class="btn border-0 es-text-sm es-font-600 p-0">
                                        Change
                                        <img src="{{ url('public/images/refresh.png') }}" width="14" height="14"
                                            alt="" />
                                    </label>
                                    <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                        id="clear_photo_input_email_logo">
                                        Delete
                                        <img src="{{ url('public/images/trash.png') }}" width="14" height="14"
                                            alt="" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="es-mt-6">
                        <div>Graphic</div>
                        <div class="es-text-gray-500 es-mt-2 es-mb-4">
                            Resolution requirements: 600x300px
                        </div>
                        <div class="mt-2">
                            <input type="file" accept=".jpg,.jpeg,.png" hidden id="photo_input_email_graphic"
                                name="email_background_image" />
                            <label for="photo_input_email_graphic"
                                class="es-file-input {{ $settings && $settings->email_background_image ? 'd-none' : '' }}"
                                id="photo_label_email_graphic">
                                Upload
                                <svg class="es-svg-download" width="16" height="16" viewBox="0 0 16 16"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                        stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M4.6665 6.6665L7.99984 9.99984L11.3332 6.6665" stroke="#984A02"
                                        stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8 10V2" stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </label>
                            <div class="{{ $settings && $settings->email_background_image ? '' : 'd-none' }}"
                                id="file_preview_container_email_graphic">
                                <img src="{{ $settings && $settings->email_background_image ? url('images/' . $settings->email_background_image) : '#' }}"
                                    {{-- src="{{  $settings && $settings->email_background_image ? $settings->email_background_image : '#' }}" --}} alt="Preview Uploaded Image" id="photo_preview_email_graphic"
                                    class="es-h-80 es-mb-3 file-preview img600x300 img-fluid" />
                                <div class="d-flex es-gap-8">
                                    <label for="photo_input_email_graphic"
                                        class="btn border-0 es-text-sm es-font-600 p-0">
                                        Change
                                        <img src="{{ url('public/images/refresh.png') }}" width="14" height="14"
                                            alt="" />
                                    </label>
                                    <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                        id="clear_photo_input_email_graphic">
                                        Delete
                                        <img src="{{ url('public/images/trash.png') }}" width="14" height="14"
                                            alt="" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex flex-column col-lg-6 es-mt-6">
                            <div class="d-flex flex-column flex-lg-row gap-2">
                                Successful Registration
                                <button type="button" data-bs-toggle="modal" data-bs-target="#viewEmailTemplate"
                                    class="bg-transparent border-0 es-text-brown-500 es-font-mulish-bold d-flex align-items-center gap-1 px-0 v-temp"
                                    data-type="registration">
                                    Preview
                                    <img src="{{ url('public/images/eye-brown.png') }}" alt="" />
                                </button>
                            </div>
                            <label for="successful_registration_subject"></label>
                            <input id="successful_registration_subject" name="successful_registration_subject"
                                type="text" class="form-control es-input mt-2" placeholder="Subject"
                                value="{{ old('successful_registration_subject', $settings->successful_registration_subject ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="d-flex flex-column col-lg-6 es-mt-6 align-self-end">
                            <label for="successful_registration_body"></label>
                            <input id="successful_registration_body" name="successful_registration_body" type="text"
                                class="form-control es-input mt-2" placeholder="Body"
                                value="{{ old('successful_registration_body', $settings->successful_registration_body ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex flex-column col-lg-6 es-mt-6">
                            <div class="d-flex flex-column flex-lg-row gap-2">
                                Payment Failed
                                <button type="button" data-bs-toggle="modal" data-bs-target="#viewEmailTemplate"
                                    class="bg-transparent border-0 es-text-brown-500 es-font-mulish-bold d-flex align-items-center gap-1 px-0 v-temp"
                                    data-type="failed">
                                    Preview
                                    <img src="{{ url('public/images/eye-brown.png') }}" alt="" />
                                </button>
                            </div>
                            <label for="payment_failed_subject"></label>
                            <input id="payment_failed_subject" name="payment_failed_subject" type="text"
                                class="form-control es-input mt-2" placeholder="Subject"
                                value="{{ old('payment_failed_subject', $settings->payment_failed_subject ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="d-flex flex-column col-lg-6 es-mt-6 align-self-end">
                            <label for="payment_failed_body"></label>
                            <input id="payment_failed_body" name="payment_failed_body" type="text"
                                class="form-control es-input mt-2" placeholder="Body"
                                value="{{ old('payment_failed_body', $settings->payment_failed_body ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex flex-column col-lg-6 es-mt-6">
                            <div class="d-flex flex-column flex-lg-row gap-2">
                                Successful Payment
                                <button type="button" data-bs-toggle="modal" data-bs-target="#viewEmailTemplate"
                                    class="bg-transparent border-0 es-text-brown-500 es-font-mulish-bold d-flex align-items-center gap-1 px-0 v-temp"
                                    data-type="success">
                                    Preview
                                    <img src="{{ url('public/images/eye-brown.png') }}" alt="" />
                                </button>
                            </div>
                            <label for="successful_payment_subject"></label>
                            <input id="successful_payment_subject" name="successful_payment_subject" type="text"
                                class="form-control es-input mt-2" placeholder="Subject"
                                value="{{ old('successful_payment_subject', $settings->successful_payment_subject ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="d-flex flex-column col-lg-6 es-mt-6 align-self-end">
                            <label for="successful_payment_body"></label>
                            <input id="successful_payment_body" name="successful_payment_body" type="text"
                                class="form-control es-input mt-2" placeholder="Body"
                                value="{{ old('successful_payment_body', $settings->successful_payment_body ?? '') }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                    </div>
                    <div class="es-mt-6 col-xl-3">
                        <button type="submit" class="es-btn es-w-full es-h-auto">
                            Save
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{ old('id', $settings->id ?? '') }}" name="id" />
        </form>

        <div class="card border-0">
            <div class="card-body es-pb-8 es-px-0">
                @if (auth()->guard('admin')->user()->hasPermission('add_user'))
                    <form action="{{ url('admin/add-user') }}" method="post" class="es-mb-6 es-px-6"
                        id="user-management">
                        @csrf
                        <div class="d-flex flex-column es-font-mulish">
                            <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-text-xl">
                                User Management
                            </div>

                            <div class="es-pb-2 es-pt-2 es-font-mulish-bold es-text-lg">
                                Add User
                            </div>
                            <div class="row">
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="first_name">First Name</label>
                                    <input id="first_name" name="f_name" type="text"
                                        class="form-control es-input mt-2" placeholder="First Name"
                                        value="{{ old('f_name') }}" />
                                    @if ($errors->has('f_name'))
                                        <div class="es-input-error-message">{{ $errors->first('f_name') }}</div>
                                    @endif
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="last_name">Last Name</label>
                                    <input id="last_name" name="l_name" type="text"
                                        class="form-control es-input mt-2" placeholder="Last Name"
                                        value="{{ old('l_name') }}" />
                                    @if ($errors->has('l_name'))
                                        <div class="es-input-error-message">{{ $errors->first('l_name') }}</div>
                                    @endif
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="phone_number">Phone Number</label>
                                    <input id="phone_number" name="phone_no" type="text"
                                        class="form-control es-input mt-2" placeholder="Phone Number"
                                        value="{{ old('phone_no') }}" />
                                    @if ($errors->has('phone_no'))
                                        <div class="es-input-error-message">{{ $errors->first('phone_no') }}</div>
                                    @endif
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="email">Email</label>
                                    <input id="email" name="email" type="text"
                                        class="form-control es-input mt-2" placeholder="Email"
                                        value="{{ old('email') }}" />
                                    @if ($errors->has('email'))
                                        <div class="es-input-error-message">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="role">Role</label>
                                    <div>
                                        <select name="user_role" id="role" class="form-select es-select mt-2">
                                            <option value="1">Admin</option>
                                            <!-- <option value="2">Member</option> -->
                                            <option value="3">Contractor</option>
                                            <option value="4">Staff</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 es-mt-6 d-flex align-items-end">
                                    <button type="submit" class="es-btn es-w-full es-h-auto">
                                        Add User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif

                <div class="es-table">
                    <div class="es-table-header d-flex align-items-center justify-content-between es-px-6">
                        <div class="es-font-mulish-bold es-text-lg">Users</div>
                        <div>
                            <button class="border-0 bg-transparent">
                                <img src="{{ url('public/images/filter-up-dark.png') }}" alt="" />
                            </button>

                            <button class="border-0 bg-transparent">
                                <img src="{{ url('public/images/filter-down-dark.png') }}" alt="" />
                            </button>
                        </div>
                    </div>
                    <div class="es-table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>View/Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->f_name }}</td>
                                        <td>{{ $user->l_name }}</td>
                                        <td>{{ $user->phone_no }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            {{ match ($user->user_role) {
                                                1 => 'Admin',
                                                2 => 'Member',
                                                3 => 'Contractor',
                                                4 => 'Staff',
                                                default => 'unknown',
                                            } }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if (auth()->guard('admin')->user()->hasPermission('edit_user'))
                                                    <a href="#viewEditUserModal" class="es-link-primary edit-user-record"
                                                        data-bs-toggle="modal" role="button"
                                                        data-f_name="{{ $user->f_name }}"
                                                        data-l_name="{{ $user->l_name }}"
                                                        data-phone_no="{{ $user->phone_no }}"
                                                        data-email="{{ $user->email }}"
                                                        data-user_role="{{ $user->user_role }}"
                                                        data-user_id="{{ $user->id }}">
                                                        View/Edit
                                                    </a>
                                                @else
                                                    <a href="#viewEditUserModal" class="es-link-primary edit-user-record"
                                                        data-bs-toggle="modal" role="button"
                                                        data-f_name="{{ $user->f_name }}"
                                                        data-l_name="{{ $user->l_name }}"
                                                        data-phone_no="{{ $user->phone_no }}"
                                                        data-email="{{ $user->email }}"
                                                        data-user_role="{{ $user->user_role }}"
                                                        data-user_id="{{ $user->id }}">
                                                        View
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{ old('id', $settings->id ?? '') }}" name="id" />
        </div>
    </div>
    </div>
    </div>

    <!-- Email Preview Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="viewEmailTemplate" tabindex="-1" role="dialog"
        aria-labelledby="viewEmailTemplateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="card">
                    <div class="card-body es-px-6 es-pt-6 es-pb-10">
                        <div class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold">
                            <p id="email-header">Successful Registration Preview</p>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="border-0 bg-transparent" data-bs-dismiss="modal">
                                    <img src="{{ url('public/images/close.png') }}" alt="" />
                                </button>
                            </div>
                        </div>
                        <div class="d-flex es-py-28 align-items-center justify-content-center">
                            <img id="email-logo" class="img-fluid img600x100" src="{{ url('public/images/logo.svg') }}"
                                alt="" />
                        </div>
                        <div class="es-mb-8">
                            <img id="email-graphic" class="img-fluid img600x300" src="{{ url('public/images/settings-img.png') }}"
                                alt="" />
                        </div>
                        <div class="es-font-mulish-bold es-text-3xl text-center es-mb-8">
                            <div id="email-subject">Welcome to Empress Spa!</div>
                        </div>
                        <div class="text-center es-text-lg es-mb-8" id="email-body">
                            We're excited to have you join our community. Your
                            registration is now complete, and you can start exploring
                            everything we have to offer. Whether you're looking to unwind
                            with a relaxing massage, rejuvenate with a facial, or simply
                            enjoy a moment of tranquility, we have something special for
                            you.
                            <br /><br />
                            You can now view your subscription plan directly through our
                            app. Simply log in to access your account, check your plan
                            details, and discover exclusive offers tailored just for you!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="viewEditUserModal" tabindex="-1" role="dialog"
        aria-labelledby="viewEditUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('public/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body es-p-6">
                        <form action="{{ url('admin/add-user') }}" method="post" id="edit-user">
                            @csrf
                            <div
                                class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold es-mb-4">
                                View/Edit User
                            </div>
                            <div class="row">
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="first_name">First Name</label>
                                    <input id="update_first_name" name="f_name" type="text"
                                        class="form-control es-input mt-2" placeholder="First Name" />
                                    <input id="user_id" name="id" type="hidden" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="last_name">Last Name</label>
                                    <input id="update_last_name" name="l_name" type="text"
                                        class="form-control es-input mt-2" placeholder="Last Name" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="phone_number">Phone Number</label>
                                    <input id="update_phone_number" name="phone_no" type="text"
                                        class="form-control es-input mt-2" placeholder="Phone Number"
                                        value="123123412" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="email">Email</label>
                                    <input id="update_email" name="email" type="text"
                                        class="form-control es-input mt-2" placeholder="Email"
                                        value="melissa@gmail.com" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    Role
                                    <div>
                                        <select name="user_role" id="update_role" class="form-select es-select mt-2">
                                            <option value="1">Admin</option>
                                            <option value="3">Contractor</option>
                                            <option value="4">Staff</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 es-mt-6 d-flex align-items-end">
                                    <button type="submit" class="es-btn es-w-full es-h-auto" data-bs-dismiss="modal">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.js"></script>
    <script>
        $('#terms_condition').summernote();

        document.addEventListener("DOMContentLoaded", function() {
            const paymentGatewaySelect = document.getElementById("payment_gateway");
            const paypalForm = document.getElementById("paypalForm");
            const stripeForm = document.getElementById("stripeForm");
            const squareForm = document.getElementById("squareForm");

            function updateFormVisibility() {
                if (paymentGatewaySelect.value === "0") {
                    paypalForm.classList.remove("d-none");
                    stripeForm.classList.add("d-none");
                    squareForm.classList.add("d-none");
                } else if (paymentGatewaySelect.value === "1") {
                    paypalForm.classList.add("d-none");
                    stripeForm.classList.remove("d-none");
                    squareForm.classList.add("d-none");
                } else if (paymentGatewaySelect.value === "2") {
                    paypalForm.classList.add("d-none");
                    stripeForm.classList.add("d-none");
                    squareForm.classList.remove("d-none");
                } else if (paymentGatewaySelect.value === "3") {
                    paypalForm.classList.add("d-none");
                    stripeForm.classList.add("d-none");
                    squareForm.classList.remove("d-none");
                }
            }

            // Initial check
            updateFormVisibility();

            // Update visibility on change
            paymentGatewaySelect.addEventListener("change", updateFormVisibility);
        });

        // Page Logo
        const photo_input_page_logo = document.getElementById(
            "photo_input_page_logo",
        );

        photo_input_page_logo.addEventListener("change", () => {
            const file = photo_input_page_logo.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById("photo_preview_page_logo");

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document
                    .getElementById("photo_label_page_logo")
                    .classList.add("d-none");
                document
                    .getElementById("file_preview_container_page_logo")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input_page_logo")
            .addEventListener("click", () => {
                document.getElementById("photo_input_page_logo").value = null;
                document
                    .getElementById("photo_label_page_logo")
                    .classList.remove("d-none");
                document
                    .getElementById("file_preview_container_page_logo")
                    .classList.add("d-none");
            });

        // Page Background
        const photo_input_page_background = document.getElementById(
            "photo_input_page_background",
        );

        photo_input_page_background.addEventListener("change", () => {
            const file = photo_input_page_background.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById(
                    "photo_preview_page_background",
                );

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document
                    .getElementById("photo_label_page_background")
                    .classList.add("d-none");
                document
                    .getElementById("file_preview_container_page_background")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input_page_background")
            .addEventListener("click", () => {
                document.getElementById("photo_input_page_background").value = null;
                document
                    .getElementById("photo_label_page_background")
                    .classList.remove("d-none");
                document
                    .getElementById("file_preview_container_page_background")
                    .classList.add("d-none");
            });

        // Email Logo
        const photo_input_email_logo = document.getElementById(
            "photo_input_email_logo",
        );

        photo_input_email_logo.addEventListener("change", () => {
            const file = photo_input_email_logo.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById("photo_preview_email_logo");

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document
                    .getElementById("photo_label_email_logo")
                    .classList.add("d-none");
                document
                    .getElementById("file_preview_container_email_logo")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input_email_logo")
            .addEventListener("click", () => {
                document.getElementById("photo_input_email_logo").value = null;
                document
                    .getElementById("photo_label_email_logo")
                    .classList.remove("d-none");
                document
                    .getElementById("file_preview_container_email_logo")
                    .classList.add("d-none");
            });

        // Email Graphic
        const photo_input_email_graphic = document.getElementById(
            "photo_input_email_graphic",
        );

        photo_input_email_graphic.addEventListener("change", () => {
            const file = photo_input_email_graphic.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById(
                    "photo_preview_email_graphic",
                );

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document
                    .getElementById("photo_label_email_graphic")
                    .classList.add("d-none");
                document
                    .getElementById("file_preview_container_email_graphic")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input_email_graphic")
            .addEventListener("click", () => {
                document.getElementById("photo_input_email_graphic").value = null;
                document
                    .getElementById("photo_label_email_graphic")
                    .classList.remove("d-none");
                document
                    .getElementById("file_preview_container_email_graphic")
                    .classList.add("d-none");
            });

        flatpickr(".flatpickr", {
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "m/y",
                    altFormat: "F Y",
                }),
            ],
        });


        $(document).on('click', '.v-temp', function() {
            var template = $(this).data('type');

            // Check if settings exist
            var emailLogo = '{{ url('images/'. $settings->email_logo) ?? '' }}';
            var emailBackgroundImage = '{{url('images/'.  $settings->email_background_image) ?? '' }}';
            var successfulRegistrationSubject = '{{ $settings->successful_registration_subject ?? '' }}';
            var successfulRegistrationBody = '{{ $settings->successful_registration_body ?? '' }}';
            var paymentFailedSubject = '{{ $settings->payment_failed_subject ?? '' }}';
            var paymentFailedBody = '{{ $settings->payment_failed_body ?? '' }}';
            var successfulPaymentSubject = '{{ $settings->successful_payment_subject ?? '' }}';
            var successfulPaymentBody = '{{ $settings->successful_payment_body ?? '' }}';

            if (template == 'registration') {
                $('#email-logo').attr('src', emailLogo);
                $('#email-graphic').attr('src', emailBackgroundImage);
                $('#email-subject').html(successfulRegistrationSubject);
                $('#email-body').html(successfulRegistrationBody);
                $('#email-header').html('Successful Registration Preview');
            } else if (template == 'failed') {
                $('#email-logo').attr('src', emailLogo);
                $('#email-graphic').attr('src', emailBackgroundImage);
                $('#email-subject').html(paymentFailedSubject);
                $('#email-body').html(paymentFailedBody);
                $('#email-header').html('Payment Failed Preview');
            } else if (template == 'success') {
                $('#email-logo').attr('src', emailLogo);
                $('#email-graphic').attr('src', emailBackgroundImage);
                $('#email-subject').html(successfulPaymentSubject);
                $('#email-body').html(successfulPaymentBody);
                $('#email-header').html('Successful Payment Preview');
            }
        });


        // jQuery function to handle dynamic modal data population
        $('.edit-user-record').click(function() {
            // Get data from the clicked link
            var fName = $(this).data('f_name');
            var lName = $(this).data('l_name');
            var phoneNo = $(this).data('phone_no');
            var email = $(this).data('email');
            var userRole = $(this).data('user_role');
            var user_id = $(this).data('user_id');

            // Populate modal inputs with the fetched data
            $('#update_first_name').val(fName);
            $('#update_last_name').val(lName);
            $('#update_phone_number').val(phoneNo);
            $('#update_email').val(email);
            $('#update_role').val(userRole).change(); // Ensure the role is selected correctly
            $('#user_id').val(user_id); // Ensure the role is selected correctly


            // Show the modal
            // $('#viewEditUserModal').modal('show');
        });


        $(document).ready(function() {
            // Add custom method for countdown timer validation
            $.validator.addMethod('countdownFormat', function(value, element) {
                // Regex pattern to match "MM : DD : HH : mm : ss"
                var pattern1 = /^\d{2} : \d{2} : \d{2} : \d{2} : \d{2}$/;
                var pattern2 = /^\d{2}:\d{2}:\d{2}:\d{2}:\d{2}$/;
                return this.optional(element) || pattern1.test(value) || pattern2.test(value);
            }, 'Please enter the countdown timer in the format MM : DD : HH : mm : ss');

            // Apply the validation to the form
            $('#login-signup').validate({
                rules: {
                    countdown_timer: {
                        required: true,
                        countdownFormat: true // Use the custom rule
                    }
                },
                messages: {
                    countdown_timer: {
                        required: 'The countdown timer is required.',
                        countdownFormat: 'Please use the following format MM : DD : HH : mm : ss.'
                    }
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('es-input-error-message');
                    error.insertAfter(element);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });

        $(document).ready(function() {
            $.validator.addMethod("validPhone", function(value, element) {
                return this.optional(element) || /^[1-9]\d*$/.test(value);
            }, "Please enter a valid phone number (digits only, no leading zeros).");

            $('#user-management').validate({
                rules: {
                    f_name: {
                        required: true
                    },
                    l_name: {
                        required: true
                    },
                    phone_no: {
                        // required: true,
                        digits: true,
                        validPhone: false, // Use the custom method here
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    f_name: {
                        required: "First Name is required."
                    },
                    l_name: {
                        required: "Last Name is required."
                    },
                    phone_no: {
                        // required: "Phone Number is required.",
                        digits: "Phone Number must be numeric.",
                    },
                    email: {
                        required: "Email is required.",
                        email: "Email format is invalid."
                    }
                },
                errorClass: "es-input-error-message",
                validClass: "es-input-valid-message",
                errorElement: "div",
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    // If you want to do something before submission
                    // For example, you can display a message or log something

                    // Submit the form
                    form.submit();
                }
            });
        });
    </script>

    @if (!auth()->guard('admin')->user()->hasPermission('edit_setting') && auth()->guard('admin')->user()->user_role == 3)
        <script>
            $('form').find('input, textarea, select').prop('disabled', true);
            $('button[type="submit"]').hide(); // Hide the submit button
            // $('button[type="button"]').prop('disabled',true); // Hide the submit button
            $('.btn.border-0.es-text-sm.es-font-600.p-0').hide();
        </script>
    @endif

    @if (auth()->guard('admin')->user()->user_role == 4)
        @if (!auth()->guard('admin')->user()->hasPermission('edit_design'))
            <script>
                $('#page-design').find('input, textarea, select').prop('disabled', true);
                $('#page-design').find('button[type="submit"]').hide(); // Hide the submit button
                $('#page-design').find('button[type="button"]').prop('disabled', true); // Hide the submit button
                $('#page-design').find('.btn.border-0.es-text-sm.es-font-600.p-0').hide();
            </script>
        @endif

        @if (!auth()->guard('admin')->user()->hasPermission('edit_promotion'))
            <script>
                $('#login-signup').find('input, textarea, select').prop('disabled', true);
                $('#login-signup').find('button[type="submit"]').hide(); // Hide the submit button
                $('#login-signup').find('button[type="button"]').prop('disabled', true); // Hide the submit button
                $('#login-signup').find('.btn.border-0.es-text-sm.es-font-600.p-0').hide();
            </script>
        @endif

        @if (!auth()->guard('admin')->user()->hasPermission('edit_gateway'))
            <script>
                $('#payment-gateway').find('input, textarea, select').prop('disabled', true);
                $('#payment-gateway').find('button[type="submit"]').hide(); // Hide the submit button
                $('#payment-gateway').find('button[type="button"]').prop('disabled', true); // Hide the submit button
                $('#payment-gateway').find('.btn.border-0.es-text-sm.es-font-600.p-0').hide();
            </script>
        @endif

        @if (!auth()->guard('admin')->user()->hasPermission('edit_notifications'))
            <script>
                $('#email-notification').find('input, textarea, select').prop('disabled', true);
                $('#email-notification').find('button[type="submit"]').hide(); // Hide the submit button
                $('#email-notification').find('button[type="button"]').prop('disabled', true); // Hide the submit button
                $('#email-notification').find('.btn.border-0.es-text-sm.es-font-600.p-0').hide();
            </script>
        @endif

        @if (!auth()->guard('admin')->user()->hasPermission('edit_user'))
            <script>
                $('#edit-user').find('input, textarea, select').prop('disabled', true);
                $('#edit-user').find('button[type="submit"]').hide(); // Hide the submit button
                $('#edit-user').find('button[type="button"]').prop('disabled', true); // Hide the submit button
                $('#edit-user').find('.btn.border-0.es-text-sm.es-font-600.p-0').hide();
            </script>
        @endif
    @endif

@endsection
