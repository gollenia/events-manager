 import './events-manager-pro-admin.css'

 document.addEventListener('DOMContentLoaded', () => {
     document.body.addEventListener('click', event => {
         if(event.target.classList.contains("em-bookings-approve-offline") && !confirm(EM.offline_confirm)) {
             event.stopPropagation();
             event.stopImmediatePropagation();
             event.preventDefault();
             return false;
         }
     });

     document.body.addEventListener('click', event => {
         if(event.target.classList.contains("em-transaction-delete")) {
             const el = event.target;
             if( !confirm(EM.transaction_delete) ){ return false; }
             const url = em_ajaxify( el.attr('href'));
             let td = el.parents('td').first();
             td.html(EM.txt_loading);
             td.load( url );
             return false;
         }
     })
 });