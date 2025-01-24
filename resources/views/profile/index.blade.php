@extends('layouts.app')
@section('content')
    <style>
        .disabled-link {
            pointer-events: none;
            cursor: not-allowed;
            opacity: 0.5;
            /* Optional: visually indicate it's disabled */
            user-select: none;
        }
    </style>
    <div class="col-lg-9 es-py-8">
        <div class="mb-5">
            <div class="es-header-4 es-font-mulish-bold">Profile</div>
        </div>

        <form method="POST" action="{{ url('/profile/update') }}" enctype="multipart/form-data">
            @csrf
            <div class="card border-0 es-mb-6">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8">
                    <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-header-6">
                        Personal Information
                    </div>
                    <div>
                        <div>Photo :</div>
                        <div class="es-text-gray-500 es-mt-2 es-mb-4">
                            Resolution requirements: 500x500px
                        </div>
                        <div class="mt-2">
                            <input type="file" name="photo_input" accept="images/*" hidden id="photo_input" />
                            <input type="hidden" name="photo" hidden id="photo" value="{{ $user->photo ?? '' }}" />
                            <label for="photo_input" class="es-file-input {{ $user->photo ? 'd-none' : '' }}"
                                id="photo-label">
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
                            <div class="{{ $user->photo ? '' : 'd-none' }}" id="file-preview-container">
                                <img src="{{ url('images/' . $user->photo) }}" alt="Preview Uploaded Image"
                                    id="photo-preview" class="es-h-80 es-mb-3 file-preview img-fluid img500x500" />
                                <div class="d-flex es-gap-8">
                                    <label for="photo_input" class="btn border-0 es-text-sm es-font-600 p-0">
                                        Change
                                        <img src="{{ url('public/images/refresh.png') }}" width="14" height="14"
                                            alt="" />
                                    </label>
                                    <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                        id="clear_photo_input">
                                        Delete
                                        <img src="{{ url('public/images/trash.png') }}" width="14" height="14"
                                            alt="" />
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 es-mt-6">
                            <label for="first_name"> First Name </label>
                            <input id="first_name" name="first_name" type="text" class="form-control es-input mt-2"
                                placeholder="First Name" value="{{ $user->f_name ?? '' }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="last_name"> Last Name </label>
                            <input id="last_name" name="last_name" type="text" class="form-control es-input mt-2"
                                placeholder="Last Name" value="{{ $user->l_name ?? '' }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="address"> Address </label>
                            <input id="address" name="address" type="text" class="form-control es-input mt-2"
                                placeholder="Address" value="{{ $user->address ?? '' }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="email_address"> Email Address </label>
                            <input id="email_address" name="email_address" type="email" class="form-control es-input mt-2"
                                placeholder="Email Address" value="{{ $user->email ?? '' }}" />
                            <div class="es-input-error-message">
                                @if ($errors->has('email_address'))
                                    {{ $errors->first('email_address') }}
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="phone_number"> Phone Number </label>
                            <input id="phone_number" name="phone_number" type="text"
                                class="form-control es-input mt-2" placeholder="Phone Number"
                                value="{{ $user->phone_no ?? '' }}" />
                            <div class="es-input-error-message">
                                @if ($errors->has('phone_number'))
                                    {{ $errors->first('phone_number') }}
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-4 es-mt-6 d-flex align-items-end">
                            <div class="d-none d-md-block" style="padding-top: 29.33px"></div>
                            <button type="submit" class="es-btn es-w-full es-h-13">
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form action="#" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card border-0 es-mb-6">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8">
                    <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-header-6">
                        Membership Information
                    </div>
                    @if (session()->has('error'))
                        <div class="es-input-error-message">{{ session('error') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-xl-4 d-flex flex-column es-mt-6">
                            Join Date
                            <input id="my_join_date" type="text" class="es-input mt-2" placeholder="My Join Date"
                                disabled
                                value="{{ $subscription ? \Carbon\Carbon::parse($subscription->subscription_start)->format('M j, Y') : '' }}" />

                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 d-flex flex-column es-mt-6">
                            <label for="next_renewal_date">Next Renewal Date</label>
                            <input id="next_renewal_date" type="text" class="es-input mt-2"
                                placeholder="Next Renewal Date" disabled
                                value="{{ $subscription ? \Carbon\Carbon::parse($subscription->subscription_end)->format('F j, Y') : '' }}" />
                            <div class="es-input-error-message"></div>
                        </div>

                        <div class="col-xl-4 d-flex flex-column es-mt-6">
                            Last Pause Date
                            <input id="last_pause_date" type="text" class="es-input mt-2"
                                placeholder="Last Pause Date" disabled
                                value="{{ $subscription && $subscription->pause_date ? \Carbon\Carbon::parse($subscription->pause_date)->format('M j, Y') : '' }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        @if ($user->status != 2)
                            <div class="col-xl-4 es-mt-6 d-flex align-items-start">
                                @if ($user->status == 1)
                                    <button type="button" class="es-btn-outline es-w-full es-h-auto"
                                        data-bs-toggle="modal" data-bs-target="#pauseMembershipModal">
                                        Pause Membership
                                    </button>
                                @elseif($user->status == 3)
                                    <a style="" href="{{ url('subscription/resume/' . $subscription->id) }}"
                                        class="es-btn-outline es-w-full text-decoration-none es-h-auto">
                                        Resume Membership
                                    </a>
                                @endif
                            </div>
                        @endif
                        <div class="col-xl-4 es-mt-6 d-flex align-items-end d-flex flex-column es-text-justify">
                            @if ($user->status == 1 || $user->status == 3)
                                <a href="{{ $isCancel ? url('subscription/cancel/' . $user->id) : 'javascript:void(0);' }}"
                                    class="es-btn es-w-full es-h-auto mb-2 {{ $isCancel ? '' : 'disabled-link' }}"
                                    @if ($isCancel) disabled @endif>
                                    Cancel Membership
                                </a>

                                <p>You can cancel your membership after 48 hours of joining.</p>
                                <!-- , but will only be refunded half of the subscription fee -->
                            @elseif($user->status == 2)
                                <button type="button" class="es-btn es-w-full es-h-auto mb-2">Cancelled</button>
                            @endif
                        </div>
                        <div class="d-none d-xl-flex flex-column col-4 es-mt-6"></div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" value="{{ $user->id }}">

        </form>

        <form action="{{ url('bank-detail/store') }}" method="POST" id="payment-form">
            @csrf
            <div class="card border-0 es-mb-6">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8">
                    <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-header-6">
                        Payment Information
                    </div>
                    <div class="row">
                        <div class="col-xl-4 es-mt-6">
                            <label for="name"> Name </label>
                            <input id="name" name="cardholder_name" type="text"
                                class="form-control es-input mt-2" placeholder="Name"
                                value="{{ $user->cardholder_name ?? '' }}" />
                            <input type="hidden" name="profile_id" value="{{ $user ? $user->profile_id : '' }}">
                            <input type="hidden" name="card_id" value="{{ $user ? $user->card_id : '' }}">
                            <input type="hidden" name="user_id" value="{{ $user ? $user->id : '' }}">
                            <input type="hidden" name="email" value="{{ $user ? $user->email : '' }}">
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="card_number"> Card Number </label>
                            <input id="card_number" name="card_no" type="password" class="form-control es-input mt-2"
                                placeholder="Card Number" value="{{ $user->card_no ?? '' }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            Expiration Date
                            <input id="expiration_date" name="expiration" type="text" class="es-input mt-2 flatpickr"
                                placeholder="Expiration Date" value="{{ $user->expiration ?? '' }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="security_code"> Security Code </label>
                            <input id="security_code" name="security" type="password" class="form-control es-input mt-2"
                                placeholder="Security Code" value="{{ $user->security ?? '' }}" />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-8 es-mt-6">
                            <div id="card-element" class="form-control d-none es-input mt-2" style="height:60%"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6 d-flex align-items-end">
                            @if ($user->card_id)
                                <input type="hidden" name="bank_id" value="{{ $user->bank_id }}">
                                <button type="submit" class="es-btn-outline es-w-full es-h-auto" id="submit-button">
                                    Update
                                </button>
                            @else
                                <button type="submit" class="es-btn es-w-full es-h-auto" id="submit-button">
                                    Save
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </form>

        <form method="POST" action="{{ url('change-password') }}">
            @csrf
            <div class="card border-0">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8">
                    <div class="es-pb-6 es-pt-2 es-font-mulish-bold es-header-6">
                        Change Password
                    </div>
                    <div class="row">
                        <div class="col-xl-4 es-mt-6">
                            <label for="old_password">Old Password</label>
                            <input id="old_password" name="old_password" type="password"
                                class="form-control es-input mt-2" placeholder="Old Password" />
                            @error('old_password')
                                <!-- Show error for old_password -->
                                <div class="es-input-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="new_password">New Password</label>
                            <input id="new_password" name="new_password" type="password"
                                class="form-control es-input mt-2" placeholder="New Password" />
                            @error('new_password')
                                <!-- Show error for new_password -->
                                <div class="es-input-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xl-4 es-mt-6 d-flex align-items-end">
                            <button type="submit" class="es-btn es-w-full es-h-auto">
                                Save
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Pause Membership Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="pauseMembershipModal" tabindex="-1" role="dialog"
        aria-labelledby="pauseMembershipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('public/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('subscription/pause') }}" id="membership-form" method="post">
                            @csrf
                            <div class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold">
                                Pause Membership
                            </div>
                            <div class="">
                                <div class="d-flex flex-column col es-mt-6">
                                    <label for="reason_for_pausing">Reason for Pausing</label>
                                    <input id="reason_for_pausing" name="reason_for_pausing" type="text"
                                        class="form-control es-input mt-2" placeholder="Reason for Pausing"
                                        id="reason" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="d-flex flex-column col es-mt-6">
                                    <label for="days">How Long</label>
                                    <input type="number" class="form-control es-input mt-2" placeholder="0 Days"
                                        id="days" name="pause_days" />
                                    <input type="hidden" name="status" value="3">
                                    <input type="hidden" name="pause_date" value="{{ date('Y-m-d') }}">
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col es-mt-6">
                                    <button type="submit" id="pauseModal" class="es-btn es-h-auto">
                                        Pause Membership
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <input type="hidden" name="subscription_id"
                                value="{{ $subscription ? $subscription->id : '' }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://web.squarecdn.com/v1/square.js"></script>
    <script>
        flatpickr(".flatpickr", {
            dateFormat: "m/Y",
            altFormat: "F Y",
            theme: "material_blue"
        });


        document.addEventListener('DOMContentLoaded', async () => {
            const cardContainer = document.getElementById('card-element');
            const paymentStatus = document.getElementById('card-status');
            const paymentForm = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');

            try {
                cardContainer.classList.remove('d-none');
                const payments = Square.payments("{{ get_setting('square_application_id') }}");
                const card = await payments.card();

                await card.attach(cardContainer);


                paymentForm.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    submitButton.disabled = true;
                    if (!$("#payment-form").valid()) return; // Validate the form

                    const result = await card.tokenize();
                    if (result.status === 'OK') {
                        handleSuccessfulPayment(result.token);
                    } else {
                        paymentStatus.textContent = result.errors[0].message;
                        paymentStatus.classList.add('visible', 'text-danger');
                    }
                });

                function handleSuccessfulPayment(token) {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'nonce'; // Assuming Square is being used
                    tokenInput.value = token;

                    paymentForm.appendChild(tokenInput);
                    paymentForm.submit();
                }
            } catch (error) {
                console.error('Error initializing Square payment:', error);
                paymentStatus.textContent = 'Failed to load payment form. Please try again.';
                submitButton.disabled = false;
                paymentStatus.classList.add('visible', 'text-danger');
            }
        });




        const photo_input = document.getElementById('photo_input');
        //    document.getElementById("photo-label").classList.add("d-none");
        photo_input.addEventListener("change", () => {
            const file = photo_input.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById("photo-preview");

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document.getElementById("photo-label").classList.add("d-none");
                document.getElementById("file-preview-container").classList.remove("d-none");
                document.getElementById("existing-image-container")?.classList.add(
                    "d-none"); // Hide existing image if any
            }
        });

        document.getElementById("clear_photo_input").addEventListener("click", () => {
            photo_input.value = null;
            document.getElementById("photo").value = null;
            document.getElementById("photo-label").classList.remove("d-none");
            document.getElementById("file-preview-container").classList.add("d-none");
            document.getElementById("file-preview-container").classList.add("d-none");
            // document.getElementById("existing-image-container")?.classList.remove("d-none"); // Show existing image if it exists
        });


        $(document).ready(function() {
            $("#membership-form").validate({
                rules: {
                    reason_for_pausing: {
                        required: true,
                        minlength: 5
                    },
                    pause_days: {
                        required: true,
                        digits: true,
                        min: 1
                    }
                },
                messages: {
                    reason_for_pausing: {
                        required: "Please provide a reason for pausing.",
                        minlength: "Reason must be at least 5 characters long."
                    },
                    pause_days: {
                        required: "Please specify the number of days.",
                        digits: "Please enter a valid number.",
                        min: "Please enter a number greater than 0."
                    }
                },
                errorClass: "es-input-error-message",
                validClass: "valid",
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
