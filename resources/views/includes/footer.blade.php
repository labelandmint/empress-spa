</div>
</div>

<!-- Logout Modal -->
<div
    class="modal fade"
    data-bs-backdrop="static"
    id="logoutModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="logoutModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="card border-0">
                <div class="card-body">
                    <div
                        class="d-flex es-py-8 flex-column align-items-center justify-content-center es-gap-6"
                    >
                        <img src="{{url('public/images/logout-icon.png')}}" alt="" class="" />
                        <div class="es-text-3xl es-font-mulish-bold">Logout</div>
                        <div class="es-text-gray-500 text-center col-8">
                            Are you sure you would like to log out of your account?
                        </div>
                        <div class="d-flex gap-3">
                            <button
                                type="button"
                                data-bs-dismiss="modal"
                                class="es-btn-outline es-w-md-auto"
                            >
                                Cancel
                            </button>
                            @if(Auth::guard('admin')->check())
                            <a href="{{url('admin/logout')}}" type="button" class="es-btn es-w-md-auto">
                                Logout
                            </a>
                            @else
                            <a href="{{url('logouts')}}" type="button" class="es-btn es-w-md-auto">
                                Logout
                            </a>
                            @endif

                        </div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
