<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page PIN Settings</title>
</head>
<body>
    @include('admin.nav')
    <div class="row g-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Page PIN Settings</h3>
                    <a href="{{ route('admin.profile') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.page-pins.update') }}" method="POST" id="pinsForm">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Page</th>
                                    <th>PIN</th>
                                    <th>Has PIN</th>
                                    <th>Enable 2-PIN Security</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                    <tr>
                                        <td>{{ $page['name'] }}</td>
                                        <td>
                                            <input type="text" 
                                                   name="pins[{{ $page['key'] }}]" 
                                                   class="form-control" 
                                                   placeholder="Enter 4-6 digit PIN"
                                                   pattern="\d{4,6}"
                                                   title="PIN must be 4-6 digits">
                                        </td>
                                        <td>
                                            @if($page['has_pin'])
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       name="enabled[{{ $page['key'] }}]" 
                                                       class="form-check-input" 
                                                       {{ $page['enabled'] ? 'checked' : '' }}
                                                       onchange="togglePagePinStatus('{{ $page['key'] }}', this.checked); event.stopPropagation();">
                                                <label class="form-check-label">Enable</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Save PINs</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.footer')

<script>
function togglePagePinStatus(pageKey, enabled) {
    event.preventDefault();
    
    const form = new FormData();
    form.append('page_key', pageKey);
    form.append('enabled', enabled ? '1' : '0');
    form.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route('admin.profile.page-pins.status') }}', {
        method: 'POST',
        body: form,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status updated successfully');
        } else {
            alert('Error: ' + (data.message || 'Failed to update status'));
            // Revert checkbox
            event.target.checked = !enabled;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update status');
        event.target.checked = !enabled;
    });
}
</script>
</body>
</html>