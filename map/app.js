var map = L.map("map").setView([51.454514, -2.58791], 12);

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
    var backgroundColor = calculateBackgroundColor(no2);

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
    map.eachLayer(function (layer) {
      if (layer instanceof L.Marker) {
        map.removeLayer(layer);
      }
    });
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

function calculateBackgroundColor(no2) {
  // Determine background color based on no2 value
  if (no2 >= 0 && no2 <= 67) {
    return "rgb(158, 207, 108)";
  } else if (no2 >= 68 && no2 <= 134) {
    return "rgb(49, 255, 0)";
  } else if (no2 >= 135 && no2 <= 200) {
    return "rgb(49, 207, 0)";
  } else if (no2 >= 135 && no2 <= 200) {
    return "rgb(49, 207, 0)";
  } else if (no2 >= 201 && no2 <= 267) {
    return "rgb(255, 255, 0)";
  } else if (no2 >= 268 && no2 <= 334) {
    return "rgb(255, 207, 0)";
  } else if (no2 >= 335 && no2 <= 400) {
    return "rgb(255, 154, 0)";
  } else if (no2 >= 401 && no2 <= 467) {
    return "rgb(255, 100, 100)";
  } else if (no2 >= 468 && no2 <= 534) {
    return "rgb(255, 0, 0)";
  } else if (no2 >= 535 && no2 <= 600) {
    return "rgb(153, 0, 0)";
  } else if (no2 >= 601) {
    return "rgb(206, 48, 255)";
  }
}
