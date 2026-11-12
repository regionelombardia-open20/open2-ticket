/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos-ticket
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

$(document).ready(function () {
    $('#create-ticket').on('show.bs.modal', function (event) {
        var link = $(event.relatedTarget); // link that triggered the modal

        var categoryName = link.data('category-name');
        var categoryInstance = link.data('category-instance');

        var modal = $(this);
        modal.find('#modal-title').text(categoryName);

        modal.find('.modal-body').load('/ticket/ticket/create?categoriaId=' + categoryInstance);
        //modal.find('.modal-body').load('/ticket/ticket/create?categoriaId=7');

    })
            .on('hidden.bs.modal', function () {
                // location.reload(); // quando viene chiusa, ricarica la pagina padre
            });

    /*function checkChild() {
     if (opened.closed) {
     clearInterval(timer);
     var modal = $('#create-ticket'); // la modale
     //console.log('lessonUrl',lessonUrl);
     modal.find('.modal-body').load(lessonUrl+'&close=1');
     }
     }*/

    /*  $("body").on('click', ".js-btn-entra", function(event) {
     event.preventDefault();
     var href = $(this).attr('href');
     opened = window.open(href);
     timer = setInterval(checkChild, 500);  // ogni secondo
     });*/
    /*$("body").on('click', '#save_ticket_button', function () {
     console.log("sono qua!!");
     var modal = $('#create-ticket'); // la modale
     modal.find('.modal-body').load($(this).attr('value'));
     });*/


    /*  var form = jQuery('#ticket-form');
   
     var modal = $('#create-ticket'); // la modale
      
    var form = modal.find('#ticket-form');
    
    var url= form.attr('action');

    form.on('beforeSubmit', function (e) {
        e.preventDefault();

      jQuery.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: new FormData(form[0]),
            mimeType: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                alert('ID: ' + data.id + ' someOtherData:' + data.someOtherData);
            }
        });
        return false;
    });*/




});

