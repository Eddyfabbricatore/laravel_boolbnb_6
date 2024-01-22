<p>
    Hai ricevuto un nuovo messaggio <br>
    nome: {{$message?->full_name}}
    email: {{$message->email}}
    Messaggio: <br>
        {{$message->message}}
    Data D'invio Messaggio
        {{$message->date}}
</p>
