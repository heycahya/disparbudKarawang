<script setup>
import { onMounted, onBeforeUnmount, shallowRef, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    destinations: {
        type: Array,
        default: () => []
    },
    height: {
        type: String,
        default: '450px'
    }
});

const mapContainer = shallowRef(null);
const mapInstance = shallowRef(null);

// Fix default leaflet icons path issue in bundler
const fixLeafletIcons = () => {
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    });
};

const renderMarkers = () => {
    if (!mapInstance.value) return;

    // Default center Karawang: -6.3084, 107.3047
    if (props.destinations && props.destinations.length > 0) {
        props.destinations.forEach(item => {
            if (item.latitude && item.longitude) {
                const marker = L.marker([item.latitude, item.longitude]).addTo(mapInstance.value);
                
                const popupContent = `
                    <div style="max-width: 220px; font-family: sans-serif;">
                        ${item.image_url ? `<img src="${item.image_url}" style="width: 100%; height: 110px; object-fit: cover; border-radius: 6px; margin-bottom: 8px;" />` : ''}
                        <h4 style="font-weight: 700; margin: 0 0 4px 0; color: #0F5E3D; font-size: 14px;">${item.name}</h4>
                        ${item.category ? `<span style="display: inline-block; background: #ECFDF5; color: #059669; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 12px; margin-bottom: 6px;">${item.category}</span>` : ''}
                        <p style="font-size: 12px; color: #4B5563; margin: 0 0 6px 0; line-height: 1.3;">${item.description || ''}</p>
                        ${item.address ? `<p style="font-size: 11px; color: #6B7280; margin: 0 0 8px 0;">📍 ${item.address}</p>` : ''}
                        ${item.slug ? `<a href="/tourism/${item.slug}" rel="external" style="display: inline-block; font-size: 12px; color: #0F5E3D; font-weight: 600; text-decoration: none;">Lihat Detail &rarr;</a>` : ''}
                    </div>
                `;
                marker.bindPopup(popupContent);
            }
        });
    }
};

onMounted(() => {
    fixLeafletIcons();

    if (mapContainer.value) {
        // Center the map on the first destination if only one is provided, otherwise default to Karawang
        const hasSingleDest = props.destinations && props.destinations.length === 1 && props.destinations[0].latitude && props.destinations[0].longitude;
        const centerCoords = hasSingleDest ? [props.destinations[0].latitude, props.destinations[0].longitude] : [-6.3084, 107.3047];
        const initialZoom = hasSingleDest ? 14 : 10;

        mapInstance.value = L.map(mapContainer.value).setView(centerCoords, initialZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapInstance.value);

        renderMarkers();
    }
});

watch(() => props.destinations, () => {
    if (mapInstance.value) {
        renderMarkers();
    }
}, { deep: true });

onBeforeUnmount(() => {
    // Memory leak prevention on Inertia route change
    if (mapInstance.value) {
        mapInstance.value.remove();
        mapInstance.value = null;
    }
});
</script>

<template>
    <div class="relative w-full overflow-hidden rounded-2xl shadow-lg border border-emerald-900/10">
        <div ref="mapContainer" :style="{ height: height, width: '100%' }" class="z-0"></div>
    </div>
</template>
