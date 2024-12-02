<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <title>{{$title ?? 'Transactions'}}</title>

    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="{{ url('public/logo.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS v5.2.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ url('public/css/style.css') }}" />

    <!-- Required JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/a2d48955b0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.js"
        integrity="sha512-1cMYNLuYP3nNQUA42Gj7XvcJN5lAukNNw3fE1HtK3Fs1DA5JPrNQHv5g/FM+1yL5cT6x3sf2o1mKmTpVO0iGcA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Your content goes here -->

</head>

<body class="es-bg-gray-50 es-text-gray-900 es-text-normal">
    <div class="es-navbar">
        <nav class="navbar navbar-expand-lg es-h-20 bg-white es-drop-shadow">
            <div class="container-xxl">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <svg style="width: 30px; height: 30px" viewBox="0 0 24 24">
                        <path fill="#707071" d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" />
                    </svg>
                </button>
                <!-- Desktop View -->
                <div class="d-none d-lg-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <a href="#" class="">
                            <img src="{{ url('public/images/logo.svg') }}" alt="" class="es-h-8" />
                        </a>
                    </div>
                    @if(Auth::guard('admin')->check())
                    <a href="{{ url('admin/profile') }}" class="btn border-0 d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <div
                                class="d-flex align-items-center justify-content-center es-w-10 es-h-10 es-rounded-full es-bg-golden es-border-0">
                                @if(Auth::guard('admin')->user()->photo)
                                <img src="{{Auth::guard('admin')->user()->photo}}" class="w-100 h-100 es-rounded-full">
                                @else
                                <span class="es-font-mulish text-white">{{ substr(Auth::guard('admin')->user()->f_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="es-font-mulish-bold">{{Auth::guard('admin')->user()->f_name .' '.Auth::guard('admin')->user()->l_name}}</div>
                        </div>
                    </a>
                    @else
                    <a href="{{ url('profile') }}" class="btn border-0 d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <div
                                class="d-flex align-items-center justify-content-center es-w-10 es-h-10 es-rounded-full es-bg-golden es-border-0">
                                @if(Auth::user()->photo)
                                <img src="{{Auth::user()->photo}}" class="w-100 h-100 es-rounded-full">
                                @else
                                <span class="es-font-mulish text-white">{{ substr(Auth::user()->f_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="es-font-mulish-bold">{{Auth::user()->f_name .' '.Auth::user()->l_name}}</div>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Mobile View    -->
        @if(Auth::guard('admin')->check())
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                    <div class="es-px-4 d-flex align-items-center align-self-center my-3">
                        <a href="#" class="">
                            <img src="{{ url('public/images/logo.svg') }}" alt="" class="es-h-8" />
                        </a>
                    </div>
                </h5>
                <button type="button" class="btn-close-canvas" data-bs-dismiss="offcanvas" aria-label="Close">
                    <svg style="width: 30px; height: 30px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                    </svg>
                </button>
            </div>
            <div class="offcanvas-body d-flex flex-column bg-white gap-2">
                <div class="es-px-7 pb-2 d-flex align-items-center gap-2">
                    <a href="{{url('admin/profile')}}" class="btn border-0 d-flex align-items-center gap-3 p-0 w-100">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center es-w-10 es-h-10 es-rounded-full es-bg-golden es-border-0">
                                @if(Auth::guard('admin')->user()->photo)
                                <img src="{{Auth::guard('admin')->user()->photo}}" class="w-100 h-100 es-rounded-full">
                                @else
                                <span class="es-font-mulish text-white">{{ substr(Auth::guard('admin')->user()->f_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="es-font-mulish-bold">{{Auth::guard('admin')->user()->f_name .' '. Auth::guard('admin')->user()->l_name}}</div>
                        </div>
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{url('admin/members')}}" class="es-menu-btn d-flex align-items-center @if (request()->is('admin/members*')) active @endif">
                        <img src="{{ request()->is('admin/members*')
                        ? url('public/images/members-white.png')
                        : url('public/images/members-dark.png') }}" alt="" class="es-mr-3" />
                        Members
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{url('admin/transactions')}}" class="es-menu-btn d-flex align-items-center @if (request()->is('admin/transactions*')) active @endif">
                        <img src="{{ request()->is('admin/transactions*')
                        ? url('public/images/transactions-icon-white.png')
                        : url('public/images/transactions-icon-dark.png')  }}" alt="" class="es-mr-3" />
                        Transactions
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{ url('admin/subscriptions')}}" class="es-menu-btn d-flex align-items-center
                    @if (request()->is('admin/subscriptions*')) active @endif ">
                        <img src="{{ request()->is('admin/subscriptions*')
                        ? url('public/images/shopping-cart-white.png')
                        : url('public/images/shopping-cart-dark.png') }}" alt="" class="es-mr-3" />
                        Subscriptions
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{ url('admin/products')}}" class="es-menu-btn d-flex align-items-center
                    @if (request()->is('admin/products*')) active @endif ">
                        <img src="{{ request()->is('admin/products*')
                        ? url('public/images/products-white.png')
                        : url('public/images/products-dark.png') }}" alt="" class="es-mr-3" />
                        Products
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{ url('admin/services')}}" class="es-menu-btn d-flex align-items-center
                    @if (request()->is('admin/services*')) active @endif ">
                        <img src="{{ request()->is('admin/services*') ?
                        url('public/images/edit-white.png') :
                        url('public/images/edit-dark.png')}}" alt="" class="es-mr-3" />
                        Services
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{ url('admin/profile')}}" class="es-menu-btn d-flex align-items-center
                    @if (request()->is('admin/profile*')) active @endif ">
                        <img src="{{ request()->is('admin/profile*')
                        ? url('public/images/profile-icon-white.png')
                        : url('public/images/profile-icon-dark.png') }}" alt="" class="es-mr-3" />
                        Profile
                    </a>
                </div>
                <div class="es-px-4 mb-2">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#logoutModal"
                        class="es-w-full es-menu-btn d-flex align-items-center">
                        <img src="{{ url('public/images/arrow-right-start-on-rectangle-icon.png') }}" alt=""
                            class="es-mr-3" />
                        Logout
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                    <div class="es-px-4 d-flex align-items-center align-self-center my-3">
                        <a href="#" class="">
                            <img src="{{ url('public/images/logo.svg') }}" alt="" class="es-h-8" />
                        </a>
                    </div>
                </h5>
                <button type="button" class="btn-close-canvas" data-bs-dismiss="offcanvas" aria-label="Close">
                    <svg style="width: 30px; height: 30px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                    </svg>
                </button>
            </div>
            <div class="offcanvas-body d-flex flex-column bg-white gap-2">
                <div class="es-px-7 pb-2 d-flex align-items-center gap-2">
                    <a href="{{url('profile')}}" class="btn border-0 d-flex align-items-center gap-3 p-0 w-100">
                        <div class="d-flex align-items-center gap-2">
                            <div
                                class="d-flex align-items-center justify-content-center es-w-10 es-h-10 es-rounded-full es-bg-golden es-border-0">
                                @if(Auth::user()->photo)
                                <img src="{{Auth::user()->photo}}" class="w-100 h-100 es-rounded-full">
                                @else
                                <span class="es-font-mulish text-white">{{ substr(Auth::user()->f_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="es-font-mulish-bold">{{Auth::user()->f_name .' '. Auth::user()->l_name}}</div>
                        </div>
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{url('transactions')}}" class="es-menu-btn d-flex align-items-center
                     @if (request()->is('transactions*')) active @endif ">
                        <img src="{{ request()->is('transactions')
                        ? url('public/images/transactions-icon-white.png')
                        : url('public/images/transactions-icon-dark.png') }}" alt="" class="es-mr-3" />
                        Transactions
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{ url('services')}}" class="es-menu-btn d-flex align-items-center
                    @if (request()->is('services*')) active @endif
                     @if (Auth::check() && Auth::user()->status === 3) disabled @endif">
                        <img src="{{ request()->is('services')
                        ? url('public/images/services-icon-white.png')
                        : url('public/images/services-icon-dark.png') }}" alt="" class="es-mr-3" />
                        Services
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{ url('bookings')}}" class="es-menu-btn d-flex align-items-center
                    @if (request()->is('bookings*')) active @endif
                    @if (Auth::check() && Auth::user()->status === 3) disabled @endif ">
                        <img src="{{ request()->is('bookings')
                        ? url('public/images/upcoming-bookings-icon-white.png')
                        : url('public/images/upcoming-bookings-icon-dark.png') }}" alt="" class="es-mr-3" />
                        Upcoming Bookings
                    </a>
                </div>
                <div class="es-px-4">
                    <a href="{{ url('profile')}}" class="es-menu-btn d-flex align-items-center
                    @if (request()->is('profile*')) active @endif ">
                        <img src="{{ request()->is('profile')
                        ? url('public/images/profile-icon-white.png')
                        : url('public/images/profile-icon-dark.png') }}" alt="" class="es-mr-3" />
                        Profile
                    </a>
                </div>
                <div class="es-px-4 mb-2">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#logoutModal"
                        class="es-w-full es-menu-btn d-flex align-items-center">
                        <img src="{{ url('public/images/arrow-right-start-on-rectangle-icon.png') }}" alt=""
                            class="es-mr-3" />
                        Logout
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="container-xxl">
        <div class="row">
            <div class="d-none d-xl-flex col-3">
                <div class="es-w-72">
                    <div
                        class="bg-white es-pt-8 es-pb-4 d-flex flex-column justify-content-between w-100 es-sidebar gap-5">
                        <div class="d-grid gap-2">

                            {{-- sidebar for admin --}}
                            @if (Auth::guard('admin')->check())
                                <div class="es-px-4">
                                    <a href="{{ url('admin/members') }}"
                                        class="es-menu-btn d-flex align-items-center
                                         @if (request()->is('admin/members*')) active @endif">
                                        <img src="{{ request()->is('admin/members*')
                                            ? url('public/images/members-white.png')
                                            : url('public/images/members-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Members
                                    </a>
                                </div>

                                <div class="es-px-4">
                                    <a href="{{ url('admin/transactions') }}"
                                        class="es-menu-btn d-flex align-items-center
                                         @if (request()->is('admin/transactions*')) active @endif">
                                        <img src="{{ request()->is('admin/transactions*')
                                            ? url('public/images/transactions-icon-white.png')
                                            : url('public/images/transactions-icon-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Transactions
                                    </a>
                                </div>

                                <div class="es-px-4">
                                    <a href="{{ url('admin/subscriptions') }}"
                                        class="es-menu-btn d-flex align-items-center
                                         @if (request()->is('admin/subscriptions*')) active @endif">
                                        <img src="{{ request()->is('admin/subscriptions*')
                                            ? url('public/images/shopping-cart-white.png')
                                            : url('public/images/shopping-cart-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Subscriptions
                                    </a>
                                </div>

                                <div class="es-px-4">
                                    <a href="{{ url('admin/products') }}"
                                        class="es-menu-btn d-flex align-items-center
                                         @if (request()->is('admin/products*')) active @endif">
                                        <img src="{{ request()->is('admin/products*')
                                            ? url('public/images/products-white.png')
                                            : url('public/images/products-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Products
                                    </a>
                                </div>

                                <div class="es-px-4">
                                    <a href="{{ url('admin/services') }}"
                                        class="es-menu-btn d-flex align-items-center
                                         @if (request()->is('admin/services*')) active @endif">
                                        <img src="{{ request()->is('admin/services*') ? url('public/images/edit-white.png') : url('public/images/edit-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Services
                                    </a>
                                </div>

                                <div class="es-px-4">
                                    <a href="{{ url('admin/profile') }}"
                                        class="es-menu-btn d-flex align-items-center
                                     @if (request()->is('admin/profile*')) active @endif">
                                        <img src="{{ request()->is('admin/profile*')
                                            ? url('public/images/profile-icon-white.png')
                                            : url('public/images/profile-icon-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Profile
                                    </a>
                                </div>
                            @endif


                            {{-- sidebar for user --}}
                            @if (!Auth::guard('admin')->check())
                                <div class="es-px-4">
                                    <a href="{{ url('transactions') }}"
                                        class="es-menu-btn  d-flex align-items-center
                                         @if (request()->is('transactions')) active @endif">
                                        <img src="{{ request()->is('transactions')
                                            ? url('public/images/transactions-icon-white.png')
                                            : url('public/images/transactions-icon-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Transactions
                                    </a>
                                </div>

                                <div class="es-px-4">
                                    <a href="{{ url('services') }}"
                                        class="es-menu-btn d-flex align-items-center
                                               @if (request()->is('services')) active @endif
                                               @if (Auth::check() && Auth::user()->status === 3) disabled @endif">
                                        <img src="{{ request()->is('services')
                                            ? url('public/images/services-icon-white.png')
                                            : url('public/images/services-icon-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Services
                                    </a>
                                </div>


                                <div class="es-px-4">
                                    <a href="{{ url('bookings') }}"
                                        class="es-menu-btn d-flex align-items-center
                                         @if (request()->is('bookings')) active @endif
                                         @if (Auth::check() && Auth::user()->status === 3) disabled @endif">
                                        <img src="{{ request()->is('bookings')
                                            ? url('public/images/upcoming-bookings-icon-white.png')
                                            : url('public/images/upcoming-bookings-icon-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Upcoming Bookings
                                    </a>
                                </div>

                                <div class="es-px-4">
                                    <a href="{{ url('profile') }}"
                                        class="es-menu-btn d-flex align-items-center
                                            @if (request()->is('profile')) active @endif ">
                                        <img src="{{ request()->is('profile')
                                            ? url('public/images/profile-icon-white.png')
                                            : url('public/images/profile-icon-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Profile
                                    </a>
                                </div>
                            @endif

                        </div>
                        <div class="d-grid gap-2">
                            <div class="es-px-4">
                                <div class="d-flex es-bg-brown-100 es-px-5 es-py-3 es-rounded">
                                    <div>
                                        <img src="{{ url('public/images/info.png') }}" alt=""
                                            class="es-mr-3" />
                                    </div>
                                    <div class="es-font-mulish-medium">
                                        <div>Have any questions?</div>
                                        <a href="#" class="es-font-mulish es-text-gray-900">
                                            <div>{{$setting->business_phone_number}}</div>
                                            <div>{{$setting->business_email_address}}</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="bg-gray-700 mb-2" />

                            @if (Auth::guard('admin')->check())
                                <div class="es-px-4">
                                    <a href="{{ url('admin/settings') }}"
                                        class="es-menu-btn d-flex align-items-center
                                        @if (request()->is('admin/settings')) active @endif ">
                                        <img src="{{ request()->is('admin/settings')
                                            ? url('public/images/settings-white.png')
                                            : url('public/images/settings-dark.png') }}"
                                            alt="" class="es-mr-3" />
                                        Settings
                                    </a>
                                </div>
                            @endif

                            <div class="es-px-4 mb-2">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#logoutModal"
                                    class="es-w-full es-menu-btn d-flex align-items-center">
                                    <img src="{{ url('public/images/arrow-right-start-on-rectangle-icon.png') }}"
                                        alt="" class="es-mr-3" />
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
