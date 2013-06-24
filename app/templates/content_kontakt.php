<?php 
/**
 * Kontaktseite
 * Ausgabe und Aktion der Kontaktformularseite 
 * @author TThies Schillhorn 
 */
?>
<div class="kontakt" >
<h2>Kontakt</h2>
<?php
    // die E-Mail-Adresse wohin die Nachricht soll erstmal mit einem default vorbelegen
    $mailto = "beispiel@beispielfirma.de";

    if (isset($displayData['kontakt']))
    {
        $mailto = $displayData['kontakt']->mymail;
    }

    // Holen der Eingaben oder Fuellen mit einer leeren Vorgabe
    $eingabefehler = "";
    $name = isset($_POST["name"]) ? $_POST["name"] : "";
    $mailfrom = isset($_POST["mailfrom"]) ? $_POST["mailfrom"] : "";
    $betreff = isset($_POST["betreff"]) ? $_POST["betreff"] : "";
    $nachricht = isset($_POST["nachricht"]) ? $_POST["nachricht"] : "";

    // Hier das Formular erstellen, bevor es dargestellt wird
    $formular = "<form class='kontakt' enctype='multipart/form-data' action='?page=kontakt&amp;action=save' method='post'>
<table>
<tr>
<td colspan='2' class='pflichtfeld'><!-- Raum fuer evtl. Fehlermeldungsanzeige --></td>
</tr>
<tr class='hintergrund'>
<td>
<label for='Name'>Name: <span class='pflichtfeld'>*</span>
</label>
</td>
<td>
<input type='text' size='45' name='name' value='" . $name . "' id='Name'>
</td>
</tr>
<tr class='hintergrund'>
<td>
<label for='Email'>E-Mail: <span class='pflichtfeld'>*</span>
</label>
</td>
<td>
<input type='text' size='45' name='mailfrom' value='" . $mailfrom . "' id='Email'>
</td>
</tr>
<tr class='hintergrund'>
<td>
<label for='Betreff'>Betreff: <span class='pflichtfeld'>*</span>
</label>
</td>
<td>
<input type='text' size='45' name='betreff' value='" . $betreff . "' id='Betreff'>
</td>
</tr>
<tr class='hintergrund'>
<td colspan='2'>
<label>Nachricht: <span class='pflichtfeld'>*</span><br>
<textarea name='nachricht' rows='6' cols='72' id='Nachricht'>" . $nachricht . "</textarea>
</label>
</td>
</tr>
<tr class='hintergrund'>
<th colspan='2'>
<br><span class='pflichtfeld'>&#10034;</span>
<small>Bitte alle Pflichtfelder ausf&uuml;llen!</small> &nbsp; &nbsp;
<input type='submit' name='save' value='Formular absenden'>
</th>
</tr>
</table>
</form>\n";

    // check ob Formular abgesendet wurde
    if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST["sendung"]))
    {
        // check der Eingabe
        $fehlercount = 0;
        if (strlen($name) < 3)
        {
            $eingabefehler .= "&bull; Bitte geben Sie einen Namen angeben!<br>";
            $fehlercount += 1;
        }
        if (filter_var($mailfrom, FILTER_VALIDATE_EMAIL) === False)
        {
            $eingabefehler .= "&bull; Bitte geben Sie eine korrekte eMail-Adresse an!<br>";
            $fehlercount += 1;
        }
        if (strlen($betreff) < 5)
        {
            $eingabefehler .= "&bull; Eine Angabe eines sinnvollen Betreffs" . ((strlen($betreff) == 0) ? " ist erforderlich" : " muss ausf?hrlicher sein!<br>");
            $fehlercount += 1;
        }
        if (strlen($nachricht) < 30)
        {
            $eingabefehler .= "&bull; Bitte beschreiben Sie in der Nachricht Ihr Anliegen" . ((strlen($nachricht) == 0) ? "" : " ausf?hrlicher!<br>");
            $fehlercount += 1;
        }

        // Sind keine Eingabefehler vorhanden
        if ($fehlercount === 0)
        {
            // Vorbereiten der Nachrichtenparameter
            $text = "
Name: $name
E-Mail: $mailfrom
Betreff: $betreff
Nachricht: $nachricht
";

            // E-Mail versenden
            mb_internal_encoding("ISO-8859-1");
            $Betreff = mb_encode_mimeheader($betreff, "ISO-8859-1", "Q");
            $kopfzeile = "MIME-Version: 1.0;\nFrom: " . mb_encode_mimeheader($name, "ISO-8859-1", "Q") .
            "<" . $mailfrom . ">\nContent-Type: text/plain; Charset=ISO-8859-1;\n";
            if (mail($mailto, $Betreff, $text, $kopfzeile))
            {
                // Best?tigung, dass die eMail versendet wurde
                echo "<p class='hintergrund danke'>" .
                        "Vielen Dank, Ihre Nachricht wurde an den Empfänger versendet.<br><br>";
            }
            else
            {
                // Falls Probleme beim Versenden der Nachricht auftraten,
                // wird hier die E-Mail-Adresse f?r den direkten Kontakt eingeblendet.
                echo "<p class='hintergrund danke'>Beim Senden der Nachricht ist ein Fehler aufgetreten!<br>" .
                        "Bitte wenden Sie sich direkt an: <a href='mailto:" . $mailto . "'>" . $mailto . "</a></p>";
            }
        }
        else
        {
            // Eingabefehler und Formular ausgeben
            echo str_replace("<!-- Fehleranzeige -->",
                             "<b>Ihre Nachricht konnte aus folgende". (($fehlercount == 1) ? "m Grund" : "n Gr&uuml;nden") . " noch nicht versendet:</b><br>"
                             . $eingabefehler, $formular);
        }
    }
    else
    {
       // Darstellung des Formulars
       echo $formular;
    }
?>
</div>