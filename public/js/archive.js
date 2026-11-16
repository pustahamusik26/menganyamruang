/**
 * ARSIP ANYAMAN: KEBUDAYAAN TIONGHOA MEDAN (menganyamruang.id)
 * Interactive Frontend Engine:
 * 1. seunghyuk.com Radial Vector Canvas ("Points, Lines, and Planes")
 * 2. 3-Jingle Loop BackSound Audio Controller
 * 3. Ethnographic Map (Locked to Medan & Deli Serdang, Numbered Markers, Zoom Overlay, Hover/Click Thumbnail+Desa+Kecamatan+Koordinat)
 * 4. Aparelho Studio 5:4 B&W-to-Color Digital Gallery & Multi-Photo Loader
 * 5. Aparelho Studio 7-Video YouTube Top-Bar Player
 */

function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function sanitizeUrl(url) {
    if (!url) return '#';
    const trimmed = String(url).trim();

    // Prevent protocol-relative redirects (e.g. //attacker.com or /\attacker.com)
    if (trimmed.startsWith('//') || trimmed.startsWith('/\\') || trimmed.startsWith('\\/')) {
        return '#';
    }

    // Allow safe internal relative paths like /images/... or /audio/...
    if (/^\/[a-zA-Z0-9_\-.~%#?=&/]+$/.test(trimmed) && !trimmed.startsWith('//')) {
        return trimmed;
    }

    // For absolute URLs, parse strictly via the URL constructor
    try {
        const parsed = new URL(trimmed);
        if (parsed.protocol === 'http:' || parsed.protocol === 'https:') {
            return parsed.href;
        }
    } catch (e) {
        // Invalid URL format
    }

    return '#';
}

document.addEventListener('DOMContentLoaded', () => {
    const DATA = window.ARCHIVE_DATA || { sites: [], videos: [], jingles: [], activeSection: 'beranda' };

    initCustomCursor();
    initSeunghyukCanvas(DATA.sites);
    initJingleController(DATA.jingles);
    initEthnographicMap(DATA.sites);
    initDigitalGallery(DATA.sites);
    initVideoPlayer(DATA.videos);
    initCatalogTable(DATA.sites);

    // If loaded via dedicated sub-route (/peta-etnografis, /galeri-digital, /video, /profil-tim), scroll smoothly
    if (DATA.activeSection && DATA.activeSection !== 'beranda') {
        const targetEl = document.getElementById(DATA.activeSection);
        if (targetEl) {
            setTimeout(() => {
                targetEl.scrollIntoView({ behavior: 'smooth' });
            }, 250);
        }
    }
});

/* ==========================================================================
   CUSTOM CURSOR FOLLOWER (SEUNGHYUK.COM STYLE)
   ========================================================================== */
function initCustomCursor() {
    const dot = document.getElementById('cursorDot');
    const ring = document.getElementById('cursorRing');
    if (!dot || !ring) return;

    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let ringX = mouseX;
    let ringY = mouseY;

    window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        dot.style.left = `${mouseX}px`;
        dot.style.top = `${mouseY}px`;
    });

    function animateCursor() {
        ringX += (mouseX - ringX) * 0.18;
        ringY += (mouseY - ringY) * 0.18;
        ring.style.left = `${ringX}px`;
        ring.style.top = `${ringY}px`;
        requestAnimationFrame(animateCursor);
    }
    requestAnimationFrame(animateCursor);

    document.querySelectorAll('a, button, input, .radial-node-item, .sidebar-place-item, .filmstrip-thumb-btn').forEach((el) => {
        el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
        el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
    });
}

/* ==========================================================================
   HOMEPAGE RADIAL CANVAS (HTTPS://SEUNGHYUK.COM/ REFERENCE)
   ========================================================================== */
function initSeunghyukCanvas(sites) {
    const canvas = document.getElementById('seunghyukCanvas');
    const stage = document.getElementById('heroCanvasStage');
    const centerAnchor = document.getElementById('radialCenterAnchor');
    const coordReadout = document.getElementById('heroLiveCoords');
    const nodeElements = document.querySelectorAll('.radial-node-item');

    if (!canvas || !stage) return;
    const ctx = canvas.getContext('2d');

    let width = stage.clientWidth;
    let height = stage.clientHeight;
    let dpr = window.devicePixelRatio || 1;

    function resize() {
        width = stage.clientWidth;
        height = stage.clientHeight;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }
    resize();
    window.addEventListener('resize', resize);

    let targetX = width / 2;
    let targetY = height / 2;
    let centerX = width / 2;
    let centerY = height / 2;
    let hoveredNodeIdx = -1;

    stage.addEventListener('mousemove', (e) => {
        const rect = stage.getBoundingClientRect();
        const relX = e.clientX - rect.left;
        const relY = e.clientY - rect.top;

        // Subtle spring displacement around center like seunghyuk.com
        targetX = width / 2 + (relX - width / 2) * 0.16;
        targetY = height / 2 + (relY - height / 2) * 0.16;

        if (coordReadout) {
            const simLat = (3.5852 + ((height / 2 - relY) / height) * 0.18).toFixed(4);
            const simLng = (98.6756 + ((relX - width / 2) / width) * 0.18).toFixed(4);
            coordReadout.textContent = `POS // ${simLat}° N, ${simLng}° E`;
        }
    });

    stage.addEventListener('mouseleave', () => {
        targetX = width / 2;
        targetY = height / 2;
    });

    nodeElements.forEach((el, idx) => {
        el.addEventListener('mouseenter', () => {
            hoveredNodeIdx = idx;
        });
        el.addEventListener('mouseleave', () => {
            hoveredNodeIdx = -1;
        });
        el.addEventListener('click', (e) => {
            const filterCat = el.getAttribute('data-filter-category');
            const targetSec = el.getAttribute('data-target-section') || 'peta-etnografis';
            if (filterCat && window.applyMapCategoryFilter) {
                e.preventDefault();
                document.getElementById(targetSec)?.scrollIntoView({ behavior: 'smooth' });
                setTimeout(() => {
                    window.applyMapCategoryFilter(filterCat);
                }, 350);
            }
        });
    });

    // Ambient background rays (like seunghyuk.com fine geometric rays)
    const ambientAngles = [
        -2.75, -2.35, -1.85, -1.35, -0.85, -0.35, 0.18, 0.62, 1.15, 1.68, 2.15, 2.65
    ];

    function renderFrame(time) {
        ctx.clearRect(0, 0, width, height);

        centerX += (targetX - centerX) * 0.09;
        centerY += (targetY - centerY) * 0.09;

        if (centerAnchor) {
            centerAnchor.style.left = `${centerX}px`;
            centerAnchor.style.top = `${centerY}px`;
        }

        // Draw subtle architectural crosshair axes through center
        ctx.save();
        ctx.strokeStyle = 'rgba(59, 34, 20, 0.08)';
        ctx.lineWidth = 1;
        ctx.setLineDash([4, 6]);
        ctx.beginPath();
        ctx.moveTo(0, centerY);
        ctx.lineTo(width, centerY);
        ctx.moveTo(centerX, 0);
        ctx.lineTo(centerX, height);
        ctx.stroke();
        ctx.restore();

        // Draw secondary ambient geometric rays from center
        ambientAngles.forEach((ang, i) => {
            const sway = Math.sin(time * 0.0006 + i) * 0.03;
            const len = Math.min(width, height) * (0.28 + (i % 3) * 0.09);
            const ex = centerX + Math.cos(ang + sway) * len;
            const ey = centerY + Math.sin(ang + sway) * len;

            ctx.save();
            ctx.strokeStyle = 'rgba(59, 34, 20, 0.13)';
            ctx.lineWidth = 0.8;
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.lineTo(ex, ey);
            ctx.stroke();

            // Small terminal tick
            ctx.fillStyle = 'rgba(59, 34, 20, 0.28)';
            ctx.fillRect(ex - 1.5, ey - 1.5, 3, 3);
            ctx.restore();
        });

        // Position and connect the primary interactive radial nodes
        nodeElements.forEach((el, idx) => {
            const pctX = parseFloat(el.getAttribute('data-x') || '50') / 100;
            const pctY = parseFloat(el.getAttribute('data-y') || '50') / 100;

            // Parallax offset relative to center displacement
            const offsetX = (centerX - width / 2) * (0.35 + (idx % 3) * 0.12);
            const offsetY = (centerY - height / 2) * (0.35 + (idx % 3) * 0.12);

            const nx = width * pctX + offsetX;
            const ny = height * pctY + offsetY;

            el.style.left = `${nx}px`;
            el.style.top = `${ny}px`;

            // Draw connecting vector line from center square to node
            ctx.save();
            const isHovered = hoveredNodeIdx === idx;
            ctx.strokeStyle = isHovered ? '#A62424' : 'rgba(59, 34, 20, 0.36)';
            ctx.lineWidth = isHovered ? 1.8 : 1.0;
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.lineTo(nx, ny);
            ctx.stroke();

            // Midpoint coordinate tick on the ray
            const mx = (centerX + nx) / 2;
            const my = (centerY + ny) / 2;
            ctx.fillStyle = isHovered ? '#A62424' : 'rgba(198, 146, 44, 0.75)';
            ctx.fillRect(mx - 2, my - 2, 4, 4);
            ctx.restore();
        });

        requestAnimationFrame(renderFrame);
    }

    requestAnimationFrame(renderFrame);
}

/* ==========================================================================
   3-JINGLE LOOP BACKSOUND CONTROLLER (PDF PAGE 5)
   ========================================================================== */
function initJingleController(jingles) {
    const controllerEl = document.getElementById('jingleController');
    const toggleBtn = document.getElementById('soundToggleBtn');
    const stateLabel = document.getElementById('soundStateLabel');
    const jingleBtns = document.querySelectorAll('.jingle-btn');
    const activeTitleEl = document.getElementById('activeJingleTitle');

    if (!controllerEl || !toggleBtn || !jingles.length) return;

    const audio = new Audio();
    audio.loop = true;
    audio.volume = 0.55;
    let currentJingle = jingles[0];
    let isPlaying = false;

    audio.src = currentJingle.src;

    function updateUI() {
        if (isPlaying) {
            controllerEl.classList.add('playing');
            if (stateLabel) stateLabel.textContent = 'SOUND ON';
        } else {
            controllerEl.classList.remove('playing');
            if (stateLabel) stateLabel.textContent = 'SOUND OFF';
        }
        jingleBtns.forEach((btn) => {
            const jid = parseInt(btn.getAttribute('data-jingle-id'), 10);
            btn.classList.toggle('active', jid === currentJingle.id);
        });
        if (activeTitleEl) {
            activeTitleEl.textContent = currentJingle.title;
        }
    }

    toggleBtn.addEventListener('click', () => {
        if (isPlaying) {
            audio.pause();
            isPlaying = false;
            updateUI();
        } else {
            audio.play().then(() => {
                isPlaying = true;
                updateUI();
            }).catch((err) => {
                console.warn('Audio play blocked:', err);
            });
        }
    });

    jingleBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const jid = parseInt(btn.getAttribute('data-jingle-id'), 10);
            const found = jingles.find((j) => j.id === jid);
            if (!found) return;
            currentJingle = found;
            audio.src = currentJingle.src;
            audio.loop = true;
            audio.play().then(() => {
                isPlaying = true;
                updateUI();
            }).catch(() => {
                updateUI();
            });
        });
    });

    updateUI();
}

/* ==========================================================================
   1. PETA ETNOGRAFIS (LOCKED TO MEDAN & DELI SERDANG, NUMBERED PINS, OVERLAYS)
   ========================================================================== */
function initEthnographicMap(sites) {
    const mapEl = document.getElementById('ethnographicMap');
    if (!mapEl || typeof L === 'undefined') return;

    // Close-up bounds locked to Kota Medan & Kabupaten Deli Serdang (PDF Page 3)
    const medanDeliSerdangBounds = L.latLngBounds(
        L.latLng(3.4200, 98.5000), // Southwest (Deli Tua / Sunggal border)
        L.latLng(3.8100, 98.8500)  // Northeast (Medan Labuhan / Belawan / Percut Sei Tuan)
    );

    const map = L.map('ethnographicMap', {
        center: [3.5952, 98.6780],
        zoom: 12,
        minZoom: 11,
        maxZoom: 18,
        maxBounds: medanDeliSerdangBounds,
        maxBoundsViscosity: 0.92,
        zoomControl: false,
    });

    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Warm archival cartographic tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    const markersLayer = L.layerGroup().addTo(map);
    let markerMap = {};
    let currentCategory = 'all';
    let currentSubCategory = 'all';
    let searchQuery = '';
    let forceOverlayLabels = false;
    let activeSiteId = sites[0]?.id || null;

    // Zoom-in overlay handler: when zoom >= 14, show Nama/Kategori overlay next to numbered pins
    function syncZoomOverlayClass() {
        const z = map.getZoom();
        if (z >= 14 || forceOverlayLabels) {
            mapEl.classList.add('show-zoom-labels');
        } else {
            mapEl.classList.remove('show-zoom-labels');
        }
        const zoomBadge = document.getElementById('mapCurrentZoomBadge');
        if (zoomBadge) {
            zoomBadge.textContent = `ZOOM ${z}x ${z >= 14 || forceOverlayLabels ? '(OVERLAY NAMA/KATEGORI AKTIF)' : '(ZOOM IN UNTUK LABEL NAMA)'}`;
        }
    }

    map.on('zoomend', syncZoomOverlayClass);
    syncZoomOverlayClass();

    // Build HTML popup content (Thumbnail + Desa + Kecamatan + Koordinat)
    function createPopupHtml(site) {
        const latStr = Number(site.latitude).toFixed(5);
        const lngStr = Number(site.longitude).toFixed(5);
        const safeThumb = sanitizeUrl(site.thumbnail);
        const safeName = escapeHtml(site.name);
        const safeCategory = escapeHtml(site.category);
        const safeSub = escapeHtml(site.sub_category || site.category);
        const safeYear = escapeHtml(site.year_period || '-');
        const safeDesa = escapeHtml(site.desa_kelurahan);
        const safeKec = escapeHtml(site.kecamatan);
        const safeCity = escapeHtml(site.regency_city);
        const safeColor = escapeHtml(site.color_hex);

        return `
            <div class="map-popup-card">
                <div class="map-popup-thumb-wrap">
                    <img src="${safeThumb}" alt="${safeName}" loading="lazy" />
                    <span class="map-popup-cat-badge" style="background:${safeColor}">
                        #${site.marker_number} • ${safeCategory}
                    </span>
                </div>
                <div class="map-popup-body">
                    <div class="map-popup-title">${safeName}</div>
                    <div style="font-size:11.5px; color:#75492E; margin-bottom:6px;">
                        ${safeSub} (${safeYear})
                    </div>
                    <div class="map-popup-meta-table">
                        <div>
                            <span>DESA / KEL.</span>
                            <span>${safeDesa}</span>
                        </div>
                        <div>
                            <span>KECAMATAN</span>
                            <span>${safeKec}</span>
                        </div>
                        <div>
                            <span>WILAYAH</span>
                            <span>${safeCity}</span>
                        </div>
                        <div>
                            <span>KOORDINAT</span>
                            <span style="font-family:monospace;">${latStr}° N, ${lngStr}° E</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Update Right Sidebar Detail Drawer
    function updateActiveInspector(site) {
        if (!site) return;
        activeSiteId = site.id;

        document.querySelectorAll('.sidebar-place-item').forEach((item) => {
            item.classList.toggle('active', parseInt(item.getAttribute('data-site-id'), 10) === site.id);
        });

        const drawer = document.getElementById('sidebarDetailDrawer');
        if (!drawer) return;

        const latStr = Number(site.latitude).toFixed(5);
        const lngStr = Number(site.longitude).toFixed(5);
        const safeName = escapeHtml(site.name);
        const safeCategory = escapeHtml(site.category);
        const safeSub = escapeHtml(site.sub_category || '-');
        const safeYear = escapeHtml(site.year_period || '-');
        const safeDesa = escapeHtml(site.desa_kelurahan);
        const safeKec = escapeHtml(site.kecamatan);
        const safeAddress = escapeHtml(site.address);
        const safeUniqueness = escapeHtml(site.uniqueness || '');
        const safeCode = escapeHtml(site.site_code);
        const safeColor = escapeHtml(site.color_hex);
        const safeGmapsUrl = sanitizeUrl(site.gmaps_url);
        const safeSourceUrl = sanitizeUrl(site.source_url);

        drawer.innerHTML = `
            <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:8px;">
                <span style="background:${safeColor}; color:#FAF6EE; font-family:monospace; font-size:10.5px; padding:2px 8px; font-weight:600;">
                    TITIK #${site.marker_number} (${safeCode}) • ${safeCategory.toUpperCase()}
                </span>
                <span style="font-family:monospace; font-size:11px; color:#75492E;">${safeYear}</span>
            </div>
            <h4 style="font-size:16px; font-weight:600; color:#24140C; margin-bottom:4px;">${safeName}</h4>
            <p style="font-size:12px; color:#75492E; margin-bottom:10px;">Sub-Kategori: <strong>${safeSub}</strong></p>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; background:#F3ECE0; padding:8px 10px; border:1px solid rgba(59,34,20,0.16); font-size:11.5px; margin-bottom:10px;">
                <div><small style="display:block; color:#9A6E52; font-family:monospace; font-size:9.5px;">DESA / KELURAHAN</small><strong>${safeDesa}</strong></div>
                <div><small style="display:block; color:#9A6E52; font-family:monospace; font-size:9.5px;">KECAMATAN</small><strong>${safeKec}</strong></div>
                <div style="grid-column:span 2;"><small style="display:block; color:#9A6E52; font-family:monospace; font-size:9.5px;">KOORDINAT GPS & ALAMAT</small><strong style="font-family:monospace;">${latStr}° N, ${lngStr}° E</strong> — ${safeAddress}</div>
            </div>
            <p style="font-size:12.5px; line-height:1.55; color:#3B2214; margin-bottom:12px;">
                ${safeUniqueness}
            </p>
            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                <a href="${safeGmapsUrl}" target="_blank" rel="noopener noreferrer" style="background:#24140C; color:#FAF6EE; text-decoration:none; font-family:monospace; font-size:11px; padding:6px 12px;">
                    BUKA GOOGLE MAPS ↗
                </a>
                ${safeSourceUrl !== '#' ? `
                    <a href="${safeSourceUrl}" target="_blank" rel="noopener noreferrer" style="border:1px solid #24140C; color:#24140C; text-decoration:none; font-family:monospace; font-size:11px; padding:6px 12px;">
                        SUMBER REFERENSI ↗
                    </a>
                ` : ''}
            </div>
        `;
    }

    function renderMarkersAndList() {
        markersLayer.clearLayers();
        markerMap = {};

        const filtered = sites.filter((s) => {
            const matchCat = currentCategory === 'all' || s.category === currentCategory;
            const matchSub = currentSubCategory === 'all' ||
                (s.sub_category && s.sub_category.toLowerCase().includes(currentSubCategory.toLowerCase())) ||
                (s.category && s.category.toLowerCase().includes(currentSubCategory.toLowerCase()));
            const q = searchQuery.trim().toLowerCase();
            const matchQ = !q ||
                s.name.toLowerCase().includes(q) ||
                (s.category && s.category.toLowerCase().includes(q)) ||
                (s.sub_category && s.sub_category.toLowerCase().includes(q)) ||
                (s.desa_kelurahan && s.desa_kelurahan.toLowerCase().includes(q)) ||
                (s.kecamatan && s.kecamatan.toLowerCase().includes(q)) ||
                (s.address && s.address.toLowerCase().includes(q));
            return matchCat && matchSub && matchQ;
        });

        const countEl = document.getElementById('sidebarPlacesCount');
        if (countEl) {
            countEl.textContent = `${filtered.length} TITIK LOKASI`;
        }

        const listEl = document.getElementById('sidebarPlacesList');
        if (listEl) {
            listEl.innerHTML = '';
        }

        filtered.forEach((site) => {
            const shortName = site.name.length > 28 ? site.name.substring(0, 26) + '…' : site.name;
            const safeShortName = escapeHtml(shortName);
            const safeCategory = escapeHtml(site.category);
            const safeSub = escapeHtml(site.sub_category || '');
            const safeColor = escapeHtml(site.color_hex);
            const safeName = escapeHtml(site.name);
            const safeDesa = escapeHtml(site.desa_kelurahan);
            const safeKec = escapeHtml(site.kecamatan);
            const safeThumb = sanitizeUrl(site.thumbnail);

            const iconHtml = `
                <div class="arch-map-pin" data-pin-id="${site.id}">
                    <div class="pin-number-badge" style="background:${safeColor};">
                        ${site.marker_number}
                    </div>
                    <div class="pin-zoom-overlay">
                        <strong>${safeShortName}</strong>
                        <small>${safeCategory} • ${safeSub}</small>
                    </div>
                </div>
            `;

            const customIcon = L.divIcon({
                html: iconHtml,
                className: 'custom-div-pin',
                iconSize: [28, 28],
                iconAnchor: [14, 14],
                popupAnchor: [0, -14],
            });

            const marker = L.marker([site.latitude, site.longitude], { icon: customIcon });
            marker.bindPopup(createPopupHtml(site), { maxWidth: 300, closeButton: true });

            // Show popup with thumbnail, desa, kecamatan, and coordinates both on hover and click!
            marker.on('mouseover', function () {
                this.openPopup();
                updateActiveInspector(site);
            });

            marker.on('click', function () {
                this.openPopup();
                updateActiveInspector(site);
                map.flyTo([site.latitude, site.longitude], Math.max(map.getZoom(), 15), { duration: 0.6 });
            });

            marker.addTo(markersLayer);
            markerMap[site.id] = { marker, site };

            // Add row to Petraverso-style right sidebar list
            if (listEl) {
                const row = document.createElement('div');
                row.className = `sidebar-place-item ${site.id === activeSiteId ? 'active' : ''}`;
                row.setAttribute('data-site-id', site.id);
                row.innerHTML = `
                    <img src="${safeThumb}" alt="${safeName}" class="sidebar-place-thumb" loading="lazy" />
                    <div>
                        <div style="display:flex; align-items:center; gap:6px; margin-bottom:2px;">
                            <span style="background:${safeColor}; color:#FAF6EE; font-family:monospace; font-size:10px; padding:1px 5px; font-weight:700;">
                                #${site.marker_number}
                            </span>
                            <span style="font-family:monospace; font-size:10px; color:#75492E; text-transform:uppercase;">
                                ${safeSub || safeCategory}
                            </span>
                        </div>
                        <div style="font-size:13px; font-weight:600; color:#24140C; line-height:1.25; margin-bottom:3px;">
                            ${safeName}
                        </div>
                        <div style="font-size:11px; color:#75492E;">
                            Kel. ${safeDesa}, ${safeKec}
                        </div>
                        <div style="font-family:monospace; font-size:10px; color:#9A6E52; margin-top:2px;">
                            ${Number(site.latitude).toFixed(4)}° N, ${Number(site.longitude).toFixed(4)}° E
                        </div>
                    </div>
                `;
                row.addEventListener('click', () => {
                    updateActiveInspector(site);
                    map.flyTo([site.latitude, site.longitude], 16, { duration: 0.7 });
                    marker.openPopup();
                });
                listEl.appendChild(row);
            }
        });

        if (filtered.length > 0) {
            const currentActive = filtered.find((s) => s.id === activeSiteId) || filtered[0];
            updateActiveInspector(currentActive);
        }
    }

    // Category filter chips
    document.querySelectorAll('.cat-chip[data-map-category]').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.cat-chip[data-map-category]').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            currentCategory = btn.getAttribute('data-map-category');
            currentSubCategory = 'all';
            document.querySelectorAll('.subcat-quick-btn').forEach((sb) => sb.classList.remove('active'));
            renderMarkersAndList();
        });
    });

    // Sub-category quick chips (Tempat Makan, Klenteng, Vihara, dll.)
    document.querySelectorAll('.subcat-quick-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const sub = btn.getAttribute('data-subcat');
            if (currentSubCategory === sub) {
                currentSubCategory = 'all';
                btn.classList.remove('active');
            } else {
                document.querySelectorAll('.subcat-quick-btn').forEach((b) => b.classList.remove('active'));
                currentSubCategory = sub;
                currentCategory = 'all';
                document.querySelectorAll('.cat-chip[data-map-category]').forEach((b) => {
                    b.classList.toggle('active', b.getAttribute('data-map-category') === 'all');
                });
                btn.classList.add('active');
            }
            renderMarkersAndList();
        });
    });

    // Search input
    const searchInput = document.getElementById('mapSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value;
            renderMarkersAndList();
        });
    }

    // Reset Medan & Deli Serdang Lock View button
    const resetBoundsBtn = document.getElementById('resetMedanBoundsBtn');
    if (resetBoundsBtn) {
        resetBoundsBtn.addEventListener('click', () => {
            map.flyTo([3.5952, 98.6780], 12, { duration: 0.7 });
        });
    }

    // Toggle Overlay Labels button
    const toggleLabelsBtn = document.getElementById('toggleOverlayLabelsBtn');
    if (toggleLabelsBtn) {
        toggleLabelsBtn.addEventListener('click', () => {
            forceOverlayLabels = !forceOverlayLabels;
            toggleLabelsBtn.classList.toggle('active', forceOverlayLabels);
            syncZoomOverlayClass();
        });
    }

    // Global helper so radial canvas nodes and catalog table can focus any site or category on the map
    window.applyMapCategoryFilter = (categoryName) => {
        currentCategory = categoryName;
        currentSubCategory = 'all';
        document.querySelectorAll('.cat-chip[data-map-category]').forEach((b) => {
            b.classList.toggle('active', b.getAttribute('data-map-category') === categoryName);
        });
        renderMarkersAndList();
    };

    window.focusSiteOnMap = (siteId) => {
        const mapSection = document.getElementById('peta-etnografis');
        if (mapSection) {
            mapSection.scrollIntoView({ behavior: 'smooth' });
        }
        currentCategory = 'all';
        currentSubCategory = 'all';
        searchQuery = '';
        if (searchInput) searchInput.value = '';
        renderMarkersAndList();

        setTimeout(() => {
            const entry = markerMap[siteId];
            if (entry) {
                map.flyTo([entry.site.latitude, entry.site.longitude], 16, { duration: 0.8 });
                entry.marker.openPopup();
                updateActiveInspector(entry.site);
            }
        }, 350);
    };

    renderMarkersAndList();
}

/* ==========================================================================
   2. GALERI DIGITAL (APARELHO.STUDIO 5:4 B&W THUMBNAIL STRIP -> MULTI-PHOTO)
   ========================================================================== */
function initDigitalGallery(sites) {
    const filmstripEl = document.getElementById('galleryFilmstripBar');
    const mainImgEl = document.getElementById('galleryMainImage');
    const subphotoStripEl = document.getElementById('gallerySubphotoStrip');
    const subtitleNameEl = document.getElementById('galleryLocationSubtitle');
    const subtitleMetaEl = document.getElementById('galleryLocationMeta');
    const groupButtons = document.querySelectorAll('.group-tab-btn[data-gallery-group]');
    const museumSpotlightEl = document.getElementById('museumChinaSpotlight');

    if (!filmstripEl || !mainImgEl) return;

    let currentGroup = 'all';
    let filteredSites = [...sites];
    let activeSite = sites[0] || null;
    let activePhotoIdx = 0;

    function renderActiveStage() {
        if (!activeSite) return;
        const photos = Array.isArray(activeSite.photos) && activeSite.photos.length
            ? activeSite.photos
            : [activeSite.thumbnail];

        const currentPhotoUrl = photos[activePhotoIdx] || photos[0];
        mainImgEl.src = currentPhotoUrl;
        mainImgEl.alt = activeSite.name;

        // Only location subtitle as specified in PDF Page 4 ("tidak usah ada deskripsi tapi ada subtitle nama lokasinya apa")
        if (subtitleNameEl) {
            subtitleNameEl.textContent = activeSite.name;
        }
        if (subtitleMetaEl) {
            subtitleMetaEl.textContent = `${activeSite.gallery_group} • ${activeSite.desa_kelurahan}, ${activeSite.kecamatan} • RASIO 5:4`;
        }

        // Render sub-photos for this location (when thumbnail is clicked, loads its photos)
        if (subphotoStripEl) {
            subphotoStripEl.innerHTML = '';
            photos.forEach((pUrl, idx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `subphoto-item ${idx === activePhotoIdx ? 'active' : ''}`;
                const safePhotoUrl = sanitizeUrl(pUrl);
                const safeName = escapeHtml(activeSite.name);
                btn.innerHTML = `<img src="${safePhotoUrl}" alt="${safeName} - Foto ${idx + 1}" loading="lazy" />`;
                btn.addEventListener('click', () => {
                    activePhotoIdx = idx;
                    renderActiveStage();
                });
                subphotoStripEl.appendChild(btn);
            });
        }

        // Highlight active thumbnail in top filmstrip (turns B&W thumbnail to full original color)
        filmstripEl.querySelectorAll('.filmstrip-thumb-btn').forEach((btn) => {
            const sid = parseInt(btn.getAttribute('data-site-id'), 10);
            btn.classList.toggle('active', sid === activeSite.id);
        });

        // Show or highlight Museum China spotlight
        if (museumSpotlightEl) {
            museumSpotlightEl.style.borderColor =
                (currentGroup === 'Museum China' || activeSite.gallery_group === 'Museum China')
                    ? '#A62424'
                    : '#C6922C';
        }
    }

    function renderFilmstrip() {
        filteredSites = currentGroup === 'all'
            ? [...sites]
            : sites.filter((s) => s.gallery_group === currentGroup);

        filmstripEl.innerHTML = '';

        filteredSites.forEach((site, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `filmstrip-thumb-btn ${activeSite && site.id === activeSite.id ? 'active' : ''}`;
            btn.setAttribute('data-site-id', site.id);
            btn.title = `${site.name} (${site.gallery_group})`;
            const safeThumb = sanitizeUrl(site.thumbnail);
            const safeName = escapeHtml(site.name);
            btn.innerHTML = `
                <img src="${safeThumb}" alt="${safeName}" loading="lazy" />
                <span class="thumb-index">${String(idx + 1).padStart(2, '0')}</span>
            `;
            btn.addEventListener('click', () => {
                activeSite = site;
                activePhotoIdx = 0;
                renderActiveStage();
            });
            filmstripEl.appendChild(btn);
        });

        if (filteredSites.length > 0) {
            activeSite = filteredSites[0];
            activePhotoIdx = 0;
            renderActiveStage();
        }
    }

    groupButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            groupButtons.forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            currentGroup = btn.getAttribute('data-gallery-group');
            renderFilmstrip();
        });
    });

    // Prev / Next navigation buttons on main stage
    document.getElementById('galleryPrevBtn')?.addEventListener('click', () => {
        if (!filteredSites.length || !activeSite) return;
        const curIdx = filteredSites.findIndex((s) => s.id === activeSite.id);
        const prevIdx = (curIdx - 1 + filteredSites.length) % filteredSites.length;
        activeSite = filteredSites[prevIdx];
        activePhotoIdx = 0;
        renderActiveStage();
    });

    document.getElementById('galleryNextBtn')?.addEventListener('click', () => {
        if (!filteredSites.length || !activeSite) return;
        const curIdx = filteredSites.findIndex((s) => s.id === activeSite.id);
        const nextIdx = (curIdx + 1) % filteredSites.length;
        activeSite = filteredSites[nextIdx];
        activePhotoIdx = 0;
        renderActiveStage();
    });

    renderFilmstrip();
}

/* ==========================================================================
   3. VIDEO PLAYER (TOP SELECTOR BAR ABOVE YOUTUBE PLAYER - 7 VIDEOS)
   ========================================================================== */
function initVideoPlayer(videos) {
    const videoBarEl = document.getElementById('videoTopSelectorBar');
    const iframeEl = document.getElementById('videoYoutubeIframe');
    const titleEl = document.getElementById('videoActiveTitle');
    const subtitleEl = document.getElementById('videoActiveSubtitle');
    const sourceLinkEl = document.getElementById('videoYoutubeSourceLink');

    if (!videoBarEl || !iframeEl || !videos.length) return;

    let activeVideo = videos[0];

    function updateVideoStage() {
        const sanitizedEmbed = sanitizeUrl(activeVideo.embed_url);
        if (/^https:\/\/www\.youtube(-nocookie)?\.com\/embed\/[a-zA-Z0-9_-]+/i.test(sanitizedEmbed)) {
            iframeEl.src = sanitizedEmbed;
        }
        if (titleEl) {
            titleEl.textContent = `${activeVideo.code} — ${activeVideo.title}`;
        }
        if (subtitleEl) {
            subtitleEl.textContent = `${activeVideo.location_subtitle} • DURASI ${activeVideo.duration}`;
        }
        if (sourceLinkEl) {
            sourceLinkEl.href = sanitizeUrl(activeVideo.youtube_url);
        }

        videoBarEl.querySelectorAll('.filmstrip-thumb-btn').forEach((btn) => {
            const vid = parseInt(btn.getAttribute('data-video-id'), 10);
            btn.classList.toggle('active', vid === activeVideo.id);
        });
    }

    videoBarEl.querySelectorAll('.filmstrip-thumb-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const vid = parseInt(btn.getAttribute('data-video-id'), 10);
            const found = videos.find((v) => v.id === vid);
            if (!found) return;
            activeVideo = found;
            updateVideoStage();
        });
    });
}

/* ==========================================================================
   CATALOG DIRECTORY FILTER (ALL 71 EXCEL RECORDS)
   ========================================================================== */
function initCatalogTable() {
    const searchEl = document.getElementById('catalogSearchInput');
    const catSelectEl = document.getElementById('catalogCategorySelect');
    const rows = document.querySelectorAll('.catalog-data-row');

    if (!searchEl && !catSelectEl) return;

    function filterTable() {
        const q = (searchEl?.value || '').trim().toLowerCase();
        const cat = catSelectEl?.value || 'all';

        rows.forEach((row) => {
            const rCat = row.getAttribute('data-row-category') || '';
            const rText = row.textContent.toLowerCase();
            const matchCat = cat === 'all' || rCat === cat;
            const matchQ = !q || rText.includes(q);
            row.style.display = matchCat && matchQ ? '' : 'none';
        });
    }

    searchEl?.addEventListener('input', filterTable);
    catSelectEl?.addEventListener('change', filterTable);
}
