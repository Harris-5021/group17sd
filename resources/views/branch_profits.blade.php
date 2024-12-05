<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        // Prepare the data for the pie chart
        var data = google.visualization.arrayToDataTable([
          ['Branch', 'Total Earnings'], // Header row
          @foreach($totalEarnings as $earning)
            ['{{ $earning->branch_name }}', {{ $earning->total_earnings }}],
          @endforeach
        ]);

        var options = {
          title: 'Total Earnings by Branch',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <h1>Total Earnings by Branch</h1>
    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
  </body>
</html>
