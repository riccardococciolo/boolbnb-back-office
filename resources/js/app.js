import './bootstrap';

import "~resources/scss/app.scss";
import * as bootstrap from 'bootstrap';
import.meta.glob([
    '../img/**'
]);

const buttons = document.querySelectorAll('.btn-delete');

buttons.forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault();

        const deleteModal = new bootstrap.Modal('#delete-modal');

        const title = button.getAttribute('data-title');
        document.getElementById('title-to-delete').innerHTML = title;
        
        document.getElementById('action-delete').addEventListener('click', () => {
            button.parentElement.submit();
        });

        deleteModal.show() 
    });
    
})
