import './bootstrap';

document.addEventListener('alpine:init', () => {
    Alpine.store('toast', {
        items: [],
        push(message, type = 'success', timeout = 4000) {
            const id = Date.now() + Math.random();
            this.items.push({ id, message, type });
            if (timeout) {
                setTimeout(() => this.remove(id), timeout);
            }
        },
        remove(id) {
            this.items = this.items.filter((t) => t.id !== id);
        },
    });

    // Animated count-up for stat tiles: <span x-data="counter(1234)" x-text="display"></span>
    Alpine.data('counter', (target = 0, prefix = '', duration = 800) => ({
        display: prefix + '0',
        init() {
            const start = performance.now();
            const from = 0;
            const to = Number(target) || 0;
            const tick = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const value = Math.round(from + (to - from) * eased);
                this.display = prefix + value.toLocaleString();
                if (progress < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        },
    }));
});

window.pushToast = (message, type = 'success') => {
    document.addEventListener('alpine:initialized', () => {}, { once: true });
    const fire = () => Alpine.store('toast').push(message, type);
    if (window.Alpine && Alpine.store('toast')) fire();
    else window.addEventListener('DOMContentLoaded', fire, { once: true });
};
