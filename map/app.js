var map = L.map("map").setView([51.454514, -2.58791], 12);
var hour = 8;

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

var select_element = document.querySelector("select");

for (var i = 0; i < 24; i++) {
  var options = document.createElement("option");
  options.textContent = i < 10 ? "0" + i : i;
  options.value = options.textContent;
  select_element.appendChild(options);
}

function fetchData() {
  var cachedData = localStorage.getItem("cachedData");
  if (cachedData) {
    // If cached data exists, parse and return it
    return Promise.resolve(JSON.parse(cachedData));
  } else {
    // If no cached data exists, fetch it from the server
    return fetch("get_data.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch data");
        }
        return response.json();
      })
      .then((data) => {
        // Store fetched data in local storage
        localStorage.setItem("cachedData", JSON.stringify(data));
        return data;
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        return null;
      });
  }
}

fetchData().then((data) => {
  for (monitor in data) {
    var geocode = data[monitor]["geocode"];
    var nox = data[monitor]["nox"][hour];
    var no = data[monitor]["no"][hour];
    var no2 = data[monitor]["no2"][hour];
    var coordinates = geocode.split(",").map(parseFloat);
    var markerIcon = L.icon({
      iconUrl:
        "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png", // URL to the red marker icon image
      iconSize: [25, 41], // Size of the default marker icon
      iconAnchor: [12, 41], // Point of the icon which corresponds to marker's location
      popupAnchor: [1, -34], // Point from which the popup should open relative to the iconAnchor
    });
    var marker = L.marker(coordinates, { icon: markerIcon }).addTo(map);
    var popupContent = "<table>";
    popupContent += "<th>" + monitor + "</th>";
    popupContent += "<tr><td> nox </td> <td>" + nox + "</td></tr>";
    popupContent += "<tr><td> no </td> <td>" + no + "</td></tr>";
    popupContent += "<tr><td> no2 </td> <td>" + no2 + "</td></tr>";

    // Bind popup to marker
    marker.bindPopup(popupContent);
  }
});
