<!DOCTYPE html>
<html>

<head>
  <title>Map with Station Markers</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <link rel="stylesheet" href="styles.css" type="text/css" />
</head>

<body>
  <div class="title">
    <h1>
      Air Quality Across 01/01/2015 - 31/12/2022 at
      <select name="hour" id="hour"></select>
    </h1>
  </div>
  <div id="map" style="height: 400px"></div>
  <div class="indicators">
    <table class="ratings">
      <tr>
        <td id="low">Low</td>
        <td id="lOw">Low</td>
        <td id="loW">Low</td>
        <td id="moderate">Moderate</td>
        <td id="moDERate">Moderate</td>
        <td id="moderATE">Moderate</td>
        <td id="high">High</td>
        <td id="hIgh">High</td>
        <td id="hiGh">High</td>
        <td id="veryHigh">Very High</td>
      </tr>
    </table>
  </div>
  <script type="text/javascript" src="app.js"></script>
</body>

</html>