<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI HQ Corp - Dashboard</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8f9fa;
        }
        tbody{
            color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            background: rgb(17 24 39);
            min-height: 100vh;
        }

        /* Dashboard card styles */
        .dashboard-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            background-color: rgb(31 41 55);
            color: #fff;
        }

        /* Table styling */
        table.dataTable thead {
            background-color: rgb(31 41 55);
            color: #fff;
        }

        .btn-qr, .btn-edit, .btn-delete {
            margin: 0 5px;
        }
        button.btn.btn-dark {
            background-color: #FFF;
            color: rgb(17 24 39);
        }

        .dataTables_wrapper .dataTables_length select {
    border: 1px solid #fff;
    border-radius: 3px;
    padding: 5px;
    background-color: rgb(31 41 55);
    color: inherit;
    padding: 4px;
}
    </style>
</head>
<body>
    <!-- Sidebar -->
     <!-- Sidebar Include -->
     @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>List of Employees</h4>
                    <button class="btn btn-dark" data-toggle="modal" data-target="#addemployeeModal">Add Employee</button>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped text-center" id="employeeTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $employee->employee_name }}</td>
                                    <td>{{ $employee->department }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-qr" data-toggle="modal" data-target="#qrCodeModal{{ $employee->tbl_employee_id }}">
                                            QR
                                        </button>
                                        <button class="btn btn-secondary btn-sm btn-edit" onclick="updateemployee({{ $employee->tbl_employee_id }})">Edit</button>
                                        <button class="btn btn-danger btn-sm btn-delete" onclick="deleteemployee({{ $employee->tbl_employee_id }})">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addemployeeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addemployee" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addemployee">Add employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="employeeName">Full Name:</label>
                            <input type="text" class="form-control" id="employeeName" name="employee_name">
                        </div>
                        <div class="form-group">
                            <label for="employeeCourse">Department:</label>
                            <input type="text" class="form-control" id="employeeCourse" name="department">
                        </div>
                        <button type="button" class="btn btn-secondary form-control qr-generator" onclick="generateQrCode()">Generate QR Code</button>

                        <div class="qr-con text-center" style="display: none;">
                            <input type="hidden" class="form-control" id="generatedCode" name="generated_code">
                            <p>Take a pic with your QR code.</p>
                            <img class="mb-4" src="" id="qrImg" alt="">
                        </div>
                        <div class="modal-footer modal-close" style="display: none;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Add List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Update Employee Modal -->
<div class="modal fade" id="updateemployeeModal" tabindex="-1" aria-labelledby="updateemployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateemployeeModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="updateEmployeeName">Name</label>
                        <input type="text" class="form-control" id="updateEmployeeName" name="employee_name" required>
                    </div>
                    <div class="form-group">
                        <label for="updateEmployeeCourse">Department</label>
                        <input type="text" class="form-control" id="updateEmployeeCourse" name="department" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- QR Code Modal Template -->
    @foreach ($employees as $employee)
    <div class="modal fade" id="qrCodeModal{{ $employee->tbl_employee_id }}" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR Code for {{ $employee->employee_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="qrImg{{ $employee->tbl_employee_id }}" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $employee->employee_name }}-{{ $employee->department }}" alt="QR Code">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.8/dist/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#employeeTable').DataTable();
        });

        function generateQrCode() {
            const employeeName = $('#employeeName').val();
            const department = $('#employeeCourse').val();

            if (employeeName && department) {
                const generatedCode = `${employeeName}-${department}`;
                $('#generatedCode').val(generatedCode);
                $('#qrImg').attr('src', `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${generatedCode}`);
                $('.qr-con').show();
                $('.modal-footer.modal-close').show();
            } else {
                alert('Please fill in all fields.');
            }
        }

        function updateemployee(id) {
            $.ajax({
                url: '/employees/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#updateEmployeeName').val(data.employee_name);
                    $('#updateEmployeeCourse').val(data.department);
                    $('#updateForm').attr('action', '/employees/' + id);
                    $('#updateemployeeModal').modal('show');
                },
                error: function() {
                    alert('Error fetching employee data. Please try again.');
                }
            });
        }

        function deleteemployee(id) {
            if (confirm('Are you sure you want to delete this employee?')) {
                $.ajax({
                    url: '/employees/' + id,
                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error deleting employee. Please try again.');
                        }
                    },
                    error: function() {
                        alert('Error deleting employee. Please try again.');
                    }
                });
            }
        }
    </script>
</body>
</html>