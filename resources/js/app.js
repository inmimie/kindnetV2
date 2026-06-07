import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('theme', {
    value: localStorage.getItem('color-theme') || 
           (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
    
    init() {
        this.apply();
    },

    toggle() {
        this.value = this.value === 'dark' ? 'light' : 'dark';
        localStorage.setItem('color-theme', this.value);
        this.apply();
    },

    apply() {
        if (this.value === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
});

Alpine.start();
