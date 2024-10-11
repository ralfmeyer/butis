<!-- resources/views/emails/example.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Beispiel E-Mail</title>
</head>
<body>
    <p>Hallo {{ $details['beurteiler1anrede'] }} {{ $details['beurteiler1name'] }},</p>

    <p>vielen Dank, sie haben als Beurteiler 1 die Beurteilung von {{ $details['beurteilteranrede'] }} {{ $details['beurteiltername'] }} abgeschlossen.</p>

    <p>Diese Mail wird automatisch vom Server f&uuml;r das Beurteilungswesen (ButIS) versendet.<br>
    Bitte antworten Sie nicht auf diese Email. Anmeldungen am Programm ButIS erfolgen mit der Personalnummer.<br>
    R&uuml;ckfragen bitte an die Personalabteilung, Frau Erika Herzog, E.Herzog@lkclp.de, Telefon 543<br></p>

    <p>(Email generiert auf dem Server https://butis-clp.cap.kdo.de/ )</p>


    <p><b>Hinweis: Eine Kopie diese Mail wird an den Beurteiler 2 {{ $details['beurteiler2anrede'] }} {{ $details['beurteiler2name'] }} gesendet.</b></p>
</body>
</html>
