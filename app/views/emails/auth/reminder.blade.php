<!DOCTYPE html>
<html lang="it-IT">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Modifica della password</h2>

        <div>
            Per modificare la tua password, visita il seguente indirizzo: {{ URL::to('password/reset', array($token)) }}.<br/>
            Per maggiore sicurezza, il link indicato scadrà in {{ Config::get('auth.reminder.expire', 60) }} minuti.
        </div>
    </body>
</html>
