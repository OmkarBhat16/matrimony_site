import './bootstrap';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-datepicker="date-of-birth"]').forEach((element) => {
        flatpickr(element, {
            dateFormat: 'd-m-Y',
            allowInput: true,
            clickOpens: true,
        });
    });
});
