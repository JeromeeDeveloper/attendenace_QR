<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI HQ Corp</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

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

        .pagination .page-link {
    color: #333; /* Text color */
    background-color: #f8f9fa; /* Light background for pagination items */
    border: 1px solid #dee2e6; /* Border for pagination items */
    margin: 0 4px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    color: #fff;
    background-color: #007bff; /* Bootstrap blue for hover */
}

.pagination .active .page-link {
    background-color: #007bff; /* Active page background */
    color: #fff;
    border-color: #007bff;
}


        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
        }

        .attendance-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .attendance-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
        }

        .attendance-container > div:last-child {
            width: 64%;
            margin-left: auto;
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
                    <a class="nav-link active" href="{{ route('attendance.index') }}">Take Attendance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('employees.index') }}">AI HQ Corp Employees</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main">
        <div class="attendance-container row">
            <div class="qr-container col-4">
                <div class="scanner-con">
                    <h5 class="text-center">Scan QR Code here for attendance</h5>
                    <video id="interactive" class="viewport" width="100%"></video>
                </div>

                <div class="qr-detected-container" style="display: none;">
                    <form action="{{ route('attendance.store') }}" method="POST">
                        @csrf
                        <h4 class="text-center">Employee QR Detected!</h4>
                        <input type="hidden" id="detected-qr-code" name="qr_code">
                        <button type="submit" class="btn btn-dark form-control">Submit Attendance</button>
                    </form>
                </div>
            </div>

            <div class="attendance-list col-8">
    <h4>Filter Date</h4>
    <!-- Date Filter Form -->
    <form method="GET" action="{{ route('attendance.index') }}" class="mb-3">
        <div class="form-row">
            <div class="form-group col-md-4">
                <input type="date" class="form-control" id="attendance-date" name="date" value="{{ request()->get('date') }}" placeholder="Filter by Date">
            </div>
            <div class="form-group col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Dynamic title for the list -->
    <h4 id="attendance-title">
        List of Present Employees <span id="selected-date-text"></span>
    </h4>

    <!-- Attendance table -->
    <div class="table-container table-responsive">
        <table class="table text-center table-sm" id="attendanceTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Department</th>
                    <th scope="col">Time In</th>
                    <th scope="col">Time Out</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->employee->employee_name }}</td>
                    <td>{{ $attendance->employee->department }}</td>
                    <td>
                        @if ($attendance->time_in)
                            {{ \Carbon\Carbon::parse($attendance->time_in)->timezone('Asia/Manila')->format('l, F j, Y g:i A') }}
                        @endif
                    </td>
                    <td>
                        @if ($attendance->time_out)
                            {{ \Carbon\Carbon::parse($attendance->time_out)->timezone('Asia/Manila')->format('l, F j, Y g:i A') }}
                        @else
                            Not Yet Clocked Out
                        @endif
                    </td>
                    <td>
                        <div class="action-button">
                            <button class="btn btn-danger delete-button" onclick="deleteAttendance({{ $attendance->tbl_attendance_id }})">X</button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            {{ $attendances->appends(request()->query())->links('pagination::bootstrap-4') }}
        </nav>
    </div>
</div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    
    <!-- instascan Js -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>

        

        let scanner;

        function startScanner() {
            scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

            scanner.addListener('scan', function (content) {
                $("#detected-qr-code").val(content);
                console.log(content);
                scanner.stop();
                document.querySelector(".qr-detected-container").style.display = '';
                document.querySelector(".scanner-con").style.display = 'none';
            });

            Instascan.Camera.getCameras()
                .then(function (cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        console.error('No cameras found.');
                        alert('No cameras found.');
                    }
                })
                .catch(function (err) {
                    console.error('Camera access error:', err);
                    alert('Camera access error: ' + err);
                });
        }

        document.addEventListener('DOMContentLoaded', startScanner);

        function deleteAttendance(id) {
            if (confirm("Do you want to remove this attendance?")) {
                const url = "{{ url('attendance') }}/" + id; // Ensure this is correct
                console.log("Delete URL:", url); // Log the URL
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // Ensure CSRF token is included
                    },
                    success: function(response) {
                        console.log(response.success); // Log the success message
                        location.reload(); // Reload the page
                    },
                    error: function(xhr) {
                        console.error('Deletion failed:', xhr.responseText);
                        alert('Failed to delete attendance. Please try again.');
                    }
                });
            }
        }
    </script>
</body>
</html>
