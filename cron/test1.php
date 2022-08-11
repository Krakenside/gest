<?php
// the message
$msg = "Je suis juste le meilleur";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("yanneric225@gmail.com","My subject",$msg);
?>