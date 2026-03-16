import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// resources/js/app.js

document.addEventListener('DOMContentLoaded', () => {
    const inputTelefone = document.getElementById('telefone');

    if (inputTelefone) {
        inputTelefone.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); 
            
            if (value.length > 11) value = value.slice(0, 11);
            
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            
            e.target.value = value;
        });
    }
});