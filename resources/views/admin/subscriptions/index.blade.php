@extends('layouts.app')
@section('content')
    <style>
        .position-relative {
            position: relative;
        }

        .tooltip-text {
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            background-color: black;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10;
            display: none;
            /* Initially hidden */
        }

        .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: black transparent transparent transparent;
        }
    </style>

    <div class="col-lg-9 es-py-8">
        <div class="mb-5">
            <div class="es-text-5xl es-font-mulish-bold">Subscriptions</div>
        </div>

        <div class="d-flex flex-column flex-md-row gap-3 es-mb-8">
            <div class="card border-0 w-100 rounded-3">
                <div class="card-body es-p-4 es-text-gray-900">
                    <div class="d-flex align-items-center es-mb-4">
                        <div
                            class="es-rounded-full es-w-8 es-h-8 es-bg-brown-100 d-flex align-items-center justify-content-center es-mr-4">
                            <img src="{{ asset('images/users-black.png') }}" alt="" />
                        </div>
                        <div class="es-text-sm es-font-500">Total Subscriptions</div>
                    </div>
                    <div class="es-text-5xl es-font-600">{{ number_format($subscriptionCount) }}</div>
                </div>
            </div>
            <div class="card border-0 w-100 rounded-3">
                <div class="card-body es-p-4 es-text-gray-900">
                    <div class="d-flex align-items-center es-mb-4">
                        <div
                            class="es-rounded-full es-w-8 es-h-8 es-bg-brown-100 d-flex align-items-center justify-content-center es-mr-4">
                            <img src="{{ asset('images/credit-card.png') }}" alt="" />
                        </div>
                        <div class="es-text-sm es-font-500">
                            Total Subscriptions Value
                        </div>
                    </div>
                    <div class="es-text-5xl es-font-600">${{ number_format($subscriptionValue) }}</div>
                </div>
            </div>
            <div class="card border-0 w-100 rounded-3">
                <div class="card-body es-p-4 es-text-gray-900">
                    <div class="d-flex align-items-center es-mb-4">
                        <div
                            class="es-rounded-full es-w-8 es-h-8 es-bg-brown-100 d-flex align-items-center justify-content-center es-mr-4">
                            <img src="{{ asset('images/shopping-bag.png') }}" alt="" />
                        </div>
                        <div class="es-text-sm es-font-500">Total This Month</div>
                    </div>
                    <div class="es-text-5xl es-font-600">${{ number_format($currentMonthSubscriptionValue) }}</div>
                </div>
            </div>
            <div class="card border-0 w-100 rounded-3">
                <div class="card-body es-p-4 es-text-gray-900">
                    <div class="d-flex align-items-center es-mb-4">
                        <div
                            class="es-rounded-full es-w-8 es-h-8 es-bg-brown-100 d-flex align-items-center justify-content-center es-mr-4">
                            <img src="{{ asset('images/refresh.png') }}" alt="" />
                        </div>
                        <div class="es-text-sm es-font-500">Total Last Month</div>
                    </div>
                    <div class="es-text-5xl es-font-600">${{ number_format($lastMonthSubscriptionValue) }}</div>
                </div>
            </div>
        </div>

        <form action="">
            <div class="es-table bg-white es-rounded-lg">
                <div class="es-table-header">
                    <div
                        class="d-flex align-items-start justify-content-start flex-column flex-lg-row justify-content-lg-between gap-2">
                        <div class="es-font-mulish-bold es-text-xl">
                            Subscriptions
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <button type="button" id="sortAsc" class="border-0 bg-transparent p-0">
                                    <img src="{{ asset('images/filter-up-dark.png') }}" alt="" />
                                </button>

                                <button type="button" id="sortDesc" class="border-0 bg-transparent p-0">
                                    <img src="{{ asset('images/filter-down-dark.png') }}" alt="" />
                                </button>
                            </div>
                            @if (auth()->guard('admin')->user()->hasPermission('add_subscriptions'))
                                <div>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#addSubscriptionModal"
                                        class="es-link-primary border-0 bg-transparent es-text-lg">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 4.16699V15.8337" stroke="#BB7E45" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M4.16602 10H15.8327" stroke="#BB7E45" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Add subscription
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="es-table-container subscriptions-page">
                    <table id="subscriptionTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Photo</th>
                                <th>Description</th>
                                <th>Total Period</th>
                                <th>Date Added</th>
                                <th>Date Archived</th>
                                <th>Mapped Products</th>
                                <th>Mapped Services</th>
                                <th>Payment Frequency</th>
                                <th>Form Link</th>
                                <th>View/Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($subscriptionPlans) > 0)
                                @foreach ($subscriptionPlans as $plan)
                                    <tr>
                                        <td class="text-nowrap">{{ $plan->title }}</td>
                                        <td>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#photoModal"
                                                class="border-0 es-outline-none bg-transparent p-0 hover-darken-95"
                                                data-image="{{ $plan->photo ?? asset('images/services-image.svg') }}">
                                                <img src="{{ $plan->photo ? $plan->photo : asset('images/subscriptions-img.png') }}"
                                                    width="40" height="40" alt="Subscription Image" />
                                            </button>
                                        </td>
                                        <td class="text-nowrap">
                                            <div>{{ $plan->description }}</div>
                                        </td>
                                        <td>
                                            @switch($plan->payment_frequency)
                                                @case(1)
                                                    Weekly
                                                @break

                                                @case(2)
                                                    Monthly
                                                @break

                                                @case(3)
                                                    Quarterly
                                                @break

                                                @case(4)
                                                    Half-Yearly
                                                @break

                                                @case(5)
                                                    Yearly
                                                @break

                                                @default
                                                    Unknown
                                            @endswitch
                                        </td>
                                        <td class="text-nowrap">{{ $plan->created_at->format('M d, Y') }}</td>
                                        <td class="text-nowrap">
                                            {{ $plan->deleted_at ? $plan->deleted_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <ul class="d-flex flex-wrap gap-2 m-0">
                                                @foreach ($plan->products as $service)
                                                    <li>{{ $service->title }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <ul class="d-flex flex-wrap gap-2 m-0">
                                                @if ($plan->services->isEmpty())
                                                    <li>N/A</li>
                                                @else
                                                    @foreach ($plan->services as $service)
                                                        <li>{{ $service->title }}</li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </td>

                                        <td>{{ $plan->days }} Days</td>
                                        <td>
                                            <div class="text-break link-text">
                                                {{ $plan->subscription_form_url }}
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ $plan->subscription_form_url }}" target="_blank"
                                                    class="bg-transparent border-0">
                                                    <img src="{{ asset('images/external-link.png') }}"
                                                        alt="Open Form" />
                                                </a>
                                                <button class="bg-transparent border-0 position-relative"
                                                    onclick="copyToClipboard(event, this, '{{ $plan->subscription_form_url }}')"
                                                    title="Copy Link">
                                                    <img src="{{ asset('images/copy.png') }}" alt="Copy Link" />
                                                    <span class="tooltip-text" style="display: none;">Copied!</span>
                                                </button>

                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if (auth()->guard('admin')->user()->hasPermission('edit_subscriptions'))
                                                    <a href="#updateSubscriptionModal"
                                                        class="es-link-primary edit-subscription" data-bs-toggle="modal"
                                                        data-id="{{ $plan->id }}" data-title="{{ $plan->title }}"
                                                        data-description="{{ $plan->description }}"
                                                        data-promo_price="{{ $plan->promo_price }}"
                                                        data-promo_period="{{ $plan->promo_period }}"
                                                        data-promo_sub_title="{{ $plan->promo_sub_title }}"
                                                        data-promo_sub_title_price="{{ $plan->promo_sub_title_price }}"
                                                        data-photo="{{ $plan->photo }}"
                                                        data-payment_frequency="{{ $plan->payment_frequency }}"
                                                        data-frequency_title="{{ $plan->frequency_title }}"
                                                        data-frequency_description="{{ $plan->frequency_description }}"
                                                        data-price_of_subscription="{{ $plan->price_of_subscription }}"
                                                        data-subscription_url="{{ $plan->subscription_url }}"
                                                        data-subscription_form_url="{{ $plan->subscription_form_url }}"
                                                        data-subscription_package="{{ $plan->subscription_package }}"
                                                        data-subscription_services="{{ $plan->subscription_services }}"
                                                        role="button">
                                                        View/Edit
                                                    </a>
                                                @else
                                                    <a href="#viewSubscriptionModal"
                                                        class="es-link-primary view-subscription" data-bs-toggle="modal"
                                                        data-id="{{ $plan->id }}" data-title="{{ $plan->title }}"
                                                        data-description="{{ $plan->description }}"
                                                        data-promo_price="{{ $plan->promo_price }}"
                                                        data-promo_period="{{ $plan->promo_period }}"
                                                        data-promo_sub_title="{{ $plan->promo_sub_title }}"
                                                        data-promo_sub_title_price="{{ $plan->promo_sub_title_price }}"
                                                        data-photo="{{ $plan->photo }}"
                                                        data-payment_frequency="{{ $plan->payment_frequency }}"
                                                        data-frequency_title="{{ $plan->frequency_title }}"
                                                        data-frequency_description="{{ $plan->frequency_description }}"
                                                        data-price_of_subscription="{{ $plan->price_of_subscription }}"
                                                        data-subscription_url="{{ $plan->subscription_url }}"
                                                        data-subscription_form_url="{{ $plan->subscription_form_url }}"
                                                        data-subscription_package="{{ $plan->subscription_package }}"
                                                        data-subscription_services="{{ $plan->subscription_services }}"
                                                        role="button">
                                                        View
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="10">No Record Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div id="pagination" class="d-flex justify-content-end pt-3 me-3"></div>
            </div>
        </form>
    </div>
    </div>
    </div>

    <!-- Add Subscription Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="addSubscriptionModal" tabindex="-1" role="dialog"
        aria-labelledby="addSubscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body es-p-6">
                        <form action="{{ url('admin/subscription/plan/store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div
                                class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold es-mb-4">
                                Add Subscription
                            </div>
                            <div>
                                <div>Photo :</div>
                                <div class="es-text-gray-500 es-mt-2 es-mb-4">
                                    Resolution requirements: 500x200px
                                </div>
                                <div class="mt-2">
                                    <input type="file" name="photo" accept=".jpg,.jpeg,.png" hidden
                                        id="photo_input_subscription" />
                                    <label for="photo_input_subscription" class="es-file-input"
                                        id="photo-label-subscription">
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
                                    <div class="d-none" id="file-preview-container-subscription">
                                        <img src="#" alt="Preview Uploaded Image" id="photo-preview-subscription" 
                                            class="es-h-80 es-mb-3 file-preview img500x200" />
                                        <div class="d-flex es-gap-8">
                                            <label for="photo_input_subscription"
                                                class="btn border-0 es-text-sm es-font-600 p-0">
                                                Change
                                                <img src="{{ asset('images/refresh.png') }}" width="14"
                                                    height="14" alt="" />
                                            </label>
                                            <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                                id="clear_photo_input_subscription">
                                                Delete
                                                <img src="{{ asset('images/trash.png') }}" width="14"
                                                    height="14" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="title">Title</label>
                                    <input id="title" name="title" type="text"
                                        class="form-control es-input mt-2" placeholder="Title" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="description">Description</label>
                                    <input id="description" name="description" type="text"
                                        class="form-control es-input mt-2" placeholder="Description" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="promo_price">Promo Price</label>
                                    <input id="promo_price" name="promo_price" type="text"
                                        class="form-control es-input mt-2" placeholder="Promo Price" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="promo_period">Promo Period</label>
                                    <input id="promo_period" name="promo_period" type="text"
                                        class="form-control es-input mt-2" placeholder="Promo Period" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="promo_sub_title">Promo SubTitle</label>
                                    <input id="promo_sub_title" name="promo_sub_title" type="text"
                                        class="form-control es-input mt-2" placeholder="Promo SubTitle" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="promo_sub_title_price">Promo SubTitle Price</label>
                                    <input id="promo_sub_title_price" name="promo_sub_title_price" type="text"
                                        class="form-control es-input mt-2" placeholder="Promo SubTitle Price" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <!-- Subscription Package Dropdown -->
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="subscription_package_subscription_modal" class="mb-2">
                                        Subscription Products
                                    </label>
                                    <div>
                                        <select class="form-select subscription_package"
                                            id="subscription_package_subscription_modal" name="subscription_package[]"
                                            multiple>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="subscription_service_subscription_modal" class="mb-2">
                                        Subscription Services
                                    </label>
                                    <div>
                                        <select class="form-select subscription_services"
                                            id="subscription_service_subscription_modal" name="subscription_services[]"
                                            multiple>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="es-text-lg es-font-mulish-bold es-mt-6">
                                Payment Frequency
                            </div>
                            <div class="row">
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="title_2">Title</label>
                                    <input id="title_2" name="frequency_title" type="text"
                                        class="form-control es-input mt-2" placeholder="Title" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="description_2">Description</label>
                                    <input id="description_2" name="frequency_description" type="text"
                                        class="form-control es-input mt-2" placeholder="Description" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="price_inc_gst">Price inc. GST</label>
                                    <input id="price_inc_gst" name="price_of_subscription" type="number"
                                        class="form-control es-input mt-2" placeholder="$0.00" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="frequency">Frequency</label>
                                    <div>
                                        <select class="form-select es-select mt-2" id="frequency"
                                            name="payment_frequency">
                                            <option value="1">Weekly</option>
                                            <option value="2">Monthly</option>
                                            <option value="3">Quarterly</option>
                                            <option value="4">Half-Yearly</option>
                                            <option value="5">Yearly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex flex-column es-mt-6">
                                    <label for="subscription_url">Subscription URL</label>
                                    <input id="subscription_url" name="subscription_url" type="text"
                                        class="form-control es-input mt-2" placeholder="Subscription URL"
                                        value="{{ url('register/' . $subs_id) }}" readonly />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="d-flex flex-column es-mt-6">
                                    <label for="subscription_form">Subscription Form</label>
                                    <div class="d-flex">
                                        <input id="subscription_form" name="subscription_form_url" type="text"
                                            class="form-control es-input mt-2 es-w-full es-pr-20"
                                            placeholder="Subscription Form" value="{{ url('register/' . $subs_id) }}"
                                            readonly />

                                        <div class="d-flex gap-1 es--ml-16">
                                            <button class="bg-transparent border-0 p-0">
                                                <img src="{{ asset('images/external-link-dark.png') }}"
                                                    alt="" />
                                            </button>
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#subscriptionFormModal"
                                                class="bg-transparent border-0 p-0 preview-subs-modal">
                                                <img src="{{ asset('images/edit-dark.png') }}" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                    <div class="es-input-error-message"></div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-6 es-mt-6 d-flex align-items-end">
                                <button type="submit" class="es-btn es-w-full es-h-auto">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Subscription Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="updateSubscriptionModal" tabindex="-1" role="dialog"
        aria-labelledby="updateSubscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body es-p-6">
                        <form action="{{ url('admin/subscription/plan/store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div
                                class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold es-mb-4">
                                Update Subscription
                            </div>
                            <div>
                                <div>Photo</div>
                                <div class="mt-2">
                                    <input type="hidden" name="id" id="id" />
                                    <input type="hidden" name="photo_url" id="update_photo_url" />
                                    <input type="file" accept=".jpg,.jpeg,.png" hidden
                                        id="update_photo_input_subscription" name="photo" />
                                    <label for="update_photo_input_subscription" class="es-file-input"
                                        id="update-photo-label-subscription">
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
                                    <div class="d-none" id="update-file-preview-container-subscription">
                                        <img src="#" alt="Preview Uploaded Image"
                                            id="update-photo-preview-subscription"
                                            class="img-fluid es-h-80 es-mb-3 file-preview" />
                                        <div class="d-flex es-gap-8">
                                            <label for="update_photo_input_subscription"
                                                class="btn border-0 es-text-sm es-font-600 p-0">
                                                Change
                                                <img src="{{ asset('images/refresh.png') }}" width="14"
                                                    height="14" alt="" />
                                            </label>
                                            <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                                id="clear_update_photo_input_subscription">
                                                Delete
                                                <img src="{{ asset('images/trash.png') }}" width="14"
                                                    height="14" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="update_title">Title</label>
                                    <input id="update_title" name="title" type="text"
                                        class="form-control es-input mt-2" placeholder="Title" value="Essential" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="update_description">Description</label>
                                    <input id="update_description" name="description" type="text"
                                        class="form-control es-input mt-2" placeholder="Description"
                                        value="Full Set Pink & White (French)" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="promo_price">Promo Price</label>
                                    <input id="update_promo_price" name="promo_price" type="text"
                                        class="form-control es-input mt-2" placeholder="Promo Price" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="promo_period">Promo Period</label>
                                    <input id="update_promo_period" name="promo_period" type="text"
                                        class="form-control es-input mt-2" placeholder="Promo Period" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="promo_sub_title">Promo SubTitle</label>
                                    <input id="update_promo_sub_title" name="promo_sub_title" type="text"
                                        class="form-control es-input mt-2" placeholder="Promo SubTitle" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="promo_sub_title_price">Promo SubTitle Price</label>
                                    <input id="update_promo_sub_title_price" name="promo_sub_title_price" type="text"
                                        class="form-control es-input mt-2" placeholder="Promo SubTitle Price" />
                                    <div class="es-input-error-message"></div>
                                </div>

                                <!-- Subscription Package Dropdown -->
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="update_subscription_package_subscription_modal" class="mb-2">
                                        Subscription Package
                                    </label>
                                    <div>
                                        <select class="form-select subscription_package" name="subscription_package[]"
                                            id="update_subscription_package_subscription_modal" multiple>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="update_subscription_service_subscription_modal" class="mb-2">
                                        Subscription Services
                                    </label>
                                    <div>
                                        <select class="form-select subscription_services"
                                            id="update_subscription_service_subscription_modal"
                                            name="subscription_services[]" multiple>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="es-text-lg es-font-mulish-bold es-mt-6">
                                Payment Frequency
                            </div>
                            <div class="row">
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="update_title_2">Title</label>
                                    <input id="update_title_2" name="frequency_title" type="text"
                                        class="form-control es-input mt-2" placeholder="Title" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="update_description_2">Description</label>
                                    <input id="update_description_2" name="frequency_description" type="text"
                                        class="form-control es-input mt-2" placeholder="Description" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="update_price_inc_gst">Price inc. GST</label>
                                    <input id="update_price_inc_gst" name="price_of_subscription" type="number"
                                        class="form-control es-input mt-2" placeholder="$0.00" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="update_frequency">Frequency</label>
                                    <div>
                                        <select class="form-select es-select mt-2" id="update_frequency"
                                            name="payment_frequency">
                                            <option value="1">Weekly</option>
                                            <option value="2">Monthly</option>
                                            <option value="3">Quarterly</option>
                                            <option value="4">Half-Yearly</option>
                                            <option value="5">Yearly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex flex-column es-mt-6">
                                    <label for="update_subscription_url">Subscription URL</label>
                                    <input id="update_subscription_url" type="text" class="form-control es-input mt-2"
                                        placeholder="Subscription URL" name="subscription_url"
                                        value="https:subscription.com.au/sub/link" readonly />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="d-flex flex-column es-mt-6">
                                    <label for="update_subscription_form">Subscription Form</label>
                                    <div class="d-flex">
                                        <input id="update_subscription_form" type="text" name="subscription_form_url"
                                            class="form-control es-input mt-2 es-w-full es-pr-20"
                                            placeholder="Subscription Form" value="https:domain.com.au/sub/xxxxxxxx"
                                            readonly />

                                        <div class="d-flex align-items-center gap-1 es--ml-16">
                                            <a href="#" id="external-link" class="bg-transparent">
                                                <img src="{{ asset('images/external-link-dark.png') }}"
                                                    alt="" />
                                            </a>
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#subscriptionFormModal"
                                                class="bg-transparent border-0 p-0">
                                                <img src="{{ asset('images/edit-dark.png') }}" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                    <div class="es-input-error-message"></div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-6 es-mt-6 d-flex align-items-end">
                                <button type="submit" class="es-btn es-w-full es-h-auto">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Subscription Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="viewSubscriptionModal" tabindex="-1" role="dialog"
        aria-labelledby="viewSubscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body es-p-6">
                        <div
                            class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold es-mb-4">
                            View Subscription
                        </div>
                        <div>
                            <div>Photo</div>
                            <div class="mt-2">
                                <input type="hidden" name="id" id="id" />
                                <input type="hidden" name="photo_url" id="view_photo_url" />
                                <input type="file" accept=".jpg,.jpeg,.png" hidden id="view_photo_input_subscription"
                                    name="photo" disabled />
                                <label for="view_photo_input_subscription" class="es-file-input"
                                    id="view-photo-label-subscription">
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
                                <div class="d-none" id="view-file-preview-container-subscription">
                                    <img src="#" alt="Preview Uploaded Image" id="view-photo-preview-subscription"
                                        class="es-h-80 es-mb-3 file-preview" />
                                    <div class="d-flex es-gap-8">
                                        <label for="view_photo_input_subscription"
                                            class="btn border-0 es-text-sm es-font-600 p-0" disabled>
                                            Change
                                            <img src="{{ asset('images/refresh.png') }}" width="14"
                                                height="14" alt="" />
                                        </label>
                                        <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                            id="clear_view_photo_input_subscription" disabled>
                                            Delete
                                            <img src="{{ asset('images/trash.png') }}" width="14"
                                                height="14" alt="" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 d-flex flex-column es-mt-6">
                                <label for="view_title">Title</label>
                                <input id="view_title" name="title" type="text" class="form-control es-input mt-2"
                                    placeholder="Title" disabled />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="col-xl-4 d-flex flex-column es-mt-6">
                                <label for="view_description">Description</label>
                                <input id="view_description" name="description" type="text"
                                    class="form-control es-input mt-2" placeholder="Description" disabled />
                                <div class="es-input-error-message"></div>
                            </div>

                            <div class="col-xl-4 d-flex flex-column es-mt-6">
                                <label for="promo_price">Promo Price</label>
                                <input id="view_promo_price" name="promo_price" type="text"
                                    class="form-control es-input mt-2" placeholder="Promo Price" disabled />
                                <div class="es-input-error-message"></div>
                            </div>

                            <div class="col-xl-4 d-flex flex-column es-mt-6">
                                <label for="promo_period">Promo Period</label>
                                <input id="view_promo_period" name="promo_period" type="text"
                                    class="form-control es-input mt-2" placeholder="Promo Period" disabled />
                                <div class="es-input-error-message"></div>
                            </div>

                            <div class="col-xl-4 d-flex flex-column es-mt-6">
                                <label for="promo_sub_title">Promo SubTitle</label>
                                <input id="view_promo_sub_title" name="promo_sub_title" type="text"
                                    class="form-control es-input mt-2" placeholder="Promo SubTitle" disabled />
                                <div class="es-input-error-message"></div>
                            </div>

                            <div class="col-xl-4 d-flex flex-column es-mt-6">
                                <label for="promo_sub_title_price">Promo SubTitle Price</label>
                                <input id="view_promo_sub_title_price" name="promo_sub_title_price" type="text"
                                    class="form-control es-input mt-2" placeholder="Promo SubTitle Price" disabled />
                                <div class="es-input-error-message"></div>
                            </div>


                            <div class="col-xl-4 d-flex flex-column es-mt-6">
                                <label for="view_subscription_package_subscription_modal" class="mb-2">
                                    Subscription Package
                                </label>
                                <div>
                                    <select class="form-select subscription_package" name="subscription_package[]"
                                        id="view_subscription_package_subscription_modal" multiple disabled>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 d-flex flex-column es-mt-6">
                                <label for="view_subscription_service_subscription_modal" class="mb-2">
                                    Subscription Services
                                </label>
                                <div>
                                    <select class="form-select subscription_services"
                                        id="view_subscription_service_subscription_modal" name="subscription_services[]"
                                        multiple disabled>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="es-text-lg es-font-mulish-bold es-mt-6">
                            Payment Frequency
                        </div>
                        <div class="row">
                            <div class="col-xl-6 d-flex flex-column es-mt-6">
                                <label for="view_frequency_title">Title</label>
                                <input id="view_frequency_title" name="frequency_title" type="text"
                                    class="form-control es-input mt-2" placeholder="Title" disabled />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="col-xl-6 d-flex flex-column es-mt-6">
                                <label for="view_frequency_description">Description</label>
                                <input id="view_frequency_description" name="frequency_description" type="text"
                                    class="form-control es-input mt-2" placeholder="Description" disabled />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="col-xl-6 d-flex flex-column es-mt-6">
                                <label for="view_price_inc_gst">Price inc. GST</label>
                                <input id="view_price_inc_gst" name="price_of_subscription" type="number"
                                    class="form-control es-input mt-2" placeholder="$0.00" disabled />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="col-xl-6 d-flex flex-column es-mt-6">
                                <label for="view_frequency">Frequency</label>
                                <div>
                                    <select class="form-select es-select mt-2" id="view_frequency"
                                        name="payment_frequency" disabled>
                                        <option value="1">Weekly</option>
                                        <option value="2">Monthly</option>
                                        <option value="3">Quarterly</option>
                                        <option value="4">Half-Yearly</option>
                                        <option value="5">Yearly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="view_subscription_url">Subscription URL</label>
                                <input id="view_subscription_url" type="text" name="subscription_url"
                                    class="form-control es-input mt-2" placeholder="Subscription URL" disabled />
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="view_subscription_form">Subscription Form URL</label>
                                <input id="view_subscription_form" type="text" name="subscription_form_url"
                                    class="form-control es-input mt-2" placeholder="Subscription Form URL" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Subscription Form Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="subscriptionFormModal" tabindex="-1" role="dialog"
        aria-labelledby="subscriptionFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body es-p-6">
                        <form action="">
                            <div
                                class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold es-mb-4">
                                Subscription Form
                            </div>
                            <div class="row">
                                <div class="col-xl-6 d-flex flex-column">
                                    <label for="title_3">Title</label>
                                    <input id="title_3" type="text" class="form-control es-input mt-2"
                                        placeholder="Title" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column mt-4 mt-xl-0">
                                    <label for="description_3">Description</label>
                                    <input id="description_3" type="text" class="form-control es-input mt-2"
                                        placeholder="Description" />
                                    <div class="es-input-error-message"></div>
                                </div>
                            </div>
                            <div class="es-mt-6">
                                <div>Photo</div>
                                <div class="es-text-gray-500 es-mt-2 es-mb-4">
                                    Resolution requirements: 500x200px
                                </div>
                                <div class="mt-2">
                                    <input type="file" accept=".jpg,.jpeg,.png" hidden
                                        id="photo_input_subscription_form" />
                                    <label for="photo_input_subscription_form" class="es-file-input"
                                        id="photo-label-subscription-form">
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
                                    <div class="d-none" id="file-preview-container-subscription-form">
                                        <img src="#" alt="Preview Uploaded Image"
                                            id="photo-preview-subscription-form" class="es-h-80 es-mb-3 file-preview" />
                                        <div class="d-flex es-gap-8">
                                            <label for="photo_input_subscription_form"
                                                class="btn border-0 es-text-sm es-font-600 p-0">
                                                Change
                                                <img src="{{ asset('images/refresh.png') }}" width="14"
                                                    height="14" alt="" />
                                            </label>
                                            <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                                id="clear_photo_input_subscription_form">
                                                Delete
                                                <img src="{{ asset('images/trash.png') }}" width="14"
                                                    height="14" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="subscription_package">Subscription Package</label>
                                <input id="subscription_package" type="text" class="form-control es-input mt-2"
                                    placeholder="Services, Products..." />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="payment_frequency"> Payment Frequency </label>
                                    <div>
                                        <select class="form-select es-select mt-2" id="payment_frequency">
                                            <option>Weekly</option>
                                            <option>Monthly</option>
                                            <option>Quarterly</option>
                                            <option>Half-Yearly</option>
                                            <option>Yearly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 d-flex flex-column es-mt-6">
                                    <label for="price_of_subscription">Price of Subscription</label>
                                    <input id="price_of_subscription" type="text" class="form-control es-input mt-2"
                                        placeholder="$80 inc. GST" />
                                    <div class="es-input-error-message"></div>
                                </div>
                            </div>
                            <div class="es-text-lg es-font-mulish-bold es-mb-4 es-mt-6">
                                Personal Information
                            </div>
                            <div class="row">
                                <div class="col-xl-4 d-flex flex-column">
                                    <label for="first_name">First Name</label>
                                    <input id="first_name" type="text" class="form-control es-input mt-2"
                                        placeholder="First Name" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column mt-4 mt-xl-0">
                                    <label for="last_name">Last Name</label>
                                    <input id="last_name" type="text" class="form-control es-input mt-2"
                                        placeholder="Last Name" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column mt-4 mt-xl-0">
                                    <label for="address">Address</label>
                                    <input id="address" type="text" class="form-control es-input mt-2"
                                        placeholder="Address" />
                                    <div class="es-input-error-message"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 d-flex flex-column mt-4">
                                    <label for="phone_number">Phone Number</label>
                                    <input id="phone_number" type="text" class="form-control es-input mt-2"
                                        placeholder="Phone Number" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column mt-4">
                                    <label for="email_address">Email Address</label>
                                    <input id="email_address" type="text" class="form-control es-input mt-2"
                                        placeholder="Email Address" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <!-- Subscription Package Dropdown -->
                                <div class="col-xl-4 d-flex flex-column mt-4">
                                    <label class="mb-2" for="subscription_package_subscription_form_modal">Subscription
                                        Package</label>
                                    <div>
                                        <select class="form-select subscription_package"
                                            id="subscription_package_subscription_form_modal" multiple>
                                            <option>Product 1</option>
                                            <option>Product 2</option>
                                            <option>Product 3</option>
                                            <option>Service 1</option>
                                            <option>Service 2</option>
                                            <option>Service 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="es-text-lg es-font-mulish-bold es-mb-4 es-mt-6">
                                Payment Details
                            </div>
                            <div class="d-flex flex-column">
                                <label for="name_on_card">Name on Card</label>
                                <input id="name_on_card" type="text" class="form-control es-input mt-2"
                                    placeholder="Name on Card" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="card_number">Card Number</label>
                                    <input id="card_number" type="password" class="form-control es-input mt-2"
                                        placeholder="Card Number" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="expiration_date">Expiration Date</label>
                                    <input id="expiration_date" type="text" class="form-control es-input mt-2"
                                        placeholder="MM / YYYY" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 d-flex flex-column es-mt-6">
                                    <label for="security_code">Security Code</label>
                                    <input id="security_code" type="password" class="form-control es-input mt-2"
                                        placeholder="CVV" />
                                    <div class="es-input-error-message"></div>
                                </div>
                            </div>
                            <div class="form-check es-mt-6">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckCheckeds" />
                                <label class="form-check-label" for="flexCheckCheckeds">
                                    Acknowledge Payment Terms and Conditions
                                </label>
                            </div>
                            <div class="col-xl-4 col-6 es-mt-6 d-flex align-items-end">
                                <button type="button" class="es-btn es-w-full es-h-auto">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 610px">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" class="" />
                </button>
                <div class="card">
                    <div class="card-body p-5">
                        <img id="modalImage" src="{{ asset('images/services-image.svg') }}" alt=""
                            class="img500x200" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>


    <script>
        const photo_input_subscription = document.getElementById(
            "photo_input_subscription",
        );

        photo_input_subscription.addEventListener("change", () => {
            const file = photo_input_subscription.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById("photo-preview-subscription");

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document
                    .getElementById("photo-label-subscription")
                    .classList.add("d-none");
                document
                    .getElementById("file-preview-container-subscription")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input_subscription")
            .addEventListener("click", () => {
                document.getElementById("photo_input_subscription").value = null;
                document
                    .getElementById("photo-label-subscription")
                    .classList.remove("d-none");
                document
                    .getElementById("file-preview-container-subscription")
                    .classList.add("d-none");
            });

        const update_photo_input_subscription = document.getElementById(
            "update_photo_input_subscription",
        );

        update_photo_input_subscription.addEventListener("change", () => {
            const file = update_photo_input_subscription.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById(
                    "update-photo-preview-subscription",
                );

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document
                    .getElementById("update-photo-label-subscription")
                    .classList.add("d-none");
                document
                    .getElementById("update-file-preview-container-subscription")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_update_photo_input_subscription")
            .addEventListener("click", () => {
                document.getElementById("update_photo_input_subscription").value =
                    null;
                document
                    .getElementById("update-photo-label-subscription")
                    .classList.remove("d-none");
                document
                    .getElementById("update-file-preview-container-subscription")
                    .classList.add("d-none");

                document
                    .getElementById("update_photo_url").value = '';
            });

        const photo_input_subscription_form = document.getElementById(
            "photo_input_subscription_form",
        );

        photo_input_subscription_form.addEventListener("change", () => {
            const file = photo_input_subscription_form.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById(
                    "photo-preview-subscription-form",
                );

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document
                    .getElementById("photo-label-subscription-form")
                    .classList.add("d-none");
                document
                    .getElementById("file-preview-container-subscription-form")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input_subscription_form")
            .addEventListener("click", () => {
                document.getElementById("photo_input_subscription_form").value = null;
                document
                    .getElementById("photo-label-subscription-form")
                    .classList.remove("d-none");
                document
                    .getElementById("file-preview-container-subscription-form")
                    .classList.add("d-none");
            });

        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $(
                '<span><span class="checkbox-icon"></span>' + state.text + "</span>",
            );
            return $state;
        }

        $(".subscription_package").select2({
            theme: "bootstrap-5",
            closeOnSelect: false,
            placeholder: "Products",
            templateResult: formatState,
        });
        $(".subscription_services").select2({
            theme: "bootstrap-5",
            closeOnSelect: false,
            placeholder: "Services",
            templateResult: formatState,
        });


        $(document).on('click', '.edit-subscription', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var description = $(this).data('description');
            var promo_price = $(this).data('promo_price');
            var promo_period = $(this).data('promo_period');
            var promo_sub_title = $(this).data('promo_sub_title');
            var promo_sub_title_price = $(this).data('promo_sub_title_price');
            var photo = $(this).data('photo');
            var payment_frequency = $(this).data('payment_frequency');
            var price_of_subscription = $(this).data('price_of_subscription');
            var subscription_url = $(this).data('subscription_url');
            var subscription_form_url = $(this).data('subscription_form_url');
            var frequency_title = $(this).data('frequency_title');
            var frequency_description = $(this).data('frequency_description');
            var subscription_package = $(this).data(
                'subscription_package');
            var subscription_services = $(this).data(
                'subscription_services');

            // Update form fields
            $('#update_title').val(title);
            $('#update_description').val(description);
            $('#update_promo_price').val(promo_price);
            $('#update_promo_period').val(promo_period);
            $('#update_promo_sub_title').val(promo_sub_title);
            $('#update_promo_sub_title_price').val(promo_sub_title_price);
            $('#update_title_2').val(frequency_title);
            $('#update_description_2').val(frequency_description);
            $('#update_price_inc_gst').val(price_of_subscription);
            $('#update_frequency').val(payment_frequency).trigger('change');
            $('#update_subscription_url').val(subscription_url);
            $('#update_subscription_form').val(subscription_form_url);
            $('#external-link').attr('href', subscription_form_url);

            $('#id').val(id);

            if (photo) {
                $('#update-photo-preview-subscription').attr('src', photo); // Set the image source
                $('#update-file-preview-container-subscription').removeClass(
                    'd-none'); // Show the preview container
                $('#update-photo-label-subscription').addClass('d-none'); // Show the preview container
                $('#update_photo_url').val(photo);
            }


            var subscriptionPackagesArray = String(subscription_package).split(','); // Split the string

            $('#update_subscription_package_subscription_modal').val(subscriptionPackagesArray).trigger('change');



            var subscriptionServicesArray = String(subscription_services).split(','); // Split the string

            $('#update_subscription_service_subscription_modal').val(subscriptionServicesArray).trigger('change');

        });

        function copyToClipboard(event, button, text) {
            // Prevent the button's default behavior (e.g., submitting a form or reloading the page)
            event.preventDefault();

            // Create a temporary input element to hold the text
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);

            // Select the text and copy it to the clipboard
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");

            // Remove the temporary input element
            document.body.removeChild(tempInput);

            // Show the tooltip
            const tooltip = button.querySelector('.tooltip-text');
            tooltip.style.display = 'block';

            // Hide the tooltip after 2 seconds
            setTimeout(() => {
                tooltip.style.display = 'none';
            }, 2000);
        }

        $(document).on('click', '.view-subscription', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var description = $(this).data('description');
            var promo_price = $(this).data('promo_price');
            var promo_period = $(this).data('promo_period');
            var promo_sub_title = $(this).data('promo_sub_title');
            var promo_sub_title_price = $(this).data('promo_sub_title_price');
            var photo = $(this).data('photo');
            var payment_frequency = $(this).data('payment_frequency');
            var price_of_subscription = $(this).data('price_of_subscription');
            var subscription_url = $(this).data('subscription_url');
            var subscription_form_url = $(this).data('subscription_form_url');
            var frequency_title = $(this).data('frequency_title');
            var frequency_description = $(this).data('frequency_description');
            var subscription_package = $(this).data('subscription_package');
            var subscription_services = $(this).data('subscription_services');

            // Update form fields
            $('#view_title').val(title);
            $('#view_description').val(description);
            $('#view_promo_price').val(promo_price);
            $('#view_promo_period').val(promo_period);
            $('#view_promo_sub_title').val(promo_sub_title);
            $('#view_promo_sub_title_price').val(promo_sub_title_price);
            $('#view_frequency_title').val(frequency_title);
            $('#view_frequency_description').val(frequency_description);
            $('#view_price_inc_gst').val(price_of_subscription);
            $('#view_frequency').val(payment_frequency).trigger('change');
            $('#view_subscription_url').val(subscription_url);
            $('#view_subscription_form').val(subscription_form_url);

            $('#id').val(id);

            if (photo) {
                $('#view-photo-preview-subscription').attr('src', photo); // Set the image source
                $('#view-file-preview-container-subscription').removeClass('d-none'); // Show the preview container
                $('#view-photo-label-subscription').addClass('d-none'); // Hide the upload label
                $('#view_photo_url').val(photo);
            }

            var subscriptionPackagesArray = subscription_package.split(
                ','); // Split the comma-separated string into an array
            $('#view_subscription_package_subscription_modal').val(subscriptionPackagesArray).trigger('change');


            var subscriptionServicesArray = String(subscription_services).split(','); // Split the string

            $('#view_subscription_service_subscription_modal').val(subscriptionServicesArray).trigger('change');


            // Disable all fields for view-only mode
            $('#view_title, #view_description, #view_frequency_title, #view_frequency_description, #view_price_inc_gst, #view_frequency, #view_subscription_url, #view_subscription_form, #view_subscription_package_subscription_modal,view_subscription_service_subscription_modal')
                .prop('disabled', true);
        });

        $(document).ready(function() {
            // Initialize form validation for the subscription form
            $("#addSubscriptionModal form").validate({
                rules: {
                    photo: {
                        required: true,
                        extension: "jpg,jpeg,png"
                    },
                    title: {
                        required: true,
                        minlength: 2
                    },
                    description: {
                        required: true,
                        minlength: 10
                    },
                    'subscription_package[]': {
                        required: true,
                        minlength: 1 // At least one package must be selected
                    },
                    'subscription_services[]': {
                        required: false,
                        minlength: 0
                    },
                    frequency_title: {
                        required: true,
                        minlength: 2
                    },
                    frequency_description: {
                        required: true,
                        minlength: 10
                    },
                    price_of_subscription: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    subscription_url: {
                        required: true,
                        url: true
                    },
                    subscription_form_url: {
                        required: true,
                        url: true
                    }
                },
                messages: {
                    photo: {
                        required: "Please upload an image",
                        extension: "Only jpg, jpeg, and png formats are allowed"
                    },
                    title: {
                        required: "Please enter a title",
                        minlength: "Title must be at least 2 characters long"
                    },
                    description: {
                        required: "Please enter a description",
                        minlength: "Description must be at least 10 characters long"
                    },
                    'subscription_package[]': {
                        required: "Please select at least one subscription package"
                    },
                    frequency_title: {
                        required: "Please enter a frequency title",
                        minlength: "Frequency title must be at least 2 characters long"
                    },
                    frequency_description: {
                        required: "Please enter a frequency description",
                        minlength: "Frequency description must be at least 10 characters long"
                    },
                    price_of_subscription: {
                        required: "Please enter the price",
                        number: "Please enter a valid number",
                        min: "Price must be at least $0"
                    },
                    subscription_url: {
                        required: "Please enter a subscription URL",
                        url: "Please enter a valid URL"
                    },
                    subscription_form_url: {
                        required: "Please enter a subscription form URL",
                        url: "Please enter a valid URL"
                    }
                },
                errorElement: "div",
                errorClass: "es-input-error-message",
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    form.submit(); // Submit the form when valid
                }
            });

            // Prevent the modal from closing when the form is invalid
            // $('#subscriptionModal').on('hide.bs.modal', function(e) {
            //     var form = $("#addSubscriptionModal form");
            //     if (!form.valid()) {
            //         e.preventDefault(); // Prevent modal from closing if form is invalid
            //     }
            // });

            // // Allow closing modal when clicking the close button
            // $('.close-modal-button').on('click', function() {
            //     $('#subscriptionModal').modal('hide'); // Close the modal directly
            // });
        });

        // Check subscription package selection
        $('#subscription_package_subscription_modal').change(function() {
            // Manually validate the subscription package field
            $("#addSubscriptionModal form").validate().element(this);
        });

        // Check subscription package selection
        $('#update_subscription_package_subscription_modal').change(function() {
            // Manually validate the subscription package field
            $("#updateSubscriptionModal form").validate().element(this);
        });

        $(document).ready(function() {
            // Initialize form validation for the subscription form
            $("#updateSubscriptionModal form").validate({
                rules: {
                    photo: {
                        required: true,
                        extension: "jpg,jpeg,png"
                    },
                    title: {
                        required: true,
                        minlength: 2
                    },
                    description: {
                        required: true,
                        minlength: 10
                    },

                    'subscription_package[]': {
                        required: true,
                        minlength: 1 // At least one package must be selected
                    },
                    frequency_title: {
                        required: true,
                        minlength: 2
                    },
                    frequency_description: {
                        required: true,
                        minlength: 10
                    },
                    price_of_subscription: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    subscription_url: {
                        required: true,
                        url: true
                    },
                    subscription_form_url: {
                        required: true,
                        url: true
                    }
                },
                messages: {
                    photo: {
                        required: "Please upload an image",
                        extension: "Only jpg, jpeg, and png formats are allowed"
                    },
                    title: {
                        required: "Please enter a title",
                        minlength: "Title must be at least 2 characters long"
                    },
                    description: {
                        required: "Please enter a description",
                        minlength: "Description must be at least 10 characters long"
                    },

                    'subscription_package[]': {
                        required: "Please select at least one subscription package"
                    },
                    frequency_title: {
                        required: "Please enter a frequency title",
                        minlength: "Frequency title must be at least 2 characters long"
                    },
                    frequency_description: {
                        required: "Please enter a frequency description",
                        minlength: "Frequency description must be at least 10 characters long"
                    },
                    price_of_subscription: {
                        required: "Please enter the price",
                        number: "Please enter a valid number",
                        min: "Price must be at least $0"
                    },
                    subscription_url: {
                        required: "Please enter a subscription URL",
                        url: "Please enter a valid URL"
                    },
                    subscription_form_url: {
                        required: "Please enter a subscription form URL",
                        url: "Please enter a valid URL"
                    }
                },
                errorElement: "div",
                errorClass: "es-input-error-message",
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    form.submit(); // Submit the form when valid
                }
            });

            // Prevent the modal from closing when the form is invalid
            // $('#subscriptionModal').on('hide.bs.modal', function(e) {
            //     var form = $("#addSubscriptionModal form");
            //     if (!form.valid()) {
            //         e.preventDefault(); // Prevent modal from closing if form is invalid
            //     }
            // });

            // // Allow closing modal when clicking the close button
            // $('.close-modal-button').on('click', function() {
            //     $('#subscriptionModal').modal('hide'); // Close the modal directly
            // });
        });

        $(document).ready(function() {

            $('#photoModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var imageUrl = button.data('image');
                $('#modalImage').attr('src', imageUrl);
            });

            var isAscending = true;
            var $table = $('#subscriptionTable');
            var $tbody = $table.find('tbody');
            var $originalRows = $tbody.find('tr').clone(); // Clone rows to restore original order

            // $('#sortAsc').click(function() {
            //     if (!isAscending) {
            //         // $tbody.html($originalRows); // Restore original order
            //         isAscending = true;
            //     }
            // });

            $('#sortAsc').click(function() {
                if (!isAscending) {
                    sortTable(true);
                    isAscending = true;
                }
            });

            $('#sortDesc').click(function() {
                if (isAscending) {
                    sortTable(false);
                    isAscending = false;
                }
            });

            function sortTable(ascending) {
                var $rows = $tbody.find('tr').get();

                if (ascending) {
                    $rows.sort(function(a, b) {
                        var keyA = $(a).find('td').eq(0).text();
                        var keyB = $(b).find('td').eq(0).text();
                        return keyA.localeCompare(keyB);
                    });
                } else {
                    $rows.reverse();
                }

                $tbody.html('');
                $.each($rows, function(index, row) {
                    $tbody.append(row);
                });
            }
        });

        $(document).ready(function() {
            let rowsPerPage = 10; // Rows to display per page
            let currentPage = 1;
            let totalRows = $("#subscriptionTable tbody tr").length; // Count total rows
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            function renderTable(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                // Hide all rows initially
                $("#subscriptionTable tbody tr").hide();

                // Show rows for the current page
                $("#subscriptionTable tbody tr").slice(start, end).show();
            }

            function renderPagination() {
                $('#pagination').empty();

                // Add Previous button (disabled if on the first page)
                if (currentPage === 1) {
                    $('#pagination').append(
                        `<span class="page prev disabled"><i class="fa-solid fa-chevron-left"></i></span> `);
                } else {
                    $('#pagination').append(
                        `<span class="page prev" data-page="${currentPage - 1}"><i class="fa-solid fa-chevron-left"></i></span> `
                    );
                }

                // Add page numbers
                for (let i = 1; i <= totalPages; i++) {
                    let activeClass = (i === currentPage) ? 'active' : '';
                    $('#pagination').append(`<span class="page ${activeClass}" data-page="${i}">${i}</span> `);
                }

                // Add Next button (disabled if on the last page)
                if (currentPage === totalPages) {
                    $('#pagination').append(
                        `<span class="page next disabled"><i class="fa-solid fa-chevron-right"></i></span> `);
                } else {
                    $('#pagination').append(
                        `<span class="page next" data-page="${currentPage + 1}"><i class="fa-solid fa-chevron-right"></i></span> `
                    );
                }
            }

            // Initial table and pagination render
            renderTable(currentPage);
            renderPagination();

            // Handle page click
            $(document).on('click', '.page:not(.disabled)', function() {
                currentPage = $(this).data('page');
                renderTable(currentPage);
                renderPagination();
            });
        });


        $('.preview-subs-modal').click(function() {
            // Find the closest parent form of the clicked button
            var title = $(this).closest('form').find('#title').val();
            var description = $(this).closest('form').find('#description').val();
            var src = $(this).closest('form').find('#photo-preview-subscription').attr('src');

            var subscriptionPackageSelect = $(this).closest('form').find(
                '#subscription_package_subscription_modal');
            var selectedOptions = subscriptionPackageSelect.find('option:selected');
            var selectedTexts = [];
            selectedOptions.each(function() {
                selectedTexts.push($(this).text());
            });
            var selectedPackageText = selectedTexts.join(', ');

            // Populate the fields in the second form
            $('#title_3').val(title); // Set title in the second form
            $('#description_3').val(description); // Set description in the second form
            if (src != '') {
                $('#photo-preview-subscription-form').attr('src', src); // Set photo preview in the second form
                $('#file-preview-container-subscription-form').removeClass('d-none');
                $('#photo-label-subscription-form').addClass('d-none');
            } else {
                $('#file-preview-container-subscription-form').addClass('d-none');
                $('#photo-label-subscription-form').removeClass('d-none');
            }
            $('#subscription_package').val(selectedPackageText);


        });
    </script>
@endsection
