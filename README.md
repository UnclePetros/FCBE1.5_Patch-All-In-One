# FCBE 1.5 Patch All-In-One

Insieme di funzionalità aggiuntive e migliorie grafiche apportate al software PHP *Fantacalciobazar Evolution ver. 1.5*, content management system (CMS) per la gestione online del fantacalcio.

Sito di riferimento: http://fantacalciobazar.altervista.org/
Topic di riferimento: http://fantacalciobazar.altervista.org/showthread.php?tid=4602

## Descrizione
La patch aggiunge al software le seguenti funzionalità:
* Possibilità di configurare le opzioni di calcolo dei punteggi negli scontri diretti, dalla pagina di gestione di un torneo
* Persistenza della classifica di giornata su file, senza bisogno di ricalcolarla ad ogni richiesta di visualizzazione
* Layout a blocchi per la Home Page utente
* Gestione rapida delle formazioni (Inserimento/Modifica/Cancellazione)
* Personalizzazione grafica delle seguenti pagine/funzionalità:
  * Calendario partite
  * Home page utente (Bacheca)
  * Tabellini
  * Formazioni Attuali
  * Invio Formazioni

## Installazione

Assicurarsi che la patch sia compatibile con la versioen dello script FCBE 1.5 installata.
Attualmente la patch è compatibile con FCBE 1.5 rev.42 assembla.
Decomprimenre il file zip della patch, e sovrascrivere il contenuto a quello attuale del vostro software FCBE.

Aprire il file dati_gen.php, presente nella cartella dati del vostro script, e sovrascrivere con quanto segue la corrispondente sezione del file:

'''php
######################## PATCH ALL-IN-ONE ##################################
$sfondo_tab						=	'#FFFFFF';
$sfondo_tab1						=	'#FFFFFF';
$sfondo_tab2						=	'042e7b';
$sfondo_tab3						=	'#B2C7E9';
$bgtabtitolari					=	'#FCE0E0';
$bgtabpanchinari					=	'#FBC8C8';
$colore_riga_alt					=	'#DDDDDD';
$carattere_tipo					=	'Tahoma';
$carattere_size					=	'15px';
$carattere_colore				=	'#000000';
$carattere_colore_chiaro			=	'#18045C';
######################## PATCH ALL-IN-ONE ##################################
'''

Buon divertimento!

## Changelog

##### Ver. 2.97
- Visualizzazione di parte della miniatura del logo per ciascuna squadra nel box giornata corrente

##### Ver. 2.96
- Aggiunto il salvataggio di una miniatura del logo caricato
- Visualizzazione di parte della miniatura del logo per ciascuna squadra nel box prossimo turno
- Visualizzazione di parte dei loghi delle squadre di serie A nella pagina di gestione formazione, per ogni calciatore in rosa
- Regolata larghezza colonne nella bacheca

##### Ver. 2.93
- Introdotta temporaneamente la possibilità di visualizzare le formazioni avversarie

##### Ver. 2.92
- Corretto bug sul ricalcolo della classifica, quando vengono modificati i parametri di configurazione di un torneo

##### Ver. 2.91
- Corretto bug su timer asta

##### Ver. 2.90
- Sistemata la gestione panchina fissa

##### Ver. 2.8
- Corretto bug su identificazione tipo di sostituzione per schema

##### Ver. 2.7
- Aggiunta gestione sostituzioni per schema nella pagina di "Gestione Formazione"
- Corretto bug su chiusura file formazione dopo salvataggio

##### Ver. 2.7 (solo ver. 1.6 di FCBE)
- risolto disallineamenti su variabili di configurazione dei tornei

##### Ver. 2.6
- risolto bug visualizzazione shoutbox in "Bacheca"

##### Ver. 2.5
- integrata la patch "Gestione rapida formazioni"
- aggiunto logica per la gestine del timeout su chiamate a siti, nei file squadra.php, gestione_formazione.php e a_gestione.php
- risolto problema con ridimensionamento pagina mercato in Chrome
- risolto bug con posizionamento sezione "TOP 11 di giornata"

##### Ver. 2.4
- Aggiunta la chat "Shoutbox" in bacheca!
- Corretto bug su "Calendario Serie A" in menù laterale

##### Ver. 2.3
- Corretto bug sul salvataggio dati torneo

##### Ver. 2.2
- Corretto bug sulla visualizzazione delle offerte in "Bacheca"

##### Ver. 2.1
- aggiunto controllo per l'attivazione delle statistiche squadre, solo a campionato iniziato
- aggiunti angoli arrotondati ad alcuni box in home page

##### Ver. 2.0
- Aggiornato, in generale il comparto grafico, con immagini nuove, effetti css, e piccole modifiche ai colori    
- Aggiunto spazio logo di lega personalizzabile
- Cambiato l'aspetto grafico del menù laterale "Statistiche" per adattarlo al layout corrente
- Cambiato il layout delle pagine "Televideo", "Indisponibili" e "Probabili formazioni", ed anche la modalità di apertura (adesso si aprono in un popup, per essere meglio fruibili)
- Aggiunto il logo squadra e la posizione in classifica nella "Bacheca"
- Migliorata la presentazione del fantacampo in "Bacheca"
- Modificata la grafica della Home page iniziale, per adattarla al layout corrente
- Migliorate le funzionalità di tutte le pagine di statistiche (tramite l'utilizzo di schede, e controlli in pagina): manca solo il controllo nella pagina dei voti per visualizzare i voti delle giornate precedenti quella corrente (lo aggiungerò nella prossima versione).
- Aggiunto il collegamento "Classifiche Tornei" nella index, per visualizzare le classifiche di tutti i tornei in corso
- Migliorata la pagina del "Calendario" con i collegamenti diretti ai tabellini delle partite
- Molte altre modifiche minori
