@include('helpers.partials_headers')
<div class="main-panel" id="main-panel">

    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Users</h4>
                        @include('helpers.message_handler')
                        <div class="form-group">
                            <input type="text" id="searchField" class="form-control" placeholder="Search User by name...">
                        </div>

                        <div class="table-responsive pt-3">
                            <table class="table table-bordered" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->role->name }}</td>
                                            <td>{{ $user->isActive ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-{{ $user->isActive ? 'danger' : 'success' }}"
                                                        data-user-id="{{ $user->id }}"
                                                        onclick="toggleStatus({{ $user->id }})">
                                                    {{ $user->isActive ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>                                
                            </table>
                        </div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('helpers.copyright')
    <script>
        document.getElementById('searchField').addEventListener('keyup', function() {
            const searchValue = this.value.trim().toLowerCase();

            if (searchValue.length > 0) {
                fetch(`/super_admin/filter-users/${encodeURIComponent(searchValue)}`)
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('#usersTable tbody');
        tableBody.innerHTML = '';
        data.data.forEach((user, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${user.name}</td>
                    <td>${user.role.name}</td>
                    <td>${user.isActive ? 'Active' : 'Inactive'}</td>
                    <td>
                        <button type="button" class="btn btn-${user.isActive ? 'danger' : 'success'}"
                                data-user-id="${user.id}"
                                onclick="toggleStatus(${user.id})">
                            ${user.isActive ? 'Deactivate' : 'Activate'}
                        </button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    })
    .catch(error => {
        console.error('Error fetching users:', error);
    });

            }
        });

        function toggleStatus(userId) {
    fetch(`/super_admin/alter-user-status/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== undefined) {
            const button = document.querySelector(`button[data-user-id="${userId}"]`);

            if (button) {
                const row = button.closest('tr');
                const statusCell = row.querySelector('td:nth-child(4)');

                if (data.status === 'active') {
                    button.classList.remove('btn-success');
                    button.classList.add('btn-danger');
                    button.innerText = 'Deactivate';
                    statusCell.innerText = 'Active';
                } else if (data.status === 'inactive') {
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-success');
                    button.innerText = 'Activate';
                    statusCell.innerText = 'Inactive';
                } else {
                    console.error('Unexpected status value:', data.status);
                }
            } else {
                console.error('Button not found for userId:', userId);
            }
        } else {
            console.error('Unexpected data format:', data);
        }
    })
    .catch(error => {
        console.error('Error toggling user status:', error);
    });
}





    </script>
</div>
@include('helpers.partials_footers')
