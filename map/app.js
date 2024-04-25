var map = L.map("map").setView([51.454514, -2.58791], 13);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

var select_element = document.querySelector("select");

for (var i = 0; i < 24; i++) {
  var options = document.createElement("option");
  options.textContent = i < 10 ? "0" + i : i;
  options.value = options.textContent;
  if (i === 8) {
    options.selected = true;
  }
  select_element.appendChild(options);
}

function fetchData() {
  var cachedData = localStorage.getItem("cachedData");
  if (cachedData) {
    return Promise.resolve(JSON.parse(cachedData));
  } else {
    return fetch("get_data.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch data");
        }
        return response.json();
      })
      .then((data) => {
        localStorage.setItem("cachedData", JSON.stringify(data));
        return data;
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        return null;
      });
  }
}

function createMarker(data, hour) {
  for (var monitor in data) {
    var geocode = data[monitor]["geocode"];
    var nox = data[monitor]["nox"][hour];
    var no = data[monitor]["no"][hour];
    var no2 = data[monitor]["no2"][hour];
    var coordinates = geocode.split(",").map(parseFloat);
    var marker = L.marker(coordinates).addTo(map);

    // Determine background color based on no2 value
    var backgroundColor;
    if (no2 <= 50) {
      backgroundColor = "#00ff00"; // Green
    } else if (no2 <= 100) {
      backgroundColor = "#ffff00"; // Yellow
    } else if (no2 <= 150) {
      backgroundColor = "#ff9900"; // Orange
    } else {
      backgroundColor = "#ff0000"; // Red
    }

    var popupContent =
      "<div style='background-color: " +
      backgroundColor +
      "; padding: 10px; border-radius: 5px;'>";
    popupContent += "<h1>" + monitor + "</h1>";
    popupContent += "<table>";
    popupContent +=
      "<tr><td style='border: 1px solid'> nox </td> <td style='border: 1px solid'>" +
      nox +
      "</td></tr>";
    popupContent +=
      "<tr><td style='border: 1px solid'> no </td> <td style='border: 1px solid'>" +
      no +
      "</td></tr>";
    popupContent +=
      "<tr><td style='border: 1px solid'> no2 </td> <td style='border: 1px solid'>" +
      no2 +
      "</td></tr>";
    popupContent += "</table></div>";

    marker.bindPopup(popupContent);
  }
}

function updateMarkers(hour) {
  fetchData().then((data) => {
    createMarker(data, hour);
  });
}

$("#hour").change(function () {
  var hour = $("#hour").val() ?? "08";
  updateMarkers(hour);
});

$(document).ready(function () {
  var hour = $("#hour").val() ?? "08";
  updateMarkers(hour);
});
