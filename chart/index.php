<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>charts</title>
  <link rel="stylesheet" href="styles.css" type="text/css" />
  <!--Load the AJAX API-->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script type="text/javascript">
    // Load the Visualization API and the piechart package.
    google.charts.load('current', {
      'packages': ['corechart']
    });

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['Age', 'Weight'],
        [8, 12],
        [4, 5.5],
        [11, 14],
        [4, 5],
        [3, 3.5],
        [6.5, 7]
      ]);

      var options = {
        title: 'Age vs. Weight comparison',
        hAxis: {
          title: 'Age',
          minValue: 0,
          maxValue: 15
        },
        vAxis: {
          title: 'Weight',
          minValue: 0,
          maxValue: 15
        },
        legend: 'none'
      };

      var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));

      chart.draw(data, options);
    }
  </script>
</head>

<body>
  <div class="container">
    <section class="chart1">
      <h1>Scatter Chart</h1>
      <div class="choices">
        <select>
          <?php
          include("../config.php");

          foreach (MONITORS as $key => $value) {
            echo "<option value=$key>$value</option>";
          }
          ?>
        </select>
      </div>
      <div class="scatter_chart"></div>
    </section>
    <section class="chart2">
      <h1>Line Chart</h1>
      <div class="options">
        <div class="option">
          <input type="radio" value="nox" id="nox" name="category">
          <label for="nox">NOx</label>
          <input type="radio" value="no" id="no" name="category">
          <label for="no">NO</label>
          <input type="radio" value="no2" id="no2" name="category">
          <label for="no2">NO2</label>
        </div>
      </div>
      <div class="line_chart"></div>
  </div>
  </section>
  </div>
</body>

</html>