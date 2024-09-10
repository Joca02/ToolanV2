<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <script src="https://kit.fontawesome.com/a6397c1be2.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/post.js') }}"></script>

</head>
<body>
<!--NAVIGATION BAR-->
<div class="container-fluid">
    <div class="row" id="upper-panel">
        <div class="col">
            {{--            <img src="/uploads/toolan.png" alt="logo" id="logo">--}}
            <img src="/uploads/admin.png" alt="logoadmin" id="adminLogo">
        </div>
        <div class="col-6" id="search-div">

            <input type="text" class="form-control" id="search" placeholder="Search...">
            {{--            <img src="/uploads/admin.png" alt="logoadmin" id="adminLogo">--}}

        </div>
        <div class="usrBar col">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class='currUserName'>{{ Auth::user()->name }}</span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="profile?id={{ Auth::user()->id_user }}">View Profile</a>
                    <a class="dropdown-item" href="/admin/statistics-page">Statistics</a>
                    <a class="dropdown-item" href="{{route('logout')}}">Log Out</a>
                </div>

                <img src="/{{ Auth::user()->profile_picture }}" class='pfpNav' data-userid="{{ Auth::user()->id_user }}">
            </div>
        </div>
    </div>
</div>



<!--HOME PAGE-->
<div class="container-fluid text-center" id="home-container">

    <div>
        <div class="row">
            <div class="col">
            </div>
            <div class="col-7" id="post-container">
                <div id="suggestion-box" class="list-group"></div>


                <!--STATISTICS TABLE-->
                <div class="container mt-5">
                    <h2>User Statistics</h2>
                    <br>
                    <table class="table table-bordered">
                        <thead class="statistics-header">
                        <tr>
                            <th>Action Type</th>
                            <th>Today</th>
                            <th>This Week</th>
                            <th>This Month</th>
                            <th>This Year</th>
                            <th>Total Count</th>
                            <th>Monthly Average</th>
                        </tr>
                        </thead>
                        <tbody id="statisticsTableBody">
                        <!-- Data -->
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="col">
            </div>
        </div>
    </div>
</div>



</body>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function() {
        fetchStatistics();

        function fetchStatistics() {
            $.get("/admin/statistics", function (data) {
                let tableBody = '';
                $.each(data, function (action, stats) {
                    tableBody += `
                        <tr class="statistics-content">
                            <td>${action}</td>
                            <td>${stats.today}</td>
                            <td>${stats.thisWeek}</td>
                            <td>${stats.thisMonth}</td>
                            <td>${stats.thisYear}</td>
                            <td>${stats.totalCount}</td>
                            <td>${stats.totalAverage}</td>
                        </tr>
                    `;
                });
                $('#statisticsTableBody').html(tableBody);
            });
        }
    });

</script>

</html>
