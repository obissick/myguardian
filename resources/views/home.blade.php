@extends('layouts.app')

@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawExpireChart);
      google.charts.setOnLoadCallback(drawExpiredChart);
      google.charts.setOnLoadCallback(drawExpiringChart);
      function drawChart() {
        var users = {!! $users !!};
        var data = google.visualization.arrayToDataTable(
         users
      );

        var options = {
          is3D: false,
          width: '100%', height: '100%'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
      function drawExpireChart() {
        var expired = {!! $expired !!};
        var data = google.visualization.arrayToDataTable(
         expired
      );

        var options = {
          width: '100%', height: '100%'
        };

        var chart = new google.visualization.BarChart(document.getElementById('expired_3d'));
        chart.draw(data, options);
      }
      function drawExpiredChart() {
        var expiredUsers = {!! $expiredUsers !!};
        var data = google.visualization.arrayToDataTable(
         expiredUsers
      );

        var options = {
            showRowNumber: true,
            width: '100%', height: '100%'
        };

        var chart = new google.visualization.Table(document.getElementById('expiredusers_3d'));
        chart.draw(data, options);
      }
      function drawExpiringChart() {
        var expiringUsers = {!! $expiring !!};
        var data = google.visualization.arrayToDataTable(
         expiringUsers
      );

        var options = {
            showRowNumber: true,
            width: '100%', height: '100%'
        };

        var chart = new google.visualization.Table(document.getElementById('expiring'));
        chart.draw(data, options);
      }
    </script>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Users/Server</div>
                <div class="card-body">
                    <div id="piechart_3d"></div>   
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Expired Users</div>
                <div class="card-body">
                    <div id="expired_3d"></div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Expired Users</div>
                <div class="card-body">
                    <div id="expiredusers_3d"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Expiring Soon</div>
                <div class="card-body">
                    <div id="expiring"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
