<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI HQ Corp</title>

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
            background: linear-gradient(to bottom, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.15) 100%), radial-gradient(at top center, rgba(255,255,255,0.40) 0%, rgba(0,0,0,0.40) 120%) #989898;
            background-blend-mode: multiply,multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
        }

        .employee-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .employee-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
            height: 100%;
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting_asc_disabled, table.dataTable thead > tr > th.sorting_desc_disabled, table.dataTable thead > tr > td.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting_asc_disabled, table.dataTable thead > tr > td.sorting_desc_disabled {
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand ml-4" href="#">AI HQ Corp Attendance System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                
            </ul>
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                    <a class="nav-link" href="{{ route('attendance.index') }}">Take Attendance</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </li>                
            </ul>
        </div>
    </nav>

    <div class="main">
        <div class="employee-container">
            <div class="employee-list">
                <div class="title">
                    <h4>List of Employees</h4>
                    <button class="btn btn-dark" data-toggle="modal" data-target="#addemployeeModal">Add employee</button>
                </div>
                <hr>
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="employeeTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Department</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $employee->employee_name }}</td>
                                    <td>{{ $employee->department }}</td>
                                    <td>
                                        <div class="action-button">
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#qrCodeModal{{ $employee->tbl_employee_id }}">
                                                <img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="" width="16">
                                            </button>

                                            <!-- QR Modal -->
                                            <div class="modal fade" id="qrCodeModal{{ $employee->tbl_employee_id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ $employee->employee_name }}'s QR Code</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $employee->generated_code }}" alt="" width="300">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="btn btn-secondary btn-sm" onclick="updateemployee({{ $employee->tbl_employee_id }})">&#128393;</button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteemployee({{ $employee->tbl_employee_id }})">&#10006;</button>
                                        </div>
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
                            <p>Take a pic with your qr code.</p>
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

    <!-- Update Modal -->
    <div class="modal fade" id="updateemployeeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="updateemployee" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateemployee">Update employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="updateEmployeeName">Full Name:</label>
                            <input type="text" class="form-control" id="updateEmployeeName" name="employee_name">
                        </div>
                        <div class="form-group">
                            <label for="updateEmployeeCourse">Department:</label>
                            <input type="text" class="form-control" id="updateEmployeeCourse" name="department">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Update List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
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
            url: '/employees/' + id + '/edit', // Change this to the correct route
            type: 'GET',
            success: function(data) {
                $('#updateEmployeeName').val(data.employee_name);
                $('#updateEmployeeCourse').val(data.department);
                $('#updateForm').attr('action', '/employees/' + id); // Change to employees route
                $('#updateemployeeModal').modal('show');
            },
            error: function(xhr) {
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
                    alert(response.message); // Show success message
                    location.reload(); // Reload the page on success
                } else {
                    alert('Error deleting employee. Please try again.');
                }
            },
            error: function(xhr) {
                alert('Error deleting employee. Please try again.');
            }
        });
    }
}


    </script>
</body>
</html>
