document.getElementById("sightingForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch("./backend/submit_sighting.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                addSightingToPage(data.sighting);
            }
            this.reset();
        })
        .catch(error => console.error("Error:", error));
});

function addSightingToPage(sighting) {
    const container = document.getElementById("sightingsContainer");
    const div = document.createElement("div");
    div.classList.add("sighting-card");
    div.innerHTML = `
        <img src="./uploads/${sighting.image}" alt="${sighting.species}">
        <p><strong>Species:</strong> ${sighting.species}</p>
        <p><strong>Location:</strong> ${sighting.location}</p>
        <p><strong>Count:</strong> ${sighting.count}</p>
        <p><strong>Date:</strong> ${sighting.timestamp}</p>
    `;
    container.prepend(div);
}

function loadSightings() {
    fetch("./backend/fetch_sightings.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(addSightingToPage);
        })
        .catch(error => console.error("Error:", error));
}

document.addEventListener("DOMContentLoaded", loadSightings);









// Initialize Map centered on India
const map = L.map('map').setView([22.3511, 78.6677], 5); // Center of India

// Base Layer - OpenStreetMap
const baseLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Heatmap Data - Example Wildlife Sightings in India
const heatData = [
    [22.57, 88.36, 0.7],  // Kolkata
    [28.61, 77.23, 0.9],  // Delhi
    [28.71, 77.20, 0.9],  // Delhi
    [28.65, 77.22, 0.9],  // Delhi
    [19.07, 72.87, 0.8],  // Mumbai
    [13.08, 80.27, 0.6],  // Chennai
    [26.85, 80.94, 0.7],  // Lucknow
    [25.45, 81.85, 0.8],  // Prayagraj
    [27.17, 78.02, 0.9],  // Agra
    [23.03, 72.58, 0.6],  // Ahmedabad
    [15.30, 74.12, 0.8],  // Goa
    [21.15, 79.09, 0.9],  // Nagpur
    [17.38, 78.48, 0.7],  // Hyderabad
    [11.41, 76.70, 0.8],  // Ooty
    [31.63, 74.87, 0.9],  // Amritsar
    [26.91, 75.78, 0.7]   // Jaipur
];

// Heatmap Layer
const heatLayer = L.heatLayer(heatData, {
    radius: 30, // Spread of the heat points
    blur: 20,
    maxZoom: 10
});

// Animal Sightings - Different Layers
const tigerLayer = L.layerGroup([
    L.marker([22.57, 88.36]).bindPopup("Tiger Sighting - Kolkata"),
    L.marker([21.15, 79.09]).bindPopup("Tiger Sighting - Nagpur"),
    L.marker([26.91, 75.78]).bindPopup("Tiger Sighting - Jaipur")
]);

const elephantLayer = L.layerGroup([
    L.marker([11.41, 76.70]).bindPopup("Elephant Sighting - Ooty"),
    L.marker([17.38, 78.48]).bindPopup("Elephant Sighting - Hyderabad"),
    L.marker([15.30, 74.12]).bindPopup("Elephant Sighting - Goa")
]);


const nilgaiLayer = L.layerGroup([
    L.marker([28.71, 77.20]).bindPopup("Nilgai Sighting - Delhi")
]);

// Layer Control
L.control.layers({
    "Base Map": baseLayer
}, {
    "Heatmap (Frequent Sightings)": heatLayer,
    "Tiger Sightings": tigerLayer,
    "Elephant Sightings": elephantLayer,
    "Leopard Sightings": leopardLayer,
    "Nilgai Sightings": nilgaiLayer
}).addTo(map);

// Default Active Layers
heatLayer.addTo(map);




