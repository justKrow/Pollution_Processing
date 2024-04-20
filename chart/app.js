// Load Google Charts API
google.charts.load('current', { packages: ['corechart'] });

function drawScatterChart(data) {
  var dataTable = new google.visualization.DataTable();
  dataTable.addColumn('string', 'Year-Month');
  dataTable.addColumn('number', 'Average NO');

  data.forEach(function(item) {
    dataTable.addRow([item.year_month, item.average_NO]);
  });

  var options = {
    title: 'Average NO by Year-Month at 8:00',
    hAxis: { title: 'Year-Month', titleTextStyle: { color: '#333' } },
    vAxis: { title: 'Average NO', minValue: 0 },
    legend: 'none'
  };

  var chart = new google.visualization.ScatterChart(document.querySelector('.scatter_chart'));
  chart.draw(dataTable, options);
}

// Function to fetch data from get-data.php and draw charts
function drawCharts() {
  var monitor = $('#monitor').val() ?? 203; // Get selected monitor
  var selectedDate = $('#date_picker').val() ?? '2022-01-01'; // Get selected date
  // console.log((selectedDate))

  $.ajax({
    url: 'get-scatter-chart-data.php',
    type: 'GET',
    dataType: 'json',
    data: { monitor: monitor },
    success: function(response) {
      drawScatterChart(response); // Draw scatter chart with the entire response
      drawLineChart(response); // Draw line chart with the
    },
    error: function(xhr, status, error) {
      console.error('Error fetching data:', error);
    }
  });
}

// Function to handle changes in monitor selection
$('#monitor').change(function() {
  drawCharts(); // Redraw charts when monitor selection changes
});

// Load charts when document is ready
$(document).ready(function() {
  drawCharts();
});
