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

## Collaboratori:
 1. Eddy
 2. Alessandro P.
 3. Alessandro D.
 4. Vincenzo
 5. Veronica

 

Cose Da Ricordare per sistemare successivamente

Ricordarsi di togliere il require su users nella migrations

Ricordarsi di sistemare l'errore sulla conferma password

---
1. Validazione client-side + server-side dati creazione/modifica apartment + gestione errori
2. aggiungere funzionalità visibilità appartamento
3. fix edit, dati nei propri campi (FATTO)
4. fix edit, aggiornamento coordinate
5. Sostituire chiave indice con chiave costante (es. [0] = [lat])
        $form_data_apartment['lat'] = $form_data_apartment['position_address'][0][0];
        $form_data_apartment['lng'] = $form_data_apartment['position_address'][0][1];
