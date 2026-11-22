(() => {
    'use strict';
    const init = () => {
        const icons = Joomla.getOptions('com_phocamaps.faicons', []);
        document.querySelectorAll('.phocamaps-fa-picker').forEach(picker => {
            const input = picker.querySelector('input:not([type=search])');
            const filter = picker.querySelector('input[type=search]');
            const grid = picker.querySelector('.phocamaps-fa-grid');
            const status = picker.querySelector('.phocamaps-fa-status');
            const prefix = document.getElementById(picker.dataset.prefix);
            const extra = document.getElementById(picker.dataset.class);
            const preview = picker.querySelector('.phocamaps-fa-preview');
            const marker = preview.querySelector('.awesome-marker');
            const symbol = marker.querySelector('i');
            const color = document.getElementById(picker.dataset.color || '');
            const iconColor = document.getElementById(picker.dataset.iconColor || '');
            const spin = document.getElementById(picker.dataset.spin || '');
            if (!input || !prefix || !extra) return;
            // Same markup as Leaflet.awesome-markers renders in the map
            const valid = /^[A-Za-z0-9_-]+$/;
            const update = () => {
                const name = input.value.trim();
                const iconPrefix = prefix.value.trim();
                preview.style.visibility = valid.test(name) && valid.test(iconPrefix) ? '' : 'hidden';
                marker.className = 'awesome-marker awesome-marker-icon-' + (color && valid.test(color.value) ? color.value : 'blue');
                const classes = extra.value.split(/\s+/).filter(value => valid.test(value));
                classes.push(iconPrefix, name.startsWith(iconPrefix + '-') ? name : iconPrefix + '-' + name);
                if (spin && spin.value === '1') {
                    classes.push('fa-spin');
                }
                const colorValue = iconColor ? iconColor.value.trim() : '';
                symbol.style.color = '';
                if (colorValue === 'white' || colorValue === 'black') {
                    classes.push('icon-' + colorValue);
                } else if (colorValue !== '') {
                    symbol.style.color = colorValue;
                }
                symbol.className = classes.join(' ');
            };
            const render = () => {
                const query = filter.value.trim().toLowerCase();
                const results = icons.filter(icon => icon.name.includes(query));
                grid.replaceChildren();
                status.textContent = Math.min(results.length, 100) + ' / ' + results.length;
                results.slice(0, 100).forEach(icon => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'btn btn-outline-secondary phocamaps-fa-choice';
                    button.title = icon.style + ' fa-' + icon.name;
                    button.setAttribute('aria-label', button.title);
                    const symbol = document.createElement('span');
                    symbol.className = icon.style + ' fa-' + icon.name;
                    symbol.setAttribute('aria-hidden', 'true');
                    const name = document.createElement('span');
                    name.textContent = icon.name;
                    button.append(symbol, name);
                    button.addEventListener('click', () => {
                        input.value = icon.name;
                        prefix.value = 'fa';
                        extra.value = [...extra.value.split(/\s+/).filter(value => value && !['fa-solid', 'fa-brands', 'fa-regular'].includes(value)), icon.style].join(' ');
                        for (const field of [input, prefix, extra]) {
                            field.dispatchEvent(new Event('change', {bubbles: true}));
                        }
                        update();
                    });
                    grid.append(button);
                });
            };
            filter.addEventListener('input', render);
            picker.querySelector('details').addEventListener('toggle', render);
            for (const field of [input, prefix, extra, color, iconColor, spin]) {
                if (field) {
                    field.addEventListener('input', update);
                    field.addEventListener('change', update);
                }
            }
            update();
        });
    };
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, {once: true});
    } else {
        init();
    }
})();