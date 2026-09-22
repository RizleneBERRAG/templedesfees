/* Carte de la page Contact — Leaflet + fond sombre CartoDB, aux couleurs du site.
   On n'affiche jamais l'adresse exacte : une zone, et les repères d'accès. */

import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const conteneur = document.getElementById('carte');

if (conteneur) {
    const data = JSON.parse(conteneur.dataset.carte);
    const bronze = '#B07A3C';

    const carte = L.map(conteneur, {
        scrollWheelZoom: false,       // on ne vole pas le défilement de la page
        zoomControl: true,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &middot; &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 18,
    }).addTo(carte);

    // La zone de l'élevage.
    const zone = L.circle([data.zone.lat, data.zone.lng], {
        radius: data.zone.rayon,
        color: bronze,
        weight: 1.5,
        opacity: 0.9,
        fillColor: bronze,
        fillOpacity: 0.14,
    }).addTo(carte);

    zone.bindPopup(`<strong>${data.zone.titre}</strong><br>${data.zone.detail}`);

    const pastille = (libelle, principal = false) => L.divIcon({
        className: '',
        html: `<span class="pin ${principal ? 'pin-main' : ''}"><i></i><b>${libelle}</b></span>`,
        iconSize: [0, 0],
        iconAnchor: [0, 0],
    });

    L.marker([data.zone.lat, data.zone.lng], { icon: pastille(data.zone.titre, true) })
        .addTo(carte)
        .bindPopup(`<strong>${data.zone.titre}</strong><br>${data.zone.detail}`);

    const points = [[data.zone.lat, data.zone.lng]];

    data.reperes.forEach((r) => {
        L.marker([r.lat, r.lng], { icon: pastille(r.titre) })
            .addTo(carte)
            .bindPopup(`<strong>${r.titre}</strong><br>${r.detail}`);
        points.push([r.lat, r.lng]);
    });

    carte.fitBounds(points, { padding: [60, 60] });

    // Le zoom à la molette ne s'active qu'après un clic sur la carte.
    carte.on('click', () => carte.scrollWheelZoom.enable());
    carte.on('mouseout', () => carte.scrollWheelZoom.disable());
}
