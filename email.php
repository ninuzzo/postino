<?php

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

# Manda una email tramite un modulo sul web.

require('email.conf.php');

/* Esempio di dati che vengono dal metodo POST,
   ottenuto con: echo var_export($_POST);

array (
  'nome' => 'Antonio Bonifati',
  'email' => 'antonio.bonifati@gmail.com',
	'copia' => 'on',
  'tel' => '098126247',
  'oggetto' => 'prova',
  'messaggio' => 'un messaggio

di prova',
)
*/

# php.net/filter-input
# http://php.net/manual/en/filter.examples.sanitization.php

# Sanitizza l'indirizzo email del mittente, cioè rimuovi eventuali caratteri
# che non possono far parte dell'email.
$email = filter_var(filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL),
	FILTER_VALIDATE_EMAIL);

# Fa sì che il numero di telefono contenga solo + - e le cifre 0-9.
$tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_NUMBER_INT);

# Togli eventuali tag e caratteri di controllo dal soggetto. In particolare gli 
# accapi possono incasinare il riconoscimento delle intestazioni dell'email.
$oggetto = filter_input(INPUT_POST, 'oggetto', FILTER_SANITIZE_STRING,
	FILTER_FLAG_STRIP_LOW);

# Idem per il nome.
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);

# Togli eventuali tag HTML dal testo del messaggio. Questo perché vogliamo 
# mandare un'email in testo semplice, anche per maggiore sicurezza.
$messaggio = filter_input(INPUT_POST, 'messaggio', FILTER_SANITIZE_STRING);

# In caso le linee del messaggio sono più lunghe di 70 caratteri, spezzale.
# Inoltre ogni linea deve essere separata con un CRLF (\r\n).
$messaggio = wordwrap($messaggio, 70, "\r\n");

/*
  Controlla che i campi obbligatori siano specificati.
  Se un campo non è presente la sua variabile vale NULL, se è invalido vale FALSE.
  Anche se è '0' verrà considerato come non specificato, ma questo non è un problema
  in quanto '0' non ha senso né come nome, né come email, etc.
 */

if ($nome && $email && $oggetto && $messaggio) {
  # Se c'è il numero di telefono, includilo in coda al messaggio.
  if ($tel) {
    $messaggio .= "\r\n\r\ntel. $tel";
  }

  $mittente = "$nome <$email>";

  $intestazioni['From'] = $mittente;
  if (isset($_POST['copia'])) {
    $intestazioni['Cc'] = $mittente;
  }

  # Invia la email e stampa 1 (vero) se tutto va bene,
  # niente (stringa vuota ovvero falso) se qualcosa va storto.

  # Vedi: php.net/mail
  # Questa funzione non supporta SMTP in Unix, quindi dobbiamo usare un'altra libreria.
  # In Windows configura il server SMTP da usare in php.conf
  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || USA_SENDMAIL) {
    echo mail(DESTINATARIO, $oggetto, $messaggio, implode("\r\n", $intestazioni));
  } else {
    $intestazioni['Subject'] = $oggetto;

    include('Mail.php');

    # Crea l'oggetto email usando il metodo Mail::factory
    $oggetto_email =& Mail::factory('smtp', [
      'host' => SMTP_HOST,
      'port' => SMTP_PORT,
      'auth' => TRUE,
      'username' => SMTP_USERNAME,
      'password' => SMTP_PASSWORD,
    ]);

	echo ! PEAR::isError($oggetto_email->send(DESTINATARIO, $intestazioni, $messaggio));
  }
}
?>
