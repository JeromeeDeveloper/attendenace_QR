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
        tbody {
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
            padding: 0 50px;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #fff;
            border-radius: 3px;
            padding: 5px;
            background-color: rgb(31 41 55);
            color: inherit;
            padding: 4px;
        }

        input.form-control {
            background: rgb(31 41 55);
            color: white;
            width: 50%;
        }
        .calendar {
            display: flex;
            gap: 20px;
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
                    <h4>Employee Attendance List</h4>
                </div>
                <form method="GET" action="{{ route('employee.attendance') }}">
                    <div class="form-group">
                        <label for="date">Filter by Date:</label>
                        <div class="calendar">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        <button type="submit" class="btn btn-dark">Filter</button>
                    </div>
                  
                </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped text-center" id="attendanceTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attend)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $attend->employee->employee_name }}</td>
                                <td>{{ $attend->employee->department }}</td>
                                <td>
                                    @if ($attend->time_in)
                                        {{ \Carbon\Carbon::parse($attend->time_in)->timezone('Asia/Manila')->format('l, F j, Y g:i A') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($attend->time_out)
                                        {{ \Carbon\Carbon::parse($attend->time_out)->timezone('Asia/Manila')->format('l, F j, Y g:i A') }}
                                    @else
                                        Not Yet Clocked Out
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function () {
            $('#attendanceTable').DataTable();
        });
    </script>
</body>
</html>
