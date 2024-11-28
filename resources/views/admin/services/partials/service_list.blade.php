@forelse ($services as $service)
    <tr>
        <td>{{ $service->title }}</td>
        <td>
            <button type="button" data-bs-toggle="modal" data-bs-target="#photoModal" data-image="{{ $service->photo }}"
                class="border-0 es-outline-none bg-transparent p-0 hover-darken-95">
                <img src="{{ $service->photo ? $service->photo : url('/images/subscriptions-img.png') }}"
                    width="40" height="40" alt="Service Image" />
            </button>
        </td>
        <td>{{ $service->description }}</td>
        <td>{{ $service->created_at->format('M d, Y') }}</td>
        <!-- Updated to format date -->
        <td>{{ $service->status == 3 ? $service->updated_at->format('M d, Y') : ''}}</td>
        <!-- Example for dynamic date -->
        <td>{{ $service->category->name ?? 'Uncategorized' }}</td>
        <!-- Assuming a relationship exists -->
        <td>{{ $service->session_capacity }}</td>
        <td>{{ $service->session_timeframe }} min</td>
        <td>{{ $service->blockout_time }} min</td>
        <td>
            <div class="d-flex align-items-center">
                @if(auth()->guard('admin')->user()->hasPermission('edit_services'))
                <a href="#updateServiceModal" class="es-link-primary" data-bs-toggle="modal"
                    data-id="{{ $service->id }}"
                    data-category_id="{{ $service->category_id }}"
                    data-description="{{ $service->description }}"
                    data-title="{{ $service->title }}"
                    data-session_timeframe="{{ $service->session_timeframe }}"
                    data-session_capacity="{{ $service->session_capacity }}"
                    data-blockout_time="{{ $service->blockout_time }}"
                    data-photo="{{ $service->photo ?? '' }}" role="button"
                    data-archived_at="{{ $service->archived_at }}"
                    >
                    View/Edit
                </a>
                @else
                <a href="#viewServiceModal" class="es-link-primary" data-bs-toggle="modal"
                    data-id="{{ $service->id }}"
                    data-category_id="{{ $service->category_id }}"
                    data-description="{{ $service->description }}"
                    data-title="{{ $service->title }}"
                    data-session_timeframe="{{ $service->session_timeframe }}"
                    data-session_capacity="{{ $service->session_capacity }}"
                    data-blockout_time="{{ $service->blockout_time }}"
                    data-photo="{{ $service->photo ?? '' }}" role="button">
                    View
                </a>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr class="text-center "><td colspan="10">No records found</td></tr>
@endforelse
