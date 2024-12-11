@extends('layouts.app')
@section('content')
    <div class="col-lg-9 es-py-8">
        <div class="mb-5">
            <div class="es-header-4 es-font-mulish-bold">Profile</div>
        </div>
        @if (session('success'))
            <div class="d-flex mb-4 align-items-center justify-content-center">
                <span class="es-text-green-500">
                    {{ session('success') }}
                </span>
            </div>
        @endif
        <form method="POST" action="{{ url('/admin/profile/update') }}" enctype="multipart/form-data">
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
                            <input type="file" accept=".jpg,.jpeg,.png" name="photo_input" hidden id="photo_input" />
                            <input type="hidden" name="photo" hidden id="photo" value="{{$photo ?? ''}}" />
                            <label for="photo_input" class="es-file-input {{$photo ? 'd-none': ''}}" id="photo-label">
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
                            <div class="{{$photo ? '' : 'd-none'}} mt-2" id="file-preview-container">
                                <img src="{{$photo}}" alt="Preview Uploaded Image" id="photo-preview" 
                                    class="es-h-80 es-mb-3 file-preview img500x500" />
                                <div class="d-flex es-gap-8">
                                    <label for="photo_input" class="btn border-0 es-text-sm es-font-600 p-0">
                                        Change
                                        <img src="{{ asset('images/refresh.png') }}" width="14" height="14"
                                            alt="" />
                                    </label>
                                    <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                        id="clear_photo_input">
                                        Delete
                                        <img src="{{ asset('images/trash.png') }}" width="14" height="14"
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
                                placeholder="First Name" value={{ $firstName }} />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="last_name"> Last Name </label>
                            <input id="last_name" name="last_name" type="text" class="form-control es-input mt-2"
                                placeholder="Last Name" value={{ $lastName }} />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="address"> Address </label>
                            <input id="address" type="text" name="address" class="form-control es-input mt-2"
                                placeholder="Address" value='{{ $address ?? '' }}' />
                            <div class="es-input-error-message"></div>
                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="email_address"> Email Address </label>
                            <input id="email_address" type="email" name="email_address" class="form-control es-input mt-2"
                                placeholder="Email Address" value={{ $email }} />
                            <div class="es-input-error-message">
                                @if ($errors->has('email_address'))
                                    {{ $errors->first('email_address') }}
                                @endif
                            </div>

                        </div>
                        <div class="col-xl-4 es-mt-6">
                            <label for="phone_number"> Phone Number </label>
                            <input id="phone_number" type="text" name="phone_number"
                                class="form-control es-input mt-2" placeholder="Phone Number"
                                value={{ $phoneNo }} />
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

        <div class="card border-0 es-mb-6">
            <div class="card-body d-flex align-items-center es-font-mulish es-px-6 es-gap-3">
                <div class="es-font-mulish-bold es-header-6">Role:</div>
                <label class="es-header-6" for="">
                    {{ match($role) {
                        1 => 'Admin',
                        3 => 'Contractor',
                        4 => 'Staff',
                        default => 'Undefined'
                    } }}
                </label>
            </div>
        </div>
        <form method="POST" action="{{ url('admin/change-password') }}">
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
    </div>
    </div>

    <!-- Pause Membership Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="pauseMembershipModal" tabindex="-1" role="dialog"
        aria-labelledby="pauseMembershipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body">
                        <form action="">
                            <div class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold">
                                Pause Membership
                            </div>
                            <div class="">
                                <div class="d-flex flex-column col es-mt-6">
                                    <label for="reason_for_pausing">Reason for Pausing</label>
                                    <input id="reason_for_pausing" type="text" class="form-control es-input mt-2"
                                        placeholder="Reason for Pausing" id="reason" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="d-flex flex-column col es-mt-6">
                                    <label for="days">How Long</label>
                                    <input type="text" class="form-control es-input mt-2" placeholder="0 Days"
                                        id="days" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col es-mt-6">
                                    <button type="button" id="pauseModal" class="es-btn es-h-auto">
                                        Pause Membership
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        flatpickr(".flatpickr", {
            // Human-friendly format
            altInput: true,
            altFormat: "M j, Y",
            dateFormat: "Y-m-d",

            minDate: "today",
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
            document.getElementById("existing-image-container")?.classList.add("d-none"); // Hide existing image if any
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
    </script>
@endsection
