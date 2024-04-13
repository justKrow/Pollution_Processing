<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>charts</title>
  <link rel="stylesheet" href="styles.css" type="text/css" />
  <!-- Load jQuery -->
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Load Google Charts API -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <!-- Load your app.js -->
</head>

<body>
  <div class="container">
    <div class="choices">
      <select id="monitor" name="monitor">
        <?php
        include("../config.php");

        foreach (MONITORS as $key => $value) {
          echo "<option value='$key'><h1>$value</h1></option>"; // Enclose value in quotes
        }
        ?>
      </select>
    </div>
    <div class="chart_container">
      <section class="chart1">
        <h1>Scatter Chart</h1>
        <div class="scatter_chart"></div>
      </section>
      <section class="chart2">
        <h1>Line Chart</h1>
        <div class="line_chart"></div>
      </section>
    </div>
  </div>
  <script type="text/javascript" src="app.js"></script>
</body>

</html>