<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tenant Page PIN Settings - {{ $tenant->business_name }}</title>
</head>
<body>

@include('admin.nav')

<div class="row g-0">
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title mb-0">
                            Tenant Page PIN Settings - {{ $tenant->business_name }}
                        </h3>

                        <small class="text-muted">
                            Manage individual page PIN security for this tenant.
                        </small>
                    </div>

                    <a href="{{ route('admin.profile') }}"
                       class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i>
                        Back to Profile
                    </a>
                </div>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fa fa-check-circle me-1"></i>
                        {{ session('success') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            Please fix the following errors:
                        </strong>

                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route(
                    'admin.profile.tenant-page-pins.update',
                    ['tenant' => $tenant->id]
                ) }}"
                      method="POST"
                      id="tenantPagePinsForm">

                    @csrf
                    @method('PUT')

                    <input type="hidden"
                           name="tenant_id"
                           value="{{ $tenant->id }}">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Page</th>
                                    <th>URL</th>
                                    <th width="230">PIN</th>
                                    <th width="130" class="text-center">Has PIN</th>
                                    <th width="220">2-PIN Security</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($pages as $index => $page)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            <strong>{{ $page['name'] }}</strong>
                                        </td>

                                        <td>
                                            @if(!empty($page['url']))
                                                <code>{{ $page['url'] }}</code>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <td>
                                            <input type="password"
                                                   name="pins[{{ $page['key'] }}]"
                                                   class="form-control tenant-pin-input"
                                                   minlength="4"
                                                   maxlength="6"
                                                   inputmode="numeric"
                                                   pattern="[0-9]{4,6}"
                                                   autocomplete="new-password"
                                                   placeholder="{{ $page['has_pin'] ? 'Leave blank to keep current PIN' : 'Enter 4-6 digit PIN' }}">

                                            <small class="text-muted">
                                                4-6 digits
                                            </small>

                                            @error("pins.{$page['key']}")
                                                <div class="text-danger small mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>

                                        <td class="text-center">
                                            @if($page['has_pin'])
                                                <span class="badge bg-success">
                                                    <i class="fa fa-check me-1"></i>
                                                    Yes
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fa fa-times me-1"></i>
                                                    No
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            {{-- IMPORTANT:
                                                 This is a separate form.
                                                 It prevents nested-form problems. --}}
                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.profile.tenant-page-pins.status',
                                                      ['tenant' => $tenant->id]
                                                  ) }}"
                                                  class="tenant-page-pin-status-form">

                                                @csrf

                                                <input type="hidden"
                                                       name="tenant_id"
                                                       value="{{ $tenant->id }}">

                                                <input type="hidden"
                                                       name="page_key"
                                                       value="{{ $page['key'] }}">

                                                <input type="hidden"
                                                       name="enabled"
                                                       value="0"
                                                       class="tenant-enabled-hidden">

                                                <div class="form-check form-switch">

                                                    <input type="checkbox"
                                                           class="form-check-input tenant-page-pin-toggle"
                                                           name="enabled"
                                                           value="1"
                                                           data-original-enabled="{{ $page['enabled'] ? '1' : '0' }}"
                                                           {{ $page['enabled'] ? 'checked' : '' }}>

                                                    <label class="form-check-label">
                                                        <span class="tenant-status-label">
                                                            {{ $page['enabled'] ? 'Enabled' : 'Disabled' }}
                                                        </span>
                                                    </label>

                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="text-center text-muted py-4">
                                            No tenant pages found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">

                        <small class="text-muted">
                            Leave a PIN field empty to keep the existing PIN.
                        </small>

                        <div>
                            <a href="{{ route('admin.profile') }}"
                               class="btn btn-secondary me-2">
                                <i class="fa fa-times me-1"></i>
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fa fa-save me-1"></i>
                                Save PINs
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@include('admin.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | PIN INPUT - DIGITS ONLY
    |--------------------------------------------------------------------------
    */
    document.querySelectorAll('.tenant-pin-input').forEach(function (input) {

        input.addEventListener('input', function () {
            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 6);
        });

    });

    /*
    |--------------------------------------------------------------------------
    | SAVE PIN FORM VALIDATION
    |--------------------------------------------------------------------------
    */
    const mainForm = document.getElementById('tenantPagePinsForm');

    if (mainForm) {
        mainForm.addEventListener('submit', function (event) {

            const inputs = mainForm.querySelectorAll('.tenant-pin-input');

            for (let i = 0; i < inputs.length; i++) {

                const value = inputs[i].value.trim();

                if (value !== '' && !/^\d{4,6}$/.test(value)) {

                    event.preventDefault();

                    alert('PIN must contain 4 to 6 digits.');

                    inputs[i].focus();

                    return;
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT PAGE PIN STATUS
    |--------------------------------------------------------------------------
    */
    document.querySelectorAll('.tenant-page-pin-toggle').forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            const checkboxElement = this;

            const statusForm =
                checkboxElement.closest('.tenant-page-pin-status-form');

            if (!statusForm) {
                return;
            }

            const enabled =
                checkboxElement.checked;

            const previousValue =
                checkboxElement.dataset.originalEnabled === '1';

            const hiddenEnabled =
                statusForm.querySelector('.tenant-enabled-hidden');

            const label =
                statusForm.querySelector('.tenant-status-label');

            if (hiddenEnabled) {
                hiddenEnabled.value = enabled ? '1' : '0';
            }

            checkboxElement.disabled = true;

            const formData = new FormData();

            formData.append(
                '_token',
                statusForm.querySelector('input[name="_token"]').value
            );

            formData.append(
                'tenant_id',
                statusForm.querySelector('input[name="tenant_id"]').value
            );

            formData.append(
                'page_key',
                statusForm.querySelector('input[name="page_key"]').value
            );

            formData.append(
                'enabled',
                enabled ? '1' : '0'
            );

            fetch(statusForm.action, {
                method: 'POST',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },

                credentials: 'same-origin',

                body: formData
            })
            .then(async function (response) {

                let data = {};

                try {
                    data = await response.json();
                } catch (e) {
                    data = {};
                }

                return {
                    ok: response.ok,
                    status: response.status,
                    data: data
                };

            })
            .then(function (result) {

                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */
                if (result.ok && result.data.success) {

                    checkboxElement.dataset.originalEnabled =
                        enabled ? '1' : '0';

                    if (label) {
                        label.textContent =
                            enabled ? 'Enabled' : 'Disabled';
                    }

                    showMessage(
                        result.data.message ||
                        'Status updated successfully.',
                        'success'
                    );

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | ERROR
                |--------------------------------------------------------------------------
                */
                checkboxElement.checked =
                    previousValue;

                checkboxElement.dataset.originalEnabled =
                    previousValue ? '1' : '0';

                if (hiddenEnabled) {
                    hiddenEnabled.value =
                        previousValue ? '1' : '0';
                }

                if (label) {
                    label.textContent =
                        previousValue ? 'Enabled' : 'Disabled';
                }

                let message =
                    result.data.message ||
                    'Failed to update status.';

                if (
                    result.data.errors &&
                    result.data.errors.tenant_id
                ) {
                    message =
                        result.data.errors.tenant_id[0];
                }

                showMessage(
                    'Error: ' + message,
                    'danger'
                );

            })
            .catch(function (error) {

                console.error(
                    'Tenant Page PIN status error:',
                    error
                );

                checkboxElement.checked =
                    previousValue;

                if (hiddenEnabled) {
                    hiddenEnabled.value =
                        previousValue ? '1' : '0';
                }

                if (label) {
                    label.textContent =
                        previousValue ? 'Enabled' : 'Disabled';
                }

                showMessage(
                    'Failed to update status.',
                    'danger'
                );

            })
            .finally(function () {

                checkboxElement.disabled = false;

            });

        });

    });

    /*
    |--------------------------------------------------------------------------
    | MESSAGE
    |--------------------------------------------------------------------------
    */
    function showMessage(message, type) {

        let container =
            document.getElementById(
                'tenantPinMessageContainer'
            );

        if (!container) {

            container =
                document.createElement('div');

            container.id =
                'tenantPinMessageContainer';

            container.style.position =
                'fixed';

            container.style.top =
                '20px';

            container.style.right =
                '20px';

            container.style.zIndex =
                '99999';

            document.body.appendChild(container);
        }

        const alert =
            document.createElement('div');

        alert.className =
            'alert alert-' + type + ' shadow';

        alert.style.minWidth =
            '320px';

        alert.style.marginBottom =
            '10px';

        alert.textContent =
            message;

        container.appendChild(alert);

        setTimeout(function () {
            alert.remove();
        }, 3500);
    }

});
</script>

</body>
</html>