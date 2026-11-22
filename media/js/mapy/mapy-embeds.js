(() => {
    'use strict';
    function protectEmbeds() {
        const config = Joomla.getOptions('phocamaps.mapy.embed');
        if (!config) return;
        document.querySelectorAll('iframe').forEach(frame => {
            const host = new URL(frame.src, document.baseURI).hostname;
            if (!['frame.mapy.cz', 'frame.mapy.com'].includes(host) || frame.closest('.phocamaps-map-embed')) return;
            const wrapper = document.createElement('span');
            wrapper.className = 'phocamaps-map-embed';
            const activate = document.createElement('button');
            activate.type = 'button';
            activate.className = 'phocamaps-map-embed__activate';
            const hint = document.createElement('span');
            hint.textContent = config.hold;
            activate.append(hint);
            const lock = document.createElement('button');
            lock.type = 'button';
            lock.className = 'phocamaps-map-embed__lock';
            lock.textContent = config.lock;
            lock.hidden = true;
            const tabIndex = frame.getAttribute('tabindex');
            frame.title ||= 'Mapy.com';
            frame.tabIndex = -1;
            frame.before(wrapper);
            wrapper.append(frame, activate, lock);
            let timer;
            let origin;
            const cancel = () => { clearTimeout(timer); origin = null; };
            const setActive = active => {
                cancel();
                wrapper.classList.toggle('is-active', active);
                activate.hidden = active;
                lock.hidden = !active;
                frame.tabIndex = active ? (tabIndex === null ? 0 : Number(tabIndex)) : -1;
            };
            activate.addEventListener('pointerdown', event => {
                if (event.button !== 0 || !event.isPrimary) return;
                cancel();
                origin = {x: event.clientX, y: event.clientY};
                timer = setTimeout(() => setActive(true), 500);
            });
            activate.addEventListener('pointermove', event => {
                if (origin && Math.hypot(event.clientX - origin.x, event.clientY - origin.y) > 10) cancel();
            });
            ['pointerup', 'pointercancel', 'pointerleave'].forEach(name => activate.addEventListener(name, cancel));
            activate.addEventListener('contextmenu', event => event.preventDefault());
            activate.addEventListener('click', event => {
                if (event.detail === 0) {
                    setActive(true);
                    lock.focus();
                }
            });
            lock.addEventListener('click', () => { setActive(false); activate.focus(); });
            wrapper.addEventListener('keydown', event => {
                if (event.key === 'Escape') {
                    setActive(false);
                    activate.focus();
                }
            });
            wrapper.addEventListener('pointerleave', event => {
                if (event.pointerType === 'mouse') setActive(false);
            });
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', protectEmbeds, {once: true});
    } else {
        protectEmbeds();
    }
})();
