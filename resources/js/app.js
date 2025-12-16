import './bootstrap';
import '../css/app.css';

import Alpine from 'alpinejs';
import anchor from '@alpinejs/anchor';
import collapse from '@alpinejs/collapse';

import IMask from 'imask';

window.Alpine = Alpine;

Alpine.plugin(anchor);
Alpine.plugin(collapse);

Alpine.start();

// твоя маска телефона остаётся как была
function attachPhoneMask(input) {
    const mask = IMask(input, { mask: '+{7} (000) 000 00 00', lazy: false });

    if (input.value) mask.value = input.value;

    input.form?.addEventListener('submit', () => {
        let raw = mask.unmaskedValue;
        if (raw.length === 10) raw = '7' + raw;
        input.value = raw;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[data-phone-mask]').forEach(attachPhoneMask);
});
