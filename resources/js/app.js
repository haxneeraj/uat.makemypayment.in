import './bootstrap';
import Swal from 'sweetalert2';
import { setupToaser } from './toaser.js';
setupToaser();


window.Swal = Swal;

// Success listener
window.addEventListener('swal:success', event => {
    Swal.fire({
        title: event.detail[0].title,
        text: event.detail[0].text,
        icon: event.detail[0].icon,
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false,
    });
});

// Error listener
window.addEventListener('swal:error', event => {
    console.error('Error event received:', event.detail);
    Swal.fire({
        title: event.detail[0].title,
        text: event.detail[0].text,
        icon: event.detail[0].icon,
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
    });
    console.log('fired Swal for error');
});
