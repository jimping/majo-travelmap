window._travelmap = window._travelmap || {
	map: null,
	marker: null,
};

window._travelmap.init = function () {
	window._travelmap.map = L.map('map').setView([-27, 127], 5);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(window._travelmap.map);

	window._travelmap.map.on('click', function (e) {
		document.getElementById('lat').value = e.latlng.lat;
		document.getElementById('lng').value = e.latlng.lng;

		if (window._travelmap.marker) {
			window._travelmap.map.removeLayer(window._travelmap.marker);
		}

		window._travelmap.marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(window._travelmap.map);
	});

	document.querySelector('#locate').addEventListener('click', function () {
		navigator.geolocation.getCurrentPosition(function (position) {
			document.getElementById('lat').value = position.coords.latitude;
			document.getElementById('lng').value = position.coords.longitude;

			if (window._travelmap.marker) {
				window._travelmap.map.removeLayer(window._travelmap.marker);
			}

			window._travelmap.marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(window._travelmap.map);
			window._travelmap.map.setView([position.coords.latitude, position.coords.longitude], 10);
		});
	});
}


