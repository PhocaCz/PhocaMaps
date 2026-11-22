(() => {
    'use strict';
    const element = (tag, text, className) => {
        const node = document.createElement(tag);
        if (text) node.textContent = text;
        if (className) node.className = className;
        return node;
    };
    const tiles = (mapset, apiKey) => {
        // Retina tiles exist only for basic and outdoor map sets
        const size = L.Browser.retina && (mapset === 'basic' || mapset === 'outdoor') ? '256@2x' : '256';
        return 'https://api.mapy.com/v1/maptiles/' + mapset + '/' + size + '/{z}/{x}/{y}?apikey=' + encodeURIComponent(apiKey);
    };
    const maxZoom = mapset => mapset === 'aerial' ? 20 : 19;

    window.PhocaMapsMapy = {
        create(map, options, config) {
            const t = config.text;
            const active = name => options[name] === '1';
            let mapset = options.mapset;
            let names = active('names');
            let disposed = false;
            const listeners = [];
            const objectUrls = new Set();
            const listen = (name, callback) => {
                map.on(name, callback);
                listeners.push([name, callback]);
            };

            let errorControl;
            const tileError = () => {
                if (errorControl) return;
                console.error('Phoca Maps - Mapy.com: tile request failed, check API key, allowed domains and quota.');
                errorControl = L.control({position: 'topright'});
                errorControl.onAdd = () => {
                    const node = element('div', t.tile_error, 'phocamaps-mapy-error');
                    node.setAttribute('role', 'alert');
                    L.DomEvent.disableClickPropagation(node);
                    return node;
                };
                errorControl.addTo(map);
            };

            // Attribution and logo are mandatory, see https://developer.mapy.com/rest-api/atributovani/
            const base = L.tileLayer(tiles(mapset, config.apiKey), {
                minZoom: 0, maxZoom: maxZoom(mapset), referrerPolicy: 'strict-origin-when-cross-origin',
                attribution: '<a href="https://api.mapy.com/copyright" target="_blank" rel="noopener">&copy; Seznam.cz a.s. a další</a>'
            }).on('tileerror', tileError).addTo(map);
            const overlay = L.tileLayer(tiles('names-overlay', config.apiKey), {
                minZoom: 0, maxZoom: 20, zIndex: 2, referrerPolicy: 'strict-origin-when-cross-origin'
            }).on('tileerror', tileError);
            const refreshNames = () => {
                if (mapset === 'aerial' && names) {
                    if (!map.hasLayer(overlay)) overlay.addTo(map);
                } else if (map.hasLayer(overlay)) {
                    map.removeLayer(overlay);
                }
            };
            const setMapset = value => {
                mapset = value;
                map.removeLayer(base);
                refreshNames();
                base.options.maxZoom = maxZoom(value);
                base.setUrl(tiles(value, config.apiKey), true);
                base.addTo(map);
            };
            refreshNames();

            if (!map.attributionControl) L.control.attribution().addTo(map);
            const logo = L.control({position: 'bottomleft'});
            logo.onAdd = () => {
                const link = element('a', '', 'phocamaps-mapy-logo');
                link.href = 'https://mapy.com/';
                link.target = '_blank';
                link.rel = 'noopener';
                const image = element('img');
                image.src = 'https://api.mapy.com/img/api/logo.svg';
                image.alt = 'Mapy.com';
                image.height = 30;
                link.append(image);
                L.DomEvent.disableClickPropagation(link);
                return link;
            };
            logo.addTo(map);

            if (!['switcher', 'search', 'routing', 'point_info', 'panorama', 'static_map'].some(active)) return base;

            const revoke = url => {
                URL.revokeObjectURL(url);
                objectUrls.delete(url);
            };
            const request = async (endpoint, params, signal) => {
                const url = new URL('https://api.mapy.com/v1/' + endpoint);
                Object.entries(params).forEach(([name, value]) => {
                    for (const part of (Array.isArray(value) ? value : [value])) url.searchParams.append(name, part);
                });
                const response = await fetch(url, {
                    headers: {'X-Mapy-Api-Key': config.apiKey}, signal, cache: 'no-store',
                    referrerPolicy: 'strict-origin-when-cross-origin'
                });
                if (!response.ok) throw new Error(t.request_error + ' (HTTP ' + response.status + ')');
                return response;
            };
            const api = async (endpoint, params, signal) => (await request(endpoint, params, signal)).json();
            const previewImage = async (endpoint, params, popup, title, signal) => {
                const response = await request(endpoint, {...params, width: 300, height: 180}, signal);
                const image = element('img');
                image.alt = title;
                image.width = 300;
                image.height = 180;
                const blob = await response.blob();
                if (disposed || !popup.isConnected) return;
                const url = URL.createObjectURL(blob);
                objectUrls.add(url);
                image.addEventListener('load', () => revoke(url), {once: true});
                image.addEventListener('error', () => {
                    revoke(url);
                    image.remove();
                    popup.append(element('div', t.request_error));
                }, {once: true});
                image.src = url;
                popup.append(image);
            };

            const panel = L.control({position: 'topright'});
            const root = element('details', '', 'phocamaps-mapy-tools');
            const resize = () => {
                root.style.setProperty('--phocamaps-mapy-height', Math.max(80, map.getSize().y - 100) + 'px');
                root.style.setProperty('--phocamaps-mapy-width', map.getSize().x + 'px');
            };
            listen('resize', resize);
            resize();
            root.append(element('summary', 'Mapy.com'));
            const body = element('div', '', 'phocamaps-mapy-tools__body');
            root.append(body);
            panel.onAdd = () => {
                L.DomEvent.disableClickPropagation(root);
                L.DomEvent.disableScrollPropagation(root);
                return root;
            };
            panel.addTo(map);
            const status = element('div', '', 'phocamaps-mapy-status');
            status.setAttribute('role', 'status');
            const report = error => {
                if (error.name !== 'AbortError') {
                    status.textContent = error.message || t.request_error;
                    console.error('Phoca Maps - Mapy.com: API operation failed.');
                }
            };
            const button = (text, action) => {
                const node = element('button', text);
                node.type = 'button';
                node.addEventListener('click', action);
                return node;
            };

            if (active('switcher')) {
                const label = element('label', t.mapset);
                const select = element('select');
                select.setAttribute('aria-label', label.textContent);
                for (const name of ['basic', 'outdoor', 'winter', 'aerial']) {
                    const option = element('option', t[name]);
                    option.value = name;
                    select.append(option);
                }
                select.value = mapset;
                const namesLabel = element('label', '', 'phocamaps-mapy-check');
                const checkbox = element('input');
                checkbox.type = 'checkbox';
                checkbox.checked = names;
                namesLabel.append(checkbox, document.createTextNode(t.names));
                namesLabel.hidden = mapset !== 'aerial';
                select.addEventListener('change', () => {
                    namesLabel.hidden = select.value !== 'aerial';
                    setMapset(select.value);
                });
                checkbox.addEventListener('change', () => {
                    names = checkbox.checked;
                    refreshNames();
                });
                label.append(select);
                body.append(label, namesLabel);
            }
            let foundMarker;
            let searchController;
            let searchSequence = 0;
            let suggestTimer;
            const results = element('div', '', 'phocamaps-mapy-results');
            if (active('search')) {
                const form = element('form');
                const input = element('input');
                input.type = 'search';
                input.maxLength = 150;
                input.required = true;
                input.placeholder = t.search;
                input.setAttribute('aria-label', t.search);
                const search = async endpoint => {
                    clearTimeout(suggestTimer);
                    searchController?.abort();
                    searchController = new AbortController();
                    const sequence = ++searchSequence;
                    results.replaceChildren();
                    status.textContent = t.loading;
                    try {
                        const center = map.getCenter();
                        const data = await api(endpoint, {query: input.value.trim(), lang: config.lang,
                            limit: 5, preferNear: center.lng + ',' + center.lat}, searchController.signal);
                        if (sequence !== searchSequence) return;
                        if (!Array.isArray(data.items)) throw new Error(t.request_error);
                        status.textContent = data.items.length ? '' : t.empty;
                        for (const item of data.items) {
                            if (!Number.isFinite(item.position?.lat) || !Number.isFinite(item.position?.lon)) {
                                throw new Error(t.request_error);
                            }
                            results.append(button([item.name, item.location].filter(Boolean).join(' — '), () => {
                                const point = [item.position.lat, item.position.lon];
                                if (foundMarker) map.removeLayer(foundMarker);
                                const popup = element('div', item.name);
                                popup.append(element('div', item.location), element('small', t.live_only));
                                foundMarker = L.marker(point).addTo(map).bindPopup(popup).openPopup();
                                if (item.bbox?.length === 4) {
                                    map.fitBounds([[item.bbox[1], item.bbox[0]], [item.bbox[3], item.bbox[2]]], {maxZoom: 17});
                                } else {
                                    map.setView(point, 16);
                                }
                            }));
                        }
                    } catch (error) {
                        if (sequence === searchSequence) report(error);
                    }
                };
                input.addEventListener('input', () => {
                    searchSequence++;
                    searchController?.abort();
                    clearTimeout(suggestTimer);
                    results.replaceChildren();
                    status.textContent = '';
                    if (active('suggest') && input.value.trim().length >= 3) {
                        suggestTimer = setTimeout(() => search('suggest'), 400);
                    }
                });
                const submit = element('button', t.find);
                submit.type = 'submit';
                form.append(input, submit);
                form.addEventListener('submit', event => {
                    event.preventDefault();
                    search('geocode');
                });
                body.append(form, results);
            }

            let routeMode = '';
            let routeStart;
            let routeEnd;
            let routeLine;
            let routeController;
            let matrixController;
            let routeSequence = 0;
            let inspect = false;
            let inspectButton;
            if (active('routing')) {
                const section = element('details');
                section.append(element('summary', t.route));
                const profile = element('select');
                profile.setAttribute('aria-label', t.route);
                for (const name of ['car_fast', 'car_short', 'foot_fast', 'foot_hiking', 'bike_road', 'bike_mountain']) {
                    const option = element('option', t[name]);
                    option.value = name;
                    profile.append(option);
                }
                const clearRoute = () => {
                    routeSequence++;
                    routeController?.abort();
                    matrixController?.abort();
                    if (routeLine) map.removeLayer(routeLine);
                    routeLine = null;
                };
                const start = button(t.route_start, () => {
                    inspect = false;
                    inspectButton?.setAttribute('aria-pressed', 'false');
                    routeMode = 'start';
                    status.textContent = t.route_hint;
                });
                const end = button(t.route_end, () => {
                    inspect = false;
                    inspectButton?.setAttribute('aria-pressed', 'false');
                    routeMode = 'end';
                    status.textContent = t.route_hint;
                });
                const matrix = button(t.matrix, async () => {
                    matrixController?.abort();
                    matrixController = new AbortController();
                    const sequence = routeSequence;
                    status.textContent = t.loading;
                    try {
                        const data = await api('routing/matrix-m', {starts: [routeStart.toLngLat(), routeEnd.toLngLat()],
                            routeType: profile.value}, matrixController.signal);
                        if (sequence !== routeSequence) return;
                        const outward = data.matrix?.[0]?.[1];
                        const inward = data.matrix?.[1]?.[0];
                        if (![outward?.length, outward?.duration, inward?.length, inward?.duration]
                            .every(value => Number.isFinite(value) && value >= 0)) throw new Error(t.request_error);
                        status.textContent = t.matrix + ': '
                            + (outward.length / 1000).toFixed(1) + ' km / ' + Math.ceil(outward.duration / 60) + ' min'
                            + ' ↔ ' + (inward.length / 1000).toFixed(1) + ' km / ' + Math.ceil(inward.duration / 60) + ' min';
                    } catch (error) {
                        if (sequence === routeSequence) report(error);
                    }
                });
                matrix.disabled = true;
                const calculate = async () => {
                    clearRoute();
                    matrix.disabled = !routeStart || !routeEnd;
                    if (!routeStart || !routeEnd) return;
                    const sequence = routeSequence;
                    routeController = new AbortController();
                    status.textContent = t.loading;
                    try {
                        const data = await api('routing/route', {start: routeStart.toLngLat(),
                            end: routeEnd.toLngLat(), routeType: profile.value, format: 'geojson'}, routeController.signal);
                        if (sequence !== routeSequence) return;
                        if (!data.geometry || !Number.isFinite(data.length) || !Number.isFinite(data.duration)) {
                            throw new Error(t.request_error);
                        }
                        routeLine = L.geoJSON(data.geometry, {style: {color: '#145f9e', weight: 5}}).addTo(map);
                        map.fitBounds(routeLine.getBounds(), {padding: [25, 25]});
                        status.textContent = (data.length / 1000).toFixed(1) + ' km / ' + Math.ceil(data.duration / 60) + ' min';
                    } catch (error) {
                        if (sequence === routeSequence) report(error);
                    }
                };
                const routePoint = (point, label) => {
                    const marker = L.circleMarker(point, {radius: 8, color: '#19374b', fillOpacity: 1}).addTo(map);
                    marker.bindTooltip(label);
                    marker.toLngLat = () => marker.getLatLng().lng + ',' + marker.getLatLng().lat;
                    return marker;
                };
                listen('click', event => {
                    if (!routeMode) return;
                    if (routeMode === 'start') {
                        if (routeStart) map.removeLayer(routeStart);
                        routeStart = routePoint(event.latlng, t.route_start);
                        start.setAttribute('aria-pressed', 'true');
                    } else {
                        if (routeEnd) map.removeLayer(routeEnd);
                        routeEnd = routePoint(event.latlng, t.route_end);
                        end.setAttribute('aria-pressed', 'true');
                    }
                    routeMode = '';
                    status.textContent = '';
                    calculate();
                });
                profile.addEventListener('change', calculate);
                section.append(profile, start, end, matrix, button(t.route_clear, () => {
                    clearRoute();
                    matrix.disabled = true;
                    for (const marker of [routeStart, routeEnd]) if (marker) map.removeLayer(marker);
                    routeStart = routeEnd = null;
                    routeMode = '';
                    start.setAttribute('aria-pressed', 'false');
                    end.setAttribute('aria-pressed', 'false');
                    status.textContent = '';
                }));
                body.append(section);
            }
            let pointController;
            let staticController;
            let pointSequence = 0;
            if (active('point_info') || active('panorama')) {
                inspectButton = button(t.point_info, () => {
                    inspect = !inspect;
                    routeMode = '';
                    inspectButton.setAttribute('aria-pressed', String(inspect));
                    status.textContent = inspect ? t.route_hint : '';
                });
                inspectButton.setAttribute('aria-pressed', 'false');
                body.append(inspectButton);
                listen('click', async event => {
                    if (!inspect || routeMode) return;
                    pointController?.abort();
                    pointController = new AbortController();
                    const sequence = ++pointSequence;
                    const point = event.latlng;
                    const popup = element('div', point.lat.toFixed(6) + ', ' + point.lng.toFixed(6), 'phocamaps-mapy-point');
                    const loading = element('div', t.loading);
                    popup.append(loading);
                    L.popup().setLatLng(point).setContent(popup).openOn(map);
                    const jobs = [];
                    if (active('point_info')) {
                        jobs.push(api('rgeocode', {lon: point.lng, lat: point.lat, lang: config.lang}, pointController.signal)
                            .then(data => {
                                if (!Array.isArray(data.items)) throw new Error(t.request_error);
                                popup.append(element('div', data.items.map(item => item.name).join(', ')));
                            }));
                        jobs.push(api('elevation', {positions: point.lng + ',' + point.lat}, pointController.signal)
                            .then(data => {
                                const elevation = data.items?.[0]?.elevation;
                                if (!Number.isFinite(elevation) || elevation === -100000) throw new Error(t.request_error);
                                popup.append(element('div', t.elevation + ': ' + Math.round(elevation) + ' m'));
                            }));
                        jobs.push(api('timezone/coordinate', {lon: point.lng, lat: point.lat}, pointController.signal)
                            .then(data => {
                                const zone = data.timezone;
                                if (typeof zone?.timezoneName !== 'string') throw new Error(t.request_error);
                                popup.append(element('div', t.timezone + ': ' + zone.timezoneName));
                                if (typeof zone.currentLocalTime === 'string') {
                                    popup.append(element('div', zone.currentLocalTime.replace('T', ' ')));
                                }
                            }));
                    }
                    if (active('panorama')) {
                        const panoButton = button(t.panorama, async () => {
                            panoButton.disabled = true;
                            try {
                                await previewImage('static/pano', {lon: point.lng, lat: point.lat}, popup, t.panorama, pointController.signal);
                            } catch (error) {
                                if (error.name !== 'AbortError') popup.append(element('div', error.message || t.request_error));
                                panoButton.disabled = false;
                                report(error);
                            }
                        });
                        popup.append(panoButton);
                    }
                    const settled = await Promise.allSettled(jobs);
                    if (sequence !== pointSequence) return;
                    loading.remove();
                    for (const result of settled) {
                        if (result.status === 'rejected' && result.reason.name !== 'AbortError') {
                            popup.append(element('div', result.reason.message || t.request_error));
                            report(result.reason);
                        }
                    }
                });
            }
            if (active('static_map')) {
                body.append(button(t.static_map, async () => {
                    staticController?.abort();
                    staticController = new AbortController();
                    const center = map.getCenter();
                    const popup = element('div', t.loading, 'phocamaps-mapy-point');
                    L.popup().setLatLng(center).setContent(popup).openOn(map);
                    try {
                        await previewImage('static/map', {lon: center.lng, lat: center.lat,
                            zoom: Math.min(20, map.getZoom()), mapset: mapset}, popup, t.static_map, staticController.signal);
                        popup.firstChild?.remove();
                    } catch (error) {
                        if (error.name !== 'AbortError') popup.textContent = error.message || t.request_error;
                        report(error);
                    }
                }));
            }
            body.append(status);
            if (['search', 'routing', 'point_info', 'panorama', 'static_map'].some(active)) {
                body.append(element('small', t.live_only));
            }
            const keyboardPoint = event => {
                if (event.key === 'Enter' && event.target === map.getContainer() && (routeMode || inspect)) {
                    event.preventDefault();
                    map.fire('click', {latlng: map.getCenter()});
                }
            };
            map.getContainer().addEventListener('keydown', keyboardPoint);
            map.once('unload', () => {
                disposed = true;
                map.getContainer().removeEventListener('keydown', keyboardPoint);
                for (const controller of [searchController, routeController, matrixController, pointController, staticController]) {
                    controller?.abort();
                }
                clearTimeout(suggestTimer);
                for (const [name, callback] of listeners) map.off(name, callback);
                for (const url of objectUrls) revoke(url);
            });
            return base;
        }
    };
})();