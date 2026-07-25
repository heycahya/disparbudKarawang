<script setup>
import { onMounted, onBeforeUnmount, shallowRef, watch, nextTick } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

import markerIconUrl from 'leaflet/dist/images/marker-icon.png';
import markerIconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadowUrl from 'leaflet/dist/images/marker-shadow.png';

const props = defineProps({
    destinations: {
        type: Array,
        default: () => []
    },
    height: {
        type: String,
        default: '100%'
    }
});

const mapContainer = shallowRef(null);
const mapInstance = shallowRef(null);
let resizeObserver = null;

// Fix default leaflet icons path issue in bundlers
const fixLeafletIcons = () => {
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: markerIconRetinaUrl,
        iconUrl: markerIconUrl,
        shadowUrl: markerShadowUrl,
    });
};

const renderMarkers = () => {
    if (!mapInstance.value) return;

    // Clear existing markers if any
    mapInstance.value.eachLayer((layer) => {
        if (layer instanceof L.Marker) {
            mapInstance.value.removeLayer(layer);
        }
    });

    const bounds = [];

    if (props.destinations && props.destinations.length > 0) {
        props.destinations.forEach(item => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);

            if (!isNaN(lat) && !isNaN(lng)) {
                bounds.push([lat, lng]);

                const marker = L.marker([lat, lng]).addTo(mapInstance.value);

                const popupContent = `
                    <div style="max-width: 230px; font-family: system-ui, -apple-system, sans-serif;">
                        ${item.image_url ? `<img src="${item.image_url}" style="width: 100%; height: 115px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;" />` : ''}
                        <h4 style="font-weight: 700; margin: 0 0 4px 0; color: #004b23; font-size: 14px;">${item.name}</h4>
                        ${item.category ? `<span style="display: inline-block; background: #ECFDF5; color: #059669; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 12px; margin-bottom: 6px;">${item.category}</span>` : ''}
                        <p style="font-size: 12px; color: #4B5563; margin: 0 0 6px 0; line-height: 1.3;">${item.description || ''}</p>
                        ${item.address ? `<p style="font-size: 11px; color: #6B7280; margin: 0 0 8px 0;">📍 ${item.address}</p>` : ''}
                        ${item.slug ? `<a href="/tourism/${item.slug}" rel="external" style="display: inline-block; font-size: 12px; color: #004b23; font-weight: 700; text-decoration: none;">Lihat Detail &rarr;</a>` : ''}
                    </div>
                `;
                marker.bindPopup(popupContent);
            }
        });
    }

    if (bounds.length > 1) {
        mapInstance.value.fitBounds(bounds, { padding: [40, 40] });
    } else if (bounds.length === 1) {
        mapInstance.value.setView(bounds[0], 14);
    }
};

const invalidate = () => {
    nextTick(() => {
        if (mapInstance.value) {
            mapInstance.value.invalidateSize();
        }
    });
};

onMounted(() => {
    fixLeafletIcons();

    if (mapContainer.value) {
        const defaultCenter = [-6.3084, 107.3047]; // Karawang Center
        mapInstance.value = L.map(mapContainer.value, {
            scrollWheelZoom: false,
        }).setView(defaultCenter, 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(mapInstance.value);

        renderMarkers();

        // Multi-stage invalidation to ensure 100% full render without grey/white patches
        invalidate();
        setTimeout(() => invalidate(), 150);
        setTimeout(() => invalidate(), 500);

        // Attach ResizeObserver to auto invalidate on container resize
        if (window.ResizeObserver) {
            resizeObserver = new ResizeObserver(() => {
                invalidate();
            });
            resizeObserver.observe(mapContainer.value);
        }
    }
});

watch(() => props.destinations, () => {
    if (mapInstance.value) {
        renderMarkers();
        invalidate();
    }
}, { deep: true });

onBeforeUnmount(() => {
    if (resizeObserver && mapContainer.value) {
        resizeObserver.disconnect();
    }
    if (mapInstance.value) {
        mapInstance.value.remove();
        mapInstance.value = null;
    }
});
</script>

<template>
    <div class="relative w-full h-full min-h-[320px] overflow-hidden rounded-2xl shadow-lg border border-emerald-900/10 bg-slate-100 dark:bg-slate-900">
        <div ref="mapContainer" :style="{ height: height || '100%', width: '100%' }" class="w-full h-full min-h-[320px] z-10"></div>
    </div>
</template>
