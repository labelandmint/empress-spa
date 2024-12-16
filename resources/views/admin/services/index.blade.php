@extends('layouts.app')
@section('content')

    <style type="text/css">
        .no-text-decoration {
            text-decoration: none;
        }

        .pagination {
            display: flex;
            justify-content: end;
            align-items: center;
            flex-wrap: wrap;
            padding: 10px;
        }

        .active>.page-link,
        .page-link.active {
            background-color: #BB7E45 !important;
            border: 1px solid #BB7E45 !important;
        }

        .page-link {
            color: black;
        }
    </style>

    <div class="col-lg-9 es-py-8">
        <div class="es-mb-10">
            <div class="es-header-4 es-font-mulish-bold">Services</div>
        </div>

        <div class="card border-0 rounded-3">
            <div class="card-body px-0 pt-0 es-pb-6">
                <div class="es-table">
                    <div
                        class="es-table-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 gap-md-0">
                        <div class="es-font-mulish-bold es-text-xl">Services</div>
                        <div class="d-flex align-items-center gap-4">
                            <div class="d-flex gap-3">
                                <button id="sortAsc" class="bg-transparent p-0 es-btn-icon">
                                    <img src="{{ asset('images/filter-up-dark.png') }}" alt="" />
                                </button>

                                <button id="sortDesc" class="bg-transparent p-0 es-btn-icon">
                                    <img src="{{ asset('images/filter-down-dark.png') }}" alt="" />
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label for="filter" class="es-font-600 es-text-gray-500 text-nowrap">
                                    Filter by:
                                </label>
                                <select id="filter" class="form-select border-0 h-100 es-font-600 es-text-gray-900"
                                    aria-label="Default select example">
                                    <option value="1">Active</option>
                                    <option value="3">Archived</option>
                                </select>
                            </div>
                            @if (auth()->guard('admin')->user()->hasPermission('add_services'))
                                <div>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#addServiceModal"
                                        class="es-link-primary border-0 bg-transparent es-text-lg">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 4.16699V15.8337" stroke="#BB7E45" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M4.16602 10H15.8327" stroke="#BB7E45" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Add Service
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="es-table-container">
                        <table id="servicesTable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Photo</th>
                                    <th>Description</th>
                                    <th>Date Added</th>
                                    <th>Date Archived</th>
                                    <th>Category</th>
                                    <th>Session Capacity</th>
                                    <th>Session Time</th>
                                    <th>Blockout Time</th>
                                    <th>View/Edit</th>
                                </tr>
                            </thead>
                            <tbody id="services-rows">
                                @if (count($services) > 0)
                                    @foreach ($services as $service)
                                        <tr>
                                            <td>{{ $service->title }}</td>
                                            <td>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#photoModal"
                                                    data-image="{{ url('images/'.$service->photo) ?? asset('images/services-image.svg') }}"
                                                    class="border-0 es-outline-none bg-transparent p-0 hover-darken-95">
                                                    <img src="{{ url('images/'.$service->photo) ?? asset('images/services-image.svg') }}"
                                                        width="40" height="40" alt="Service Image" />
                                                </button>
                                            </td>
                                            <td>{{ $service->description }}</td>
                                            <td>{{ $service->created_at->format('M d, Y') }}</td>
                                            <!-- Updated to format date -->
                                            <td>{{ $service->status == 3 ? $service->archived_at->format('M d, Y') : '' }}
                                            </td>
                                            <!-- Example for dynamic date -->
                                            <td>{{ $service->category->name ?? 'Uncategorized' }}</td>
                                            <!-- Assuming a relationship exists -->
                                            <td>{{ $service->session_capacity }}</td>
                                            <td>{{ $service->session_timeframe }} min</td>
                                            <td>{{ $service->blockout_time }} min</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (auth()->guard('admin')->user()->hasPermission('edit_services'))
                                                        <a href="#updateServiceModal" class="es-link-primary"
                                                            data-bs-toggle="modal" data-id="{{ $service->id }}"
                                                            data-category_id="{{ $service->category_id }}"
                                                            data-description="{{ $service->description }}"
                                                            data-title="{{ $service->title }}"
                                                            data-session_timeframe="{{ $service->session_timeframe }}"
                                                            data-session_capacity="{{ $service->session_capacity }}"
                                                            data-blockout_time="{{ $service->blockout_time }}"
                                                            data-photo="{{ url('images/'.$service->photo) ?? '' }}" role="button"
                                                            data-archived_at="{{ $service->archived_at }}">
                                                            View/Edit
                                                        </a>
                                                    @else
                                                        <a href="#viewServiceModal" class="es-link-primary"
                                                            data-bs-toggle="modal" data-id="{{ $service->id }}"
                                                            data-category_id="{{ $service->category_id }}"
                                                            data-description="{{ $service->description }}"
                                                            data-title="{{ $service->title }}"
                                                            data-session_timeframe="{{ $service->session_timeframe }}"
                                                            data-session_capacity="{{ $service->session_capacity }}"
                                                            data-blockout_time="{{ $service->blockout_time }}"
                                                            data-photo="{{ url('images/'.$service->photo) ?? '' }}" role="button">
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
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="addServiceModal" tabindex="-1" role="dialog"
        aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 540px">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-4">
                        <form action="{{ url('admin/services/store') }}" class="" method="post"
                            enctype="multipart/form-data" id="add-services">
                            @csrf
                            <div class="es-text-lg es-font-600 es-mb-6">New Service</div>
                            <div class="es-mb-4">
                                <label for="title"> Title </label>
                                <input name="title" type="text" class="form-control es-input mt-2"
                                    placeholder="Title" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mb-4">
                                <div>Photo :</div>
                                <div class="es-text-gray-500 es-mt-2 es-mb-4">
                                    Resolution requirements: 500x500px
                                </div>
                                <div class="mt-2">
                                    <input type="file" name="photo" accept=".jpg,.jpeg,.png" hidden
                                        id="photo_input_add_service" />
                                    <label for="photo_input_add_service" class="es-file-input"
                                        id="photo-label-add-service">
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
                                    <div class="d-none" id="file-preview-container-add-service">
                                        <img src="#" alt="Preview Uploaded Image" id="photo-preview-add-service" 
                                            class="img-fluid es-h-80 es-mb-3 file-preview img-fluid img500x500" />
                                        <div class="d-flex es-gap-8">
                                            <label for="photo_input_add_service"
                                                class="btn border-0 es-text-sm es-font-600 p-0">
                                                Change
                                                <img src="{{ asset('images/refresh.png') }}" width="14"
                                                    height="14" alt="" />
                                            </label>
                                            <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                                id="clear_photo_input_add_service">
                                                Delete
                                                <img src="{{ asset('images/trash.png') }}" width="14"
                                                    height="14" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="es-mb-4">
                                <label for="description"> Description </label>
                                <input name="description" type="text" class="form-control es-input mt-2"
                                    placeholder="Description" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mb-8">
                                <label for="addServiceCategory"> Category </label>
                                <div class="es-input-error-message"></div>
                                <div class="es-h-13 mt-2">
                                    <select class="add-service-category form-select" id="addServiceCategory"
                                        name="category_id">
                                        <option value=""></option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                            <div class="row es-mb-2">
                                <div class="col-xl-4 mb-4">
                                    <label for="session_capacity"> Session Capacity </label>
                                    <input name="session_capacity" type="text" class="form-control es-input mt-2"
                                        placeholder="Session Capacity" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 mb-4">
                                    <label for="session_timeframe"> Session Timeframe </label>
                                    <select name="session_timeframe" name="session_timeframe"
                                        class="form-select es-select mt-2">
                                        <option value="5">5 min</option>
                                        <option value="10">10 min</option>
                                        <option value="20">20 min</option>
                                        <option value="30">30 min</option>
                                        <option value="60">60 min</option>
                                    </select>
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 mb-4">
                                    <label for="blockout_time"> Blockout Time </label>
                                    <select name="blockout_time" class="form-select es-select mt-2">
                                        <option value="5">5 min</option>
                                        <option value="10">10 min</option>
                                        <option value="20">20 min</option>
                                        <option value="30">30 min</option>
                                        <option value="60">60 min</option>
                                    </select>
                                    <div class="es-input-error-message"></div>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="es-btn">
                                    Add Service
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Service Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="updateServiceModal" tabindex="-1" role="dialog"
        aria-labelledby="updateServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 540px">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images//close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-4">
                        <form action="{{ url('admin/services/store') }}" class="" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" hidden id="id" />
                            <input type="hidden" name="photo_url" hidden id="update_photo_url" />
                            <div class="es-text-lg es-font-600 es-mb-6">Update Service</div>
                            <div class="es-mb-4">
                                <label for="title"> Title </label>
                                <input id="title" name="title" type="text" class="form-control es-input mt-2"
                                    placeholder="Title" value="Nail Treatment" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mb-4">
                                <div>Photo :</div>
                                <div class="es-text-gray-500 es-mt-2 es-mb-4">
                                    Resolution requirements: 500x500px
                                </div>
                                <div class="mt-2">
                                    <input name="photo" type="file" accept=".jpg,.jpeg,.png" hidden
                                        id="photo_input_update_service" />
                                    <label for="photo_input_update_service" class="es-file-input"
                                        id="photo-label-update-service">
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
                                    <div class="d-none" id="file-preview-container-update-service">
                                        <img src="#" alt="Preview Uploaded Image"
                                            id="photo-preview-update-service" class="img-fluid  es-mb-3 file-preview img500x500" />
                                        <div class="d-flex es-gap-8">
                                            <label for="photo_input_update_service"
                                                class="btn border-0 es-text-sm es-font-600 p-0">
                                                Change
                                                <img src="{{ asset('images/refresh.png') }}" width="14"
                                                    height="14" alt="" />
                                            </label>
                                            <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                                id="clear_photo_input_update_service">
                                                Delete
                                                <img src="{{ asset('images/trash.png') }}" width="14"
                                                    height="14" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="es-mb-4">
                                <label for="description"> Description </label>
                                <input id="description" name="description" type="text"
                                    class="form-control es-input mt-2" placeholder="Description"
                                    value="Full Set Pink & White (French)" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mb-4">
                                <label for="addServiceCategory"> Category </label>
                                <div class="es-h-13 mt-2">
                                    <select class="form-control update-service-category" name="category_id"
                                        id="category_id">
                                        <option value=""></option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="row es-mb-2">
                                <div class="col-xl-4 mb-4">
                                    <label for="session_capacity"> Session Capacity </label>
                                    <input id="session_capacity" name="session_capacity" type="number"
                                        class="form-control es-input mt-2" placeholder="Session Capacity" />
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 mb-4">
                                    <label for="session_timeframe"> Session Timeframe </label>
                                    <select name="session_timeframe" id="session_timeframe"
                                        class="form-select es-select mt-2">
                                        <option value="5">5 min</option>
                                        <option value="10">10 min</option>
                                        <option value="20">20 min</option>
                                        <option value="30">30 min</option>
                                        <option value="60">60 min</option>
                                    </select>
                                    <div class="es-input-error-message"></div>
                                </div>
                                <div class="col-xl-4 mb-4">
                                    <label for="blockout_time"> Blockout Time </label>
                                    <select name="blockout_time" id="blockout_time" class="form-select es-select mt-2">
                                        <option value="5">5 min</option>
                                        <option value="10">10 min</option>
                                        <option value="20">20 min</option>
                                        <option value="30">30 min</option>
                                        <option value="60">60 min</option>
                                    </select>
                                    <div class="es-input-error-message"></div>
                                </div>
                            </div>
                            <div class="d-flex es-gap-x-5">
                                <button type="submit" data-bs-dismiss="modal" class="es-btn">
                                    Save
                                </button>
                                <a id="update-archive-button" class="es-btn-outline no-text-decoration ">
                                    Archive
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Service Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="viewServiceModal" tabindex="-1" role="dialog"
        aria-labelledby="viewServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 540px">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images//close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-4">
                        <input type="hidden" name="id" hidden id="view_id" />
                        <div class="es-text-lg es-font-600 es-mb-6">View Service</div>
                        <div class="es-mb-4">
                            <label for="view_title"> Title </label>
                            <input id="view_title" name="title" type="text" class="form-control es-input mt-2"
                                placeholder="Title" readonly />
                        </div>
                        <div class="es-mb-4">
                            Photo
                            <div class="mt-2">
                                <img src="#" alt="Service Image" id="view_photo_preview"
                                    class="es-h-80 es-mb-3 file-preview d-none img-fluid img500x500" />
                            </div>
                        </div>
                        <div class="es-mb-4">
                            <label for="view_description"> Description </label>
                            <input id="view_description" name="description" type="text"
                                class="form-control es-input mt-2" placeholder="Description" readonly />
                        </div>
                        <div class="es-mb-4">
                            <label for="view_addServiceCategory"> Category </label>
                            <div class="es-h-13 mt-2">
                                <select class="form-control" name="category_id" id="category_id" disabled>
                                    <option value=""></option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row es-mb-2">
                            <div class="col-xl-4 mb-4">
                                <label for="view_session_capacity"> Session Capacity </label>
                                <input id="view_session_capacity" name="session_capacity" type="text"
                                    class="form-control es-input mt-2" placeholder="Session Capacity" readonly />
                            </div>
                            <div class="col-xl-4 mb-4">
                                <label for="view_session_timeframe"> Session Timeframe </label>
                                <input id="view_session_timeframe" name="session_timeframe" type="text"
                                    class="form-control es-input mt-2" placeholder="Session Timeframe" readonly />
                            </div>
                            <div class="col-xl-4 mb-4">
                                <label for="view_blockout_time"> Blockout Time </label>
                                <input id="view_blockout_time" name="blockout_time" type="text"
                                    class="form-control es-input mt-2" placeholder="Blockout Time" readonly />
                            </div>
                        </div>

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
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-5">
                        <img id="modalImage" src="{{ asset('images/services-image.svg') }}" alt=""
                            class="img500x500 img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script>
        const photo_input_add_service = document.getElementById("photo_input_add_service");

        photo_input_add_service.addEventListener("change", () => {
            const file = photo_input_add_service.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById("photo-preview-add-service");

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document.getElementById("photo-label-add-service").classList.add("d-none");
                document
                    .getElementById("file-preview-container-add-service")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input_add_service")
            .addEventListener("click", () => {
                document.getElementById("photo_input_add_service").value = null;
                document.getElementById("photo-label-add-service").classList.remove("d-none");
                document
                    .getElementById("file-preview-container-add-service")
                    .classList.add("d-none");
            });

        const photo_input_update_service = document.getElementById("photo_input_update_service");

        photo_input_update_service.addEventListener("change", () => {
            const file = photo_input_update_service.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById("photo-preview-update-service");

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document.getElementById("photo-label-update-service").classList.add("d-none");
                document
                    .getElementById("file-preview-container-update-service")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input_update_service")
            .addEventListener("click", () => {
                document.getElementById("photo_input_update_service").value = null;
                document.getElementById("photo-label-update-service").classList.remove("d-none");
                document
                    .getElementById("file-preview-container-update-service")
                    .classList.add("d-none");

                $('#update_photo_url').val('');
            });

        $(document).ready(function() {
            $("#addServiceCategory").select2({
                theme: "bootstrap-5",
                placeholder: "Category",
                tags: true,
                dropdownParent: $('#addServiceModal'),
            });
        });


        // function formatState(state) {
        //     if (!state.id) {
        //         return state.text; // Placeholder
        //     }
        //     var $state = $('<span><span class="checkbox-icon"></span>' + state.text + '</span>');
        //     return $state; // Return formatted state for display
        // }

        // $(document).ready(function () {
        //     $("#addServiceCategory").select2({
        //         theme: "bootstrap-5",
        //         closeOnSelect: true, // Close the dropdown after selecting an option
        //         placeholder: "Choose an option or add a custom category",
        //         templateResult: formatState,
        //         tags: true, // Enables adding custom options
        //         createTag: function (params) {
        //             var term = $.trim(params.term);
        //             if (term === '') {
        //                 return null; // Prevent empty tags
        //             }
        //             return {
        //                 id: term,
        //                 text: term,
        //                 newTag: true // Flag to indicate it's a new tag
        //             };
        //         },
        //         allowClear: true // Optional: allows users to clear their selection
        //     });
        // });

        // $(".update-service-category").select2({
        //     theme: "bootstrap-5",
        //     placeholder: "Category",
        //     tags: true,
        //     dropdownParent: $('#updateServiceModal')
        // });



        $(document).ready(function() {

            $('#photoModal').on('show.bs.modal', function(event) {
                // Get the button that triggered the modal
                var button = $(event.relatedTarget);
                // Extract the image URL from the data attribute
                var imageUrl = button.data('image');
                // Find the modal image element and update its src attribute
                $('#modalImage').attr('src', imageUrl);
            });

            var isAscending = true;
            var $table = $('#servicesTable');
            var $tbody = $table.find('tbody');
            var $originalRows = $tbody.find('tr').clone(); // Clone rows to restore original order

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
            // Handle the 'show.bs.modal' event for the update modal
            $('#updateServiceModal').on('show.bs.modal', function(event) {
                // Get the link that triggered the modal
                var button = $(event.relatedTarget);
                // Extract the data from the link's data attributes
                var id = button.data('id');
                var title = button.data('title');
                var description = button.data('description');
                var category_id = button.data('category_id');
                var session_capacity = button.data('session_capacity');
                var session_timeframe = button.data('session_timeframe');
                var blockout_time = button.data('blockout_time');
                var photo = button.data('photo');
                var archived_at = button.data('archived_at');

                // Set the data in the modal form fields
                var modal = $(this);
                modal.find('#id').val(id);
                modal.find('#update-archive-button').attr('href', "{{ url('admin/services/archive') }}/" +
                    id); // Changed productId to id
                modal.find('#title').val(title);
                modal.find('#description').val(description);
                modal.find('#category_id').val(category_id);
                modal.find('#session_capacity').val(session_capacity);
                modal.find('#session_timeframe').val(session_timeframe);
                modal.find('#blockout_time').val(blockout_time);

                if (photo) {
                    modal.find('#photo-preview-update-service').attr('src', photo);
                    modal.find('#file-preview-container-update-service').removeClass('d-none');
                    $('#photo-label-update-service').addClass('d-none');
                    $('#update_photo_url').val(photo);
                } else {
                    modal.find('#photo-preview-update-service').attr('src',
                    ''); // Clear the image preview if no photo
                    modal.find('#file-preview-container-update-service').addClass('d-none');
                    $('#photo-label-update-service').removeClass('d-none');
                }

                if (archived_at) {
                    modal.find('#update-archive-button').text('Restore');
                } else {
                    modal.find('#update-archive-button').text('Archive');
                }
            });

            // Handle the clear photo button click
            $('#update_clear_photo_input').click(function() {
                $('#update-photo-preview').attr('src', ''); // Clear the image preview
                $('#update_photo_input').val(''); // Clear the file input
                $('#update-file-preview-container').addClass('d-none'); // Hide the file preview container
                $('#update-photo-label').removeClass('d-none'); // Show the photo label

            });
        });


        $(document).ready(function() {
            $('#filter').change(function() {
                var filterValue = $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ url('admin/services/filter') }}", // Route to your filtering method
                    type: 'GET',
                    data: {
                        status: filterValue
                    },
                    success: function(response) {
                        // Update the table body with the new rows
                        $('#services-rows').html(response);
                    }
                });
            });
        });


        $('#viewServiceModal').on('show.bs.modal', function(event) {
            // Get the link that triggered the modal
            var button = $(event.relatedTarget);

            // Extract the data from the link's data attributes
            var id = button.data('id');
            var title = button.data('title');
            var description = button.data('description');
            var category_id = button.data('category_id');
            var session_capacity = button.data('session_capacity');
            var session_timeframe = button.data('session_timeframe');
            var blockout_time = button.data('blockout_time');
            var photo = button.data('photo');

            // Set the data in the modal form fields
            var modal = $(this);
            modal.find('#view_id').val(id);
            modal.find('#view_title').val(title);
            modal.find('#view_description').val(description);
            modal.find('#view_session_capacity').val(session_capacity);
            modal.find('#view_addServiceCategory').val(category_id);
            modal.find('#view_session_timeframe').val(session_timeframe);
            modal.find('#view_blockout_time').val(blockout_time);

            // Handle photo preview
            if (photo) {
                modal.find('#view_photo_preview').attr('src', photo).removeClass('d-none');
            } else {
                modal.find('#view_photo_preview').attr('src', '').addClass('d-none');
            }
        });



        $(document).ready(function() {
            // Initialize form validation for the service modal
            $("#addServiceModal form").validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 2,
                        maxlength: 100 // Added max length for title
                    },
                    photo: {
                        required: true,
                        extension: "jpg,jpeg,png"
                    },
                    description: {
                        required: true,
                        minlength: 10,
                        maxlength: 500 // Added max length for description
                    },
                    category_id: {
                        required: true
                    },
                    session_capacity: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    session_timeframe: {
                        required: true
                    },
                    blockout_time: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: "Please enter a title",
                        minlength: "Title must be at least 2 characters long",
                        maxlength: "Title cannot exceed 100 characters"
                    },
                    photo: {
                        required: "Please upload a photo",
                        extension: "Only jpg, jpeg, and png formats are allowed"
                    },
                    description: {
                        required: "Please enter a description",
                        minlength: "Description must be at least 10 characters long",
                        maxlength: "Description cannot exceed 500 characters"
                    },
                    category_id: {
                        required: "Please select a category"
                    },
                    session_capacity: {
                        required: "Please enter session capacity",
                        digits: "Please enter a valid number",
                        min: "Capacity must be at least 1"
                    },
                    session_timeframe: {
                        required: "Please select a session timeframe"
                    },
                    blockout_time: {
                        required: "Please select blockout time"
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
            $('#addServiceModal form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                if ($(this).valid()) { // Only proceed if the form is valid
                    this.submit(); // Submit the form
                }
            });
        });

        $(document).ready(function() {
            let rowsPerPage = 10; // Rows to display per page
            let currentPage = 1;
            let totalRows = $("#servicesTable tbody tr").length; // Count total rows
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            function renderTable(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                // Hide all rows initially
                $("#servicesTable tbody tr").hide();

                // Show rows for the current page
                $("#servicesTable tbody tr").slice(start, end).show();
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
    </script>
@endsection
