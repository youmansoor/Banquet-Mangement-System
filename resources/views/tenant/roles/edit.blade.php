<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role: {{ $role->name }} - Banquet Management</title>
</head>
<body>
    @include('tenant.nav')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 text-dark font-weight-bold">
                            <i class="ti-pencil-alt text-warning me-2"></i> Edit Role: <span class="text-primary">{{ $role->name }}</span>
                        </h4>
                        <a href="{{ route('tenant.roles.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti-arrow-left"></i> Back to Roles
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tenant.roles.update', $role) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Role Name & Master Select All --}}
                            <div class="row mb-4 align-items-end">
                                <div class="col-md-6">
                                    <label for="name" class="form-label font-weight-bold">
                                        Role Name <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $role->name) }}"
                                        required
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <button type="button" class="btn btn-outline-primary" id="masterSelectAllBtn">
                                        <i class="ti-check-box me-1"></i> Select All Permissions
                                    </button>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3 font-weight-bold text-dark">
                                <i class="ti-key me-1"></i> Module Permissions:
                            </h5>

                            {{-- Grouped Permissions Grid --}}
                            <div class="row">
                                @forelse($groupedPermissions as $moduleName => $permissions)
                                    @php
                                        $moduleIcon = \App\Enums\PermissionEnum::moduleIcon($moduleName);
                                    @endphp
                                    <div class="col-lg-6 col-xl-4 mb-4">
                                        <div class="card h-100 border shadow-none module-card">
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                                <span class="font-weight-bold text-dark">
                                                    <i class="{{ $moduleIcon }} text-primary me-1"></i> {{ $moduleName }}
                                                </span>
                                                <div class="form-check m-0">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input module-select-all"
                                                        id="module_{{ Str::slug($moduleName) }}"
                                                    >
                                                    <label class="form-check-label small text-muted" for="module_{{ Str::slug($moduleName) }}">
                                                        All
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="card-body p-3">
                                                @foreach($permissions as $permission)
                                                    @php
                                                        $enumCase = \App\Enums\PermissionEnum::tryFrom($permission->name);
                                                        $label = $enumCase ? $enumCase->label() : $permission->name;
                                                    @endphp
                                                    <div class="form-check mb-2">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input permission-checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            id="permission_{{ $permission->id }}"
                                                            @checked(in_array($permission->id, old('permissions', $rolePermissions)))
                                                        >
                                                        <label class="form-check-label text-secondary small" for="permission_{{ $permission->id }}">
                                                            {{ $label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted">No permissions found in the database.</p>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Buttons --}}
                            <div class="mt-4 pt-3 border-top d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ti-save me-1"></i> Update Role & Permissions
                                </button>
                                <a href="{{ route('tenant.roles.index') }}" class="btn btn-secondary px-4">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('tenant.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Master Select All
            const masterBtn = document.getElementById('masterSelectAllBtn');
            const allCheckboxes = document.querySelectorAll('.permission-checkbox');

            function checkAllState() {
                const total = allCheckboxes.length;
                const checked = document.querySelectorAll('.permission-checkbox:checked').length;
                return total > 0 && total === checked;
            }

            let allSelected = checkAllState();
            if (masterBtn) {
                masterBtn.innerHTML = allSelected
                    ? '<i class="ti-close me-1"></i> Deselect All Permissions'
                    : '<i class="ti-check-box me-1"></i> Select All Permissions';

                masterBtn.addEventListener('click', function () {
                    allSelected = !allSelected;
                    allCheckboxes.forEach(cb => cb.checked = allSelected);
                    document.querySelectorAll('.module-select-all').forEach(cb => cb.checked = allSelected);
                    masterBtn.innerHTML = allSelected
                        ? '<i class="ti-close me-1"></i> Deselect All Permissions'
                        : '<i class="ti-check-box me-1"></i> Select All Permissions';
                });
            }

            // Per-Module Select All
            document.querySelectorAll('.module-card').forEach(card => {
                const moduleCheckbox = card.querySelector('.module-select-all');
                const permissionCheckboxes = card.querySelectorAll('.permission-checkbox');

                if (moduleCheckbox) {
                    moduleCheckbox.addEventListener('change', function () {
                        permissionCheckboxes.forEach(cb => cb.checked = moduleCheckbox.checked);
                    });

                    permissionCheckboxes.forEach(cb => {
                        cb.addEventListener('change', function () {
                            const allChecked = Array.from(permissionCheckboxes).every(c => c.checked);
                            moduleCheckbox.checked = allChecked;
                        });
                    });

                    // Initial check for module
                    moduleCheckbox.checked = Array.from(permissionCheckboxes).every(c => c.checked && permissionCheckboxes.length > 0);
                }
            });
        });
    </script>
</body>
</html>