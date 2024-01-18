# Boolbnb_6

BoolBnB è una web app che permette di trovare e gestire l’affitto di appartamenti.
I proprietari di appartamenti, registrandosi a BoolBnB, possono inserire le informazioni delle
loro proprietà e decidere se sponsorizzarle per avere una posizione evidenziata nelle
ricerche e in home page.
Gli utenti interessati ad affittare, senza registrazione, possono cercare e visualizzare gli
appartamenti. Una volta scelto l’appartamento di interesse, possono inviare un messaggio al
proprietario tramite la piattaforma, per chiedere maggiori dettagli.

## Pseudo-codice

  1) A progetto aperto aggiungere dati ad user
      
    - Models 
    - Migrations 
    - Form di registrazione con nuovi dati

  2) Fare migrations
    
    - Apartments
    - Services
    - Views
    - Messages
    - Sponsors

  3) Fare Model degli elementi sopra

  4) Generare i config con dei dati 

  5) Fare i seeders degli elementi sopra

Cose Da Ricordare per sistemare successivamente

Ricordarsi di togliere il require su users nella migrations

Ricordarsi di sistemare l'errore sulla conferma password

## To do:
1. Validazione client-side + server-side dati creazione/modifica apartment + gestione errori
2. aggiungere funzionalità visibilità appartamento
3. (DONE) fix edit, dati nei propri campi 
4. (DONE) fix edit, aggiornamento coordinate
5. (DONE) Sostituire chiave indice con chiave costante (es. [0] = [lat])
        $form_data_apartment['lat'] = $form_data_apartment['position_address'][0][0];
        $form_data_apartment['lng'] = $form_data_apartment['position_address'][0][1];

6. dobbiamo aggiungere nella registrazione il minimo 18+ che si possa anche fermare come limite di un minimo di anno passato
anche controllo della coincidenza della password (FAtto)
7. messaggi di errore in tutte nella pagina (back-end)
8. sistemare l'old nell'inserimento dell'appartamento
9. implementare la ricerca per raggio nel front-end 
10. la ricerca deve essere fatta back-end
11. dettaglio di principio del pagamento delle sponsorizzazioni 
12. autocompletamento nel create-edit sull'indirizzo 
13. aggiustare il form del create-edit 



## Collaboratori:
 1. Eddy
 2. Alessandro P.
 3. Alessandro D.
 4. Vincenzo
 5. Veronica
