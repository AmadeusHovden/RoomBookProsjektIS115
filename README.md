# RoomBookProsjektIS115
Repo for Amadeus og sander til et RoomBookingSystem, et prosjektet i IS-115 Php.

Main er nå slik prosjektet ble levert! 02.12 desember.

## Composer må lastes ned for å bruke ulike funksjoner i prosjektet


- Admin siden vise alle bookinger i dato rekkefølger med alt informasjon i tabell! (Noe form for å registerre om de har sjekket in sjekket Ut?
- Admin kunne se alle rom, endre info og gjøre de utilgjengelig
- Admin gjøre om vanlig bruker til admin bruker!


### Oppgaven:
- lite motell med 25 rom til sammen. 
- Motellet må ha minimum tre forskjellige romtyper, f.eks. enkeltrom, dobbeltrom og junior suite. 
- Hver romtype må ha en begrensning på hvor mange som kan bo der (voksne og barn). Dette vil kreve en forståelse av variabler, funksjoner, matriser og andre grunnleggende PHP-konsepter samt brukergrensesnitt.

- Det må være mulig å registrere seg som gjest og administrator. En administrator må kunne navngi og beskrive rom og romtyper, samt gjøre enkelte rom utilgjengelige for perioder. 
- En gjest må kunne søke på datoer for innsjekk og utsjekk, samt spesifisere hvor mange voksne og barn som skal overnatte. 
- Om dere vil gjøre det mer avansert, kan dere la gjesten legge inn preferanser på hvorvidt rommet (1) skal i en høyere eller lavere etasje, eller (2) være eller ikke være i nærheten av en heis. 
- Det er også mulig å implementere et lojalitetsprogram og ha en gjesteprofil hvor gjesten kan se sin overnattingshistorikk, hente ut kvitteringer, og lagre ev. preferanser.

- Dere må tenke gjennom hva gjester (både de som bor ofte og av og til) og motelleierne ville satt pris på i et slik system, prioritere ønsker og implementere så mye som dere klarer gjennom godt programmeringshåndverk.


## Romtyper:
3 Suite : 5 pers (2.etasje)
5 Familierom : 4 pers (2.etasje)
10 Dobbeltrom : 2 pers (1.etasje)
7 Enkeltrom : 1 pers (1.etasje)

plass til totalt 52 pers.

etter søk på rommene med dato, Gi bruker mulighet til å filtrere, både 1 eller 2 etasje, og nærme eller ikke nærme heis.


## Brukere?
- Det skal være 3 BRUKERE: Admin (hotellarbeider), Gjest (noen som booker rom uten å registrere seg, men blir fortsatt lagt til med informasjon) og Bruker (en standard bruker som logger på eller har registrert seg)
- ENeste måten å få rollen "gjest" er ved å booke et rom uten innlogging, da blir booking detelajer lagt i databasen som en "GJEST"
- Det er samme logg inn for brukere, og admin, man bruker logginn form i headeren, som også lar en vanlig bruker registrere seg.
- Når man er i booking prosessen så skal bruker bli spurt om de skal logge inn, eller om de fortsetter som gjest.
- På profil siden sin skal en bruker kunne se Navnet sitt sant annen kontaktinfo og rolle, tidligere bookinger, med all info, kunne legge til bakgrunnsbilde med (modul 9).
og man skal kunne endre passordet sitt, ved å bruke det gamle passordet.


- Lojalitetsprogram, billigere pris etter flere bookinger, hvis man er logget inn som bruker. Etter 5 bookinger, halv pris på suite?

- Admin sin profil side skal, kunne legge til nye admin brukere, dette ved å bytte rolle til en allerede registrert mail. Altså skrive inn mail (som allered må være i databasen) trykke på admin knapp, og rollen blir endret til admin, fra bruker. Admin side skal også kunne bytte passordet på sin bruker. 
Men det viktigste en admin gjør er å bytte navn og informasjon på rommene, samt å gjøre rommene utilgjengelig, eller sette rommet til en allered registrert bruker email.




