// Load Google Charts API
google.charts.load("current", { packages: ["corechart"] });

function drawScatterChart(monitor) {
  $.ajax({
    url: "get-scatter-chart-data.php",
    type: "GET",
    dataType: "json",
    data: { monitor: monitor },
    success: function (data) {
      var dataTable = new google.visualization.DataTable();
      dataTable.addColumn("string", "Year-Month");
      dataTable.addColumn("number", "Average NO");

      data.forEach(function (item) {
        dataTable.addRow([item.year_month, item.average_NO]);
      });

      var options = {
        title: "Average NO by Year-Month at 8:00",
        hAxis: { title: "Year-Month", titleTextStyle: { color: "#333" } },
        vAxis: { title: "Average NO", minValue: 0 },
        legend: { position: "bottom" },
      };

      var chart = new google.visualization.ScatterChart(
        document.querySelector(".scatter_chart")
      );
      chart.draw(dataTable, options);
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data:", error);
    },
  });
}

function drawLineChart(monitor, selected_date) {
  $.ajax({
    url: "get-line-chart-data.php",
    type: "GET",
    dataType: "json",
    data: { monitor: monitor, selected_date: selected_date },
    success: function (data) {
      var dataTable = new google.visualization.DataTable();
      dataTable.addColumn("string", "Hour");
      dataTable.addColumn("number", "NOX");
      dataTable.addColumn("number", "NO");
      dataTable.addColumn("number", "NO2");

      // Extract and sort the keys (hours) from the data object
      var hours = Object.keys(data).sort((a, b) => parseInt(a) - parseInt(b));

      // Debugging: Print sorted hours array
      // console.log("Sorted Hours:", hours);

      // Iterate over the sorted keys to add rows to the dataTable
      hours.forEach(function (hour) {
        dataTable.addRow([
          hour,
          parseFloat(data[hour].nox),
          parseFloat(data[hour].no),
          parseFloat(data[hour].no2),
        ]);
      });

      var options = {
        title: "Hourly Pollutant Data",
        hAxis: { title: "Hour", titleTextStyle: { color: "#333" } },
        vAxis: { title: "Level", minValue: 0 },
        curveType: "function",
        legend: { position: "bottom" },
      };

      var chart = new google.visualization.LineChart(
        document.querySelector(".line_chart")
      );

      chart.draw(dataTable, options);
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data:", error);
    },
  });
}

$("#monitor").change(function () {
  var monitor = $("#monitor").val() ?? 203; // Get selected monitor
  var selected_date = $("#date_picker").val() ?? "2022-01-01"; // Get selected date
  drawScatterChart(monitor); // Redraw charts when monitor selection changes
  drawLineChart(monitor, selected_date); // Redraw charts when monitor selection changes
});

$("#date_picker").change(function () {
  var monitor = $("#monitor").val() ?? 203; // Get selected monitor
  var selected_date = $("#date_picker").val() ?? "2022-01-01"; // Get selected date
  drawLineChart(monitor, selected_date); // Redraw charts when monitor selection changes
});

// Load charts when document is ready
$(document).ready(function () {
  var monitor = $("#monitor").val() ?? 203; // Get selected monitor
  var selected_date = $("#date_picker").val() ?? "2022-01-01"; // Get selected date
  drawScatterChart(monitor);
  drawLineChart(monitor, selected_date);
});
