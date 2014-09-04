/*
Copyright 2014 Antonio Bonifati

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Ispirato dalla documentazione: http://api.jquery.com/jquery.post/
// Collega un gestore dell'invio del form.
$('#form_contatto').submit(function(evento) {
  // Annulla la normale sottomissione del form.
  evento.preventDefault();

  var $form = $(this),
    // Prendi tutti i valori degli elementi del form.
    stringa_dati = $('#form_contatto').serialize(),
    url = $form.attr('action');

  // Manda i dati usando il metodo post.
  var invio = $.post(url, stringa_dati);

  // Indica qual è stato il risultato nel tag div.
  invio.done(function(data) {
    // DEBUG
    //alert(data);

    $('#risultato').empty().append(data ? 'Messaggio inviato'
      : 'Si è verificato un errore. Nessun messaggio inviato');
  });
});

// Cambia il cursore in una clessidra mentre si fa una richiesta.
$(document).ajaxStart(function () {
  $(document.body).css({'cursor': 'wait'})
});
$(document).ajaxComplete(function () {
  $(document.body).css({'cursor': 'default'})
});
