import $ from 'jquery';
window.$ = window.jQuery = $;

import './bootstrap';
import Alpine from 'alpinejs';

import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import DataTable from 'datatables.net-dt';
import Swal from 'sweetalert2';


window.Alpine = Alpine;
Alpine.start();

window.Swal = Swal;

window.showToast = function (type, message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
};


$(document).ready(function () {
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        const type = flashMessage.dataset.type;
        const message = flashMessage.dataset.message;

        if (type && message) {
            window.showToast(type, message);
        }
    }else {
        console.log('Flash message not found.');
    }

    // DataTable
    $('#productsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });
    $('#harvestsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });
    $('#stocksTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });
    $('#warehousesTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });
    $('#transactionsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });
    $('#historiesTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });

});

