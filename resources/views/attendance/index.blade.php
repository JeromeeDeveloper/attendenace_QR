<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>AI HQ Corp</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">

    <!-- script
    ================================================== -->
    <script src="{{ asset('js/modernizr.js') }}"></script>
    <script defer src="{{ asset('js/fontawesome/all.min.js') }}"></script>


</head>

<body id="top">

    <div id="preloader">
        <div id="loader" class="dots-fade">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <header class="s-header">

        <div class="s-header__content">
    
            <nav class="s-header__nav-wrap">
                <ul class="s-header__nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('attendance.index') }}">Attendance</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="#attendance">Present Employees</a>
                    </li>
                </ul>
            </nav> 

            <a href="{{ route('login') }}" class="btn btn--primary btn--small">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22c-5.523 0-10-4.477-10-10S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-15v5H7l5 5 5-5h-4V7h-3z"/>
                </svg>
                Login
            </a>
            
            

        </div> 

        <a class="s-header__menu-toggle" href="#0"><span>Menu</span></a>

    </header> 

    <section id="hero" class="s-hero target-section">

        <div class="s-hero__bg">
            <div class="gradient-overlay"></div>
        </div>

        <div class="hero_content">

        <div class="row s-hero__content">
            <div class="column">

                <div class="attendance-container row">
                    <div class="qr-container">
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
                </div>
            </div>
        </div>

        <div class="s-hero__video">
            <a class="s-hero__video-link" href="https://player.vimeo.com/video/117310401?color=01aef0&amp;title=0&amp;byline=0&amp;portrait=0" data-lity="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 12l-18 12v-24z"/></svg>
                <span class="s-hero__video-text">Play Video</span>
            </a>
        </div>

    </div>

    </section> <!-- end s-hero -->

    <section id="attendance">
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
                       
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>


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

    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>


</body>
</html>
