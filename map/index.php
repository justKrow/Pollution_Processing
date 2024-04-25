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
      Average Pollution Level Across 01/01/2015 - 31/12/2022 in one hour period from
      <select name="hour" id="hour"></select> : 00
    </h1>
  </div>
  <div id="map" style="height: 400px"></div>
  <div class="indicators">
    <table class="ratings">
      <tr>
        <td style="background-color: rgb(43,131,203)">Band</td>
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
      <tr>
        <td style="background-color: rgb(43,131,203);">CB5g/mCB3</td>
        <td id="low">0-67</td>
        <td id="lOw">68-134</td>
        <td id="loW">135-200</td>
        <td id="moderate">201-267</td>
        <td id="moDERate">268-334</td>
        <td id="moderATE">335-400</td>
        <td id="high">401-467</td>
        <td id="hIgh">468-534</td>
        <td id="hiGh">535-600</td>
        <td id="veryHigh">601 or more</td>
      </tr>
    </table>
  </div>
  <script type="text/javascript" src="app.js"></script>
</body>

</html>