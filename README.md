# BikeS
BikeS
Aplicatia Bikes este un sistem online de management al programărilor și stocurilor unui operatii de service de biciclete. 
Fiecare client va consulta calendarul service-ului, iar apoi va completa un formular cu data și ora dorită, plus detalii despre problemă, putând adăuga inclusiv fișiere media: imagini și filme. 
Administratorul afacerii va putea respinge programarea adăugând un mesaj explicativ: "Ne pare rău, dar nu avem în stoc piesele necesare pentru reparație. Reveniți în S săptămâni" sau o va putea aproba, oferind și un preț estimativ.
De asemenea, aplicația îi va permite acestuia să țină evidența stocurilor existente, cât și a comenzilor date către furnizori. Acesta va putea importa atat date în format CSV și JSON, cât și exporta în oricare din formatele CSV, JSON, PDF.
Aplicatia va fi capabila sa creeze o lista cu reparatii, pretul va fi calculat automat. Dupa realizarea reparatiei, stocul va fi updatat automat.
    De asemenea, fiecare element din stoc va avea un "stoc minim", iar atunci cand se ajunge la acesta, se va creea automat o comanda noua pentru acel element. In acest mod stocul nu va ramane niciodata gol si nu se va ajunge la situatia de a refuza o comanda din cauza stocului indisponibil.
    Administratorul va putea vizualiza si castigurile totale pe o anumita luna pentru a vizualiza productivitatea afacerii.
Detalii de implementare:
-clientul va consulta zilele si orele disponibile service-ului, va completa formularul cu ora si data dorita, nume, mail, imagine/video si descriere. Programarea va fi preluata si stocata in baza de date( in tabela Programari) ( data si orele disponibile vor fi preluate tot din baza de date)
-administratorul poate vedea programarile in pagina de programari (acestea sunt afisate din baza de date )
-administratorul poate tine evidenta stocurilor din pagina stocuri, el poate adauga/ elimina produse din baza de date (tabela stocuri)
-administratorul poate tine evidenta comenzilor in pagina comenzi, el poate da comenzi noi pentru produsele necesare service-ului
- stoc minim pt fiecare produs, atunci cand un stoc este mai mic de 20, se va creea automat o comanda noua pentru acel produs
