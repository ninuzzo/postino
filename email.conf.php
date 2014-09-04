<?php

/*
 * Cambia qui il destinatario del messaggio e i dati di connessione al server 
 * di posta.
 *
 * Per usare google bisognare abilitare il supporto per applicazioni esterne 
 * meno sicure qui:
 *
 * https://www.google.com/settings/security/lesssecureapps
 */

const
  DESTINATARIO = 'Antonio Bonifati <antonio.bonifati@gmail.com>',

	# Valida solo per Unix. Se impostata non occorre definire valori giusti
	# per le costanti SMTP_* sotto.
  USA_SENDMAIL = FALSE,

  SMTP_HOST = 'ssl://smtp.gmail.com',
  SMTP_PORT = '465',
  SMTP_USERNAME = 'IL_TUO_USERNAME@gmail.com',
  SMTP_PASSWORD = 'LA_TUA_PASSWORD';

?>
