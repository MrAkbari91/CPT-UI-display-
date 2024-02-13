<?php  
if ( !defined( 'ABSPATH' ) ) exit;
// Help Content
?>
<!-- BEGIN tab -->
<h3 id="ctc_tutorial">Hier starten: Anleitungs-Videos</h3>
<!-- p><iframe width="560" height="315" src="https://www.youtube.com/embed/xL0YmieF6d0?rel=0&modestbranding=1" frameborder="0" allowfullscreen></iframe></p>
<p><iframe width="560" height="315" src="https://www.youtube.com/embed/vhQ5oi20rYE?rel=0&modestbranding=1" frameborder="0" allowfullscreen></iframe></p -->
<p>Wir betten aus Leistungsgründen keine Anleitungsvideos mehr ein. <a href="http://www.childthemeplugin.com/tutorial-videos" target="_blank">Hier klicken, um die Videos anzuschauen.</a></p><!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_getting_started">Eltern/Kind-Tab</h3>
<ol><li><strong>Eine Aktion auswählen:</strong>
  <ul><li><strong>Neues Kind-Thema ERZEUGEN</strong> - Installiere ein neues anpassbares Kind-Thema mit einem installierten Thema als Eltern.</li>
  <li><strong>Bestehendes Kind-Thema ANPASSEN</strong> - Aufsetzen eines vorher installierten Kindthemas für den Konfigurator oder zum Ändern der aktuellen Einstellungen.</li>
  <li><strong>Bestehendes Kind-Thema DUPLIZIEREN</strong> - Eine vollständige Kopie eines Kindthemas in ein neues Verzeichnis erstellen, inkl. Menüs, Widgets und anderer Einstellungen. Die Option zum Kopieren der Eltern-Einstellungen (Schritt 8 unten) ist mit dieser Aktion deaktiviert.</li>
  <li><strong>Bestehendes Kind-Thema ZURÜCKSETZEN</strong> (dies wird all Ihre Arbeit im Konfigurator überschreiben) - Kind-Thema-Formatvorlage und Funktionsdateien auf ihren ursprünglichen Zustand setzen (vor der Initial-Konfiguration oder dem letzten Zurücksetzen).</li></ul></li>
<li><strong>Eltern-Thema auswählen</strong> falls Sie ein neues Kind-Thema erstellen; ein Kind-Thema auswählen, falls Sie anpassen, duplizieren, oder zurücksetzen möchten.</li>
<li><strong>Analysiere Kind-Thema</strong> - "Analysieren" klicken, um Formatvorlage-Abhängigkeiten und andere mögliche Probleme zu erkennen.</li>
<li><strong>Neues Verzeichnis benennen</strong> beim Erstellen eines neuen Kindthemas; ansonsten prüft es, ob das Verzeichnis korrekt ist. - Dies ist NICHT der Name des Kindthemas. Sie können den Namen, die Beschreibung etc. in Schritt 7 unten anpassen.</li>
<li><strong>Speicherort der neuen Stile auswählen:</strong><ul>
  <li><strong>Primäre Formatvorlage (style.css)</strong> - Sichere neue angepasste Stile direkt in die primäre Formatvorlage des Kindthemas (ersetze vorhandene Werte). Die primäre Formatvorlage wird in der vom Thema definierten Reihenfolge geladen.</li>
  <li><strong>Separate Formatvorlage</strong> - Sichere neue angepasste Stile in separate Formatvorlage und verwende bestehende Kindthemen-Stile als Basis. Diese Option wählen, wenn Sie die Original-Kindthemen-Stile behalten wollen (statt zu überschreiben). Diese Option erlaubt es Ihnen, nach der primären Formatvorlage geladene Formatvorlagen anzupassen.</li></ul></li>
<li><strong>Elternthema-Formatvorlage Behandlung auswählen:</strong><ul>
  <li><strong>Benutze die WordPress Stil-Warteschlange.</strong> - Den Konfigurator die richtigen Aktionen und Abhängigkeiten auswählen lassen und Funktionendateien automatisch aktualisieren.</li>
  <li><strong>Benutze @import</strong> in der Kind-Thema-Formatvorlage. - Diese Option nur benutzen, wenn die Eltern-Formatvorlage nicht mit der WordPress-Warteschlange geladen werden kann. Die Benutzung von <code>@import</code> ist nicht länger empfohlen.</li>
  <li><strong>Keine zusätzliche Eltern-Formatvorlage-Behandlung hinzufügen.</strong> - Diese Option auswählen, wenn dieses Thema die Elternthema-Formatvorlage schon behandelt oder die Eltern-Thema <code>style.css</code>-Datei für die Darstellung/Anzeige nicht benutzt wird.</li></ul></li>
<li><strong>Kind-Thema-Name, Beschreibung, Autor, Version etc. anpassen</strong></li>
<li><strong>Kopieren der Elternthema-Menüs, Widgets und anderer Einstellungen in das Kind-Thema.</strong> - HINWEIS: Dies überschreibt alle im Kind-Thema vorgenommenen Änderungen und Einstellungen.</li>
<li><strong>Klicken Sie auf die Schaltfläche</strong> um den Konfigurator zu starten.</li>
<li><strong>WICHTIG: <a target="_blank" href="<?php echo CHLD_THM_CFG_DOCS_URL; ?>/how-to-use/#preview_activate" title="Testen Sie Ihr Kind-Thema vor der Aktivierung!">Testen Sie Ihr Kind-Thema immer mit der Live-Vorschau (Themen-Konfigurator) vor der Aktivierung!</a></strong></li>
</ol>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_query_selector">Abfrage/Selektor-Tab</h3>
<p>Es gibt zwei Wege, Eltern-Stile (Basiswerte) zu identifizieren und zu übersteuern. Der Child Theme Configurator lässt Sie Stile suchen nach <strong>Selektor</strong> oder nach <strong>Eigenschaft</strong>. Wenn Sie einen bestimmten Selektor ändern wollen (z.Bsp. h1), benutzen Sie den "Abfrage/Selektor"-Tab. Wenn Sie einen Wert Webseiten-weit ändern wollen (z.Bsp. die Farbe des Typs), benutzen Sie den "Eigenschaften/Wert"-Tab.</p>
<p>Der Abfrage/Selektor-Tab lässt Sie bestimmte Selektoren finden und bearbeiten. Zuerst finden Sie die Abfrage, die den gesuchten Selektor enthält, indem Sie in die <strong>Abfrage</strong> Autoauswahl-Box schreiben. Wählen Sie durch Mausklick oder "Eingabe"- bzw. "Tabulator"-Tasten. Selektoren sind in der <strong>Basis</strong> Abfrage standardmäßig.</p>
<p>Dann finden Sie den mit der Eingabe in der <strong>Selektor</strong> Autoauswahl-Box. Wählen Sie aus mit Mausklick oder der "Eingabe"- bzw. den "Tabulator"-Tasten.</p>
<p>Dies lädt alle Eigenschaften für diesen Selektor mit den Eltern-Werten auf der linken und den Kind-Werten auf der rechten Seite. Alle bestehenden Kind-Werte werden automatisch abgefüllt. Es gibt auch eine Musteransicht, welches die Kombination der Eltern- und Kindwerte Übersteuerungen anzeigt. Beachten Sie, dass der <strong>Rahmen</strong> und <strong>Hintergrundbild</strong> speziell behandelt werden.</p>
<p>Wenn Sie zusätzliche Eigenschaften zu einem bestehenden Selektor hinzufügen wollen, laden Sie zuerst den Selektor mit dem Abfrage/Selektor-Tab. Dann finden Sie die zu übersteuernde Eigenschaft mit der Eingabe in die <strong>Neue Eigenschaft</strong>-Auswahlbox. Wählen Sie mit einem Mausklick oder der Eingabe der ENTER- oder TAB-Taste. Dies wird der Auswahl eine neue Eingabezeile hinzufügen.</p>
<p>Das "Reihenfolge"-Feld enthält die Original-Reihenfolge des Selektors der Eltern-Formatvorlage. Sie können die Selektor-Reihenfolge ändern, indem Sie eine tiefere/höhere Zahl im Reihenfolge-Feld eingeben. Sie können auch Stil-Übersteuerungen forcieren (sogenannter !important-Schalter), indem Sie das "!" Kästchen neben der Eingabe anwählen. Benutzen Sie es nicht zu oft.</p>
<p>Klicken Sie auf "Sichern" um die Kind-Formatvorlage zu aktualisieren und die Änderungen im Wordpress-Admin zu speichern.</p>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_new_styles">Rohes CSS hinzufügen</h3>
<p>Wenn Sie komplett neue Selektoren oder sogar @media-Queries hinzufügen wollen, können Sie formloses CSS im Textfeld "Rohes CSS" eingeben. Achten Sie auf eine korrekte Syntax (bspw. passende geschweifte Klammern etc.), damit der Parser die neuen Stile laden kann. Sie erkennen Fehler an einem roten "X", welches neben der Sichern-Schaltfläche erscheint.</p>
<p>Wenn Sie die Shorthand-Syntax für Eigenschaften und Werte bevorzugen (anstatt der Vorgaben durch den Kind-Thema-Konfigurator), können Sie diese hier auch eingeben. Der Parser wir diese Eingaben automatisch in normalisierten CSS-Code konvertieren.</p>
<p>Wenn Sie zusätzliche Eigenschaften zu einem bestehenden Selektor hinzufügen möchten, laden Sie zuerst den Selektor mit dem Abfrage/Selektor-Tab. Dann suchen Sie die Eigenschaft zum Übersteuern, indem Sie in die <strong>Neue Eigenschaft</strong>-Box schreiben. Wählen Sie mit einem Mausklick oder der Eingabe der ENTER- oder TAB-Taste. Dies wird der Auswahl eine neue Eingabezeile hinzufügen.</p>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_property_value">Eigenschaften/Wert-Tab</h3>
<p>Es gibt zwei Wege, Eltern-Stile (Basiswerte) zu identifizieren und zu übersteuern. Der Child Theme Configurator lässt Sie Stile suchen nach <strong>Selektor</strong> oder nach <strong>Eigenschaft</strong>. Wenn Sie einen bestimmten Selektor ändern wollen (z.Bsp. h1), benutzen Sie den "Abfrage/Selektor"-Tab. Wenn Sie einen Wert Webseiten-weit ändern wollen (z.Bsp. die Farbe des Typs), benutzen Sie den "Eigenschaften/Wert"-Tab.</p>
<p>Der Eigenschaften/Wert-Tab lässt Sie bestimmte Werte für eine gewisse Eigenschaft finden und ermöglicht dann das Bearbeiten für individuelle Selektoren, welche diese Eigenschaft-/Wert-Kombination benutzen. Zuerst finden Sie die Eigenschaft, die Sie übersteuern möchten, indem Sie diese in der <strong>Eigenschaft</strong> Autoauswahl-Box eingeben. Wählen Sie durch Mausklick oder "Eingabe"- bzw. "Tabulator"-Tasten.</p>
<p>Dies lädt alle einzigartigen Werte dieser Eigenschaft in der Eltern-Formatvorlage mit einer Mustervorschau für diese Werte. Wenn Werte in der Kind-Formatvorlage existieren, die nicht in der Eltern-Formatvorlage enthalten sind, werden diese ebenfalls angezeigt.</p>
<p>Für jeden einzigartigen Wert, klicken Sie auf die "Selektoren"-Verknüpfung, um eine Liste der Selektoren mit dieser Eigenschaften-/Wert-Kombination anzuzeigen, nach Abfrage gruppiert mit einer Muster-Vorschau der Werte und Eingaben für die Kind-Werte. Alle bestehenden Kind-Werte werden automatisch abgefüllt.</p>
<p>Klicken Sie auf "Sichern" um die Kind-Formatvorlage zu aktualisieren und die Änderungen im Wordpress-Admin zu speichern.</p>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_imports">Webschriftarten-Tab</h3>
<p>Sie können zusätzliche Formatvorlagen und Webschriftarten verknüpfen, indem Sie @import Regeln in das Textfeld auf dem Webschriftarten-Tab eingeben.</p>
<p><strong>Hinweis:</strong> Child Theme Configurator schreibt nicht mehr länger @import Regeln in die Formatvorlage. Stattdessen benutzt es das @import Schlüsselwort und stellt sie in die Warteschlange. WordPress wandelt sie dann um in &lt;link&gt; Tags im verarbeiteten HTML.</p>
<p>Wichtig: importieren Sie die Eltern-Formatvorlage nicht hier. Benutzen Sie die "Eltern-Formatvorlage-Behandlung"-Option auf dem Eltern/Kind-Tab.</p>
<p>Wenn Sie eine Formatvorlage unter "Zusätzliche Formatvorlegen einlesen" gewählt haben, als Sie das Kind-Thema erstellt haben, werden diese Stile für Übersteuerungen in der Kind-Formatvorlage zur Verfügung stehen.</p>
<p>WordPress lädt automatisch zusätzliche Formatvorlagen, wenn es das Eltern-Thema lädt, also müssen Sie @import rules dafür hier nicht hinzufügen.</p>
<p>Unten ist ein Beispiel, welche eine lokale angepasste Formatvorlage lädt (Sie müssen das "fonts"-Verzeichnis und die Formatvorlage hinzufügen), sowie die Schriftart "Open Sans" von Google Web Fonts:</p>
<blockquote><pre><code>&#64;import url(fonts/Formatvorlage.css);
&#64;import url(http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic);</code></pre></blockquote>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_files">Dateien-Tab</h3>
<h5>Eltern-Vorlagen</h5>
<p>Kopieren von PHP-Vorlagendateien des Eltern-Themes, indem Sie die Kästchen auswählen und "Auswahl zu gewählten Kind-Themen kopieren", und die Vorlage wird zum Kind-Themenverzeichnis hinzugefügt.</p>
<p><strong>ACHTUNG: Wenn Ihr Kind-Thema aktiv ist, wird nach dem Kopiervorgang sofort die Kind-Thema-Version der Datei anstatt der Eltern-Datei benutzt.</strong></p>
<p>Die <code>functions.php</code>-Datei wird getrennt erstellt und kann nicht hierher kopiert werden.</p>
<h5>Kind-Thema-Dateien</h5>
<p>Vorlagen, die vom Eltern-Thema kopiert wurden, sowie alle Formatvorlagen-Sicherungen, sind hier aufgelistet. Vorlagen können mittels des Themen-Editors im Design-Menü bearbeitet werden.</p>
<p>Löschen Sie Kind-Thema-Dateien, indem Sie die Kästchen anwählen und auf "Auswahl löschen" klicken.</p>
<h5>Kind-Thema-Bilder</h5>
<p>Diese Bilder befinden sich unter dem <code>Images</code>-Verzeichnis in Ihrem Kind-Thema-Verzeichnis und sind nur für Formatvorlagen vorgesehen. Benutzen Sie die Medienbibliothek für Inhaltsbilder.</p>
<p>Sie können neue Bilder mittels des "Bild hochladen"-Formulars hochladen. Löschen Sie Bilder durch die Auswahl der Kästchen und dem Klicken auf "Auswahl löschen".</p>
<h5>Kind-Thema Screenshot</h5>
<p>Sie können hier einen speziellen/angepassten Screenshot für das Kind-Thema hochladen.</p>
<p>Der Themen-Screenshot sollte ein 4:3-Verhältnis aufweisen (z.Bsp, 880px x 660px) JPG, PNG oder GIF. Es wird in <code>screenshot</code> umbenannt.</p>
<h5>Kind-Thema als ZIP-Archiv exportieren </h5>
<p>Sie können Ihr Kind-Thema für den Gebrauch auf einer anderen Wordpress-Webseite herunterladen, indem Sie auf "Export" klicken.</p>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_preview">Vorschau und Aktivierung</h3>
<p><strong>WICHTIG: <a target="_blank" href="<?php echo CHLD_THM_CFG_DOCS_URL; ?>/how-to-use/#preview_activate" title="Testen Sie Ihr Kind-Thema vor der Aktivierung!">Testen Sie Ihr Kind-Thema vor der Aktivierung!</a></strong> Einige Themen (hauptsächlich kommerzielle Themen) laden Eltern-Formatvorlagen nicht korrekt oder laden Kind-Formatvorlagen automatisch oder PHP-Dateien. <strong>Im schlimmsten Fall wird bei Aktivierung Ihre Webseite unbrauchbar.</strong></p>
<ol>
  <li>Navigieren Sie zu Design > Themen im Wordpress-Admin. Sie werden nun die neuen Kind-Themen als eine der installierten Themen sehen.</li>
  <li>Klicken Sie auf "Live-Vorschau" unterhalb des Kind-Themas, um es in Aktion zu sehen.</li>
  <li>Wenn Sie das Kind-Thema nun live schalten wollen, klicken Sie auf "Aktivieren."</li>
</ol>
<p><strong>MULTISITE NUTZER:</strong> Sie müssen Ihr Thema Netzwerk-aktivieren, um es in der Live-Vorschau zu sehen. Gehen Sie zu 'Themen' in der Netzwerk-Administration.</p>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_permissions">Dateiberechtigungen</h3>
<p>Wordpress wurde für verschiedene Serverkonfigurationen entwickelt. Child Theme Configurator benutzt die WordPress Filesystem API, um Webseiten das Bearbeiten von Dateien zu ermöglichen, die Nutzerberechtigungen benötigen.</p>
<p>Allerdings, weil die meiste Funktionalität mittels AJAX(Hintergrund)-Anfragen passiert, muss die Kind-Formatvorlage auf dem Server beschreibbar sein.</p>
<p>Das Plugin erkennt automatisch Ihre Konfiguration und liefert eine Anzahl von Optionen, um diese Anforderung zu lösen. Benutzen Sie die zur Verfügung gestellten Verknüpfungen, um mehr zu erfahren, beispielsweise:</p>
<ol>
  <li>Die Formatvorlage mithilfe des Plugins vorübergehend schreibbar machen.</li>
  <li>Hinzufügen von FTP/SSH-Berechtigungen zur Wordpress-Konfigurationsdatei.</li>
  <li>Auf einem Server die Schreibberechtigung manuell setzen.</li>
  <li>Ihrem Webserver die Schreibberechtigung in gewissen Situationen erlauben.</li>
</ol>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_faq">FAQs</h3>
<<h5 id="broken_theme">HILFE! Ich habe eine Datei geändert und kann nun über nicht mehr über wp-admin einloggen, um es zu reparieren!</h5>
<p>Um aus einem defekten Thema herauszukommen, müssen Sie das problematische Themen-Verzeichnis umbenennen (via FTP, SSH oder Ihrem Webhoster-Kontrollpanel bzw. Dateimanager), so dass Wordpress es nicht mehr finden kann. WordPress wird dann einen Fehler generieren und zum Standard-Thema zurückkehren (aktuell Twenty-Fourteen).</p>

<p>Das Kind-Thema ist normalerweise in Ihrem Themen-Verzeichnis</p>

<code>[path/to/wordpress]/wp-content/themes/[child-theme]</code>

<p>Um dies in der Zukunft zu vermeiden, testen Sie Ihr Kind-Thema immer in der Live-Vorschau, bevor Sie es aktivieren.</p> 
<h5 id="no-comments">Wie füge ich Kommentare hinzu?</h5>
<p><strong>Kommentare sind nicht beliebig erlaubt.</strong> Eine hohe Flexibilität bei der Vorschau und zum Ändern von Stilen erfordert ein kompliziertes Parsen (Auslesen) und Datenstrukturen. Kommentare zu unterhalten, die an ein bestimmtes Element in der Formatvorlage gebunden sind, ist sehr aufwändig im Vergleich zum Nutzen. Obwohl wir dies in der Zukunft als Funktion bringen wollen, <em>werden zurzeit noch alle Kommentare aus dem Code der Kind-Formatvorlage entfernt.</em></p>
<h5 id="menus_broken">Wieso werden meine Menüs inkorrekt angezeigt, wenn ich das neue Kind-Thema aktiviere?</h5>...oder...
<h5 id="header_broken">Wieso fehlt mein angepasster Header (custom header), wenn ich das neue Kind-Thema aktiviere?</h5>...oder...
<h5 id="background_broken">Wieso ändert sich mein angepasster Hintergrund zurück zum Standard, wenn ich das Kind-Thema aktiviere?</h5>...oder...
<h5 id="options_broken">Wieso verschwinden meine Themen-Optionen, wenn ich das Kind-Thema aktiviere?</h5>
<p>Diese Optionen sind für jedes Thema spezifisch und werden getrennt in der Datenbank gespeichert. Beim Erstellen eines neuen Kind-Themas sind diese Optionen leer.</p>

<p><strong>Viele dieser Optionen können in das Kind-Thema kopiert werden, indem man das Kästchen "Kopieren Eltern-Theme-Menüs, Widgets und andere Design-Optionen" anklickt, wenn Sie die Kind-Thema-Dateien auf dem Eltern/Kind-Tab erstellen.</strong></p>

<p>Wenn Sie andere Optionen setzen wollen, können Sie diese nach dem Aktivieren des Kind-Themas aktivieren, oder mittels der Live-Vorschau unter Design > Themen.</p>
<ul class="instructions">
    <li><strong>Menüs: </strong> Gehen Sie zu Design > Menüs und klicken Sie den "Position"-Tab. Standardmäßig wird das Primärmenü die Verknüpfungen automatisch aus den bestehenden Seiten erstellen. Wählen Sie Ihr angepasstes Menü aus der Auswahlliste und klicken Sie "Neues Menü benutzen". Dies ersetzt das Standardmenü und Sie sehen die korrekten Verknüpfungen.</li>

    <li><strong>Header (Kopf): </strong> Gehen Sie zu Design > Header. Einige Themen zeigen standardmäßig den Titel und die Tagline Ihrer "Allgemeinen Einstellungen". Wählen Sie "Bild auswählen" und finden Sie einen "Kopf" aus der Medienbibliothek oder mittels Hochladen. Dies ersetzt den Standard mit dem angepassten Bild.</li>

    <li><strong>Hintergrund: </strong> Gehen Sie zu Design > Hintergrund und wählen Sie ein neues Hintergrundbild oder eine Farbe.</li>

    <li><strong>Optionen: </strong> Jedes Thema behandelt Optionen speziell/anders. Meistens erstellen Sie einen Satz Optionen und speichern ihn in der Wordpress-Datenbank. Einige Optionen sind spezifisch für das aktive Thema (oder Kind-Thema), und einige nur für das Eltern-Thema bestimmt (d.h. das Kind-Thema kann sie NICHT übersteuern). Sie müssen sich beim Themen-Autor erkundigen, welche auf welche Art funktionieren.</li>
</ul>
</p> 
<h5 id="existing_parent">Wie verschiebe ich bereits gemachte Änderungen an meinem Thema in mein Kind-Thema?</h5>
<p><a href="<?php echo CHLD_THM_CFG_DOCS_URL; ?>/how-to-use/#child_from_modified_parent" class="scroll-to">Folgen Sie diesen Schritten</a>. </p>
<h5 id="web_fonts">Wie füge ich Webschriftarten hinzu?</h5>
<p>Die einfachste Methode ist das Einfügen des @import code, geliefert von <a href="http://www.google.com/fonts" title="Google Fonts">Google</a>, <a href="http://www.fontsquirrel.com/" title="Font Squirrel">Font Squirrel</a> oder jeder anderen Schriftartenseite auf dem Schriftarten-Tab. Die Schriftarten werden dann zum Gebrauch als Wert der <strong>font-family</strong> Eigenschaft zur Verfügung stehen. Stellen Sie sicher, dass Sie die Lizenz zum Gebrauch der Webschriftarten verstehen. </p>
<p>Sie können auch eine sekundäre Formatvorlage erstellen, welche die @font-face Regeln enthält, und diese auf dem Webschriftarten-Tab importieren. </p>
<h5 id="plugin">Funktionert das mit Plugins?</h5>
<p>Wir bieten eine Premium-Erweiterung an, die Sie Stile für jedes auf der Webseite installierte Plugin einfach verändern lässt. Der Child Theme Configurator Plugin Extension scannt Ihre Plugins und erlaubt die Erstellung von angepassten Formatvorlagen in Ihrem Kind-Thema. <a href="<?php echo CHLD_THM_CFG_DOCS_URL; ?>/child-theme-configurator-pro/" title="Die Kontrolle über Ihre Plugin-Stile mit der Child Theme Configurator Plugin Erweiterung für WordPress übernehmen">Mehr erfahren <i class="genericon genericon-next"></i></a> 
<h5 id="doesnt_work">Wieso funktioniert das in meinem (Hersteller des Themas hier einfügen) Thema nicht?</h5>
<p>Einige Themen (hauptsächlich kommerzielle Themen) laden die Eltern-Vorlagen-Dateien nicht korrekt oder laden Kind-Thema-Formatvorlagen oder PHP-Dateien automatisch.</p><p>Das ist unglücklich, weil es im besten Fall den Webmaster daran hindert, Anpassungen vorzunehmen (abgesehen von solchen durch die Themen-Optionen), die einen Upgrade überleben. <strong>Im schlimmsten Fall jedoch wird Ihre Webseite unbrauchbar, wenn Sie das Kind-Thema aktivieren.</strong> </p>
<p>Kontaktieren Sie direkt den Hersteller, um diese Kern-Funktionalität zu verlangen. Unserer Meinung nach sollten ALLE Themen (vor allem kommerzielle) die von Wordpress.org beschriebenen Themen-Tests erfüllen. </p>
<h5 id="missing_parent">Wieso hat das Eltern-Thema keine Stile, wenn ich auf "Eltern-CSS anzeigen" gehe?</h5>
<p>Ihr Eltern-Thema benutzt für die Formatvorlagen womöglich einen getrennten Ort. Wählen Sie individuelle Formatvorlagen aus dem "Zusätzliche Formatvorlagen auslesen"-Abschnitt des Eltern/Kind-Tabs und klicken Sie erneut auf "Neuaufbau Kind-Thema-Dateien". </p>
<h5 id="performance">Wird das meine Webseite verlangsamen?</h5>
Sobald die Kind-Formatvorlage erstellt ist, fügt CTC im Frontend nur ein wenig Zusatzlast hinzu, da die ganze Funktionalität im Admin enthalten ist.

Das Plugin lädt den Hauptteil des Codes im Admin nur, wenn Sie das Tool benutzen. Die größte Performance-Einbuße geschieht, wenn Sie die Kind-Themen-Dateien auf dem Eltern/Kind-Tab erstellen. </p>
<h5 id="where_are_styles">Wo sind die Stile? Der Konfigurator zeigt nichts an!</h5>
<p>Alle Stile werden dynamisch geladen. Sie müssen in den Textfelder etwas eingeben, um die Stile zum Bearbeiten auszuwählen.</p>
<p>"Base" ist die Abfragegruppe, die mit keiner bestimmten at-Regel verbunden sind.</p>
<p>Beginnen Sie mit einem Klick auf den "Abfage/Selektor"-Tab und der Eingabe von "base" in der ersten Box. In der zweiten Box können Sie dann mit der Eingabe beginnen, um die Stil-Selektoren zum Bearbeiten zu holen. </p>
<h5 id="preview-not-loading">Wieso zeigen die Vorschau-Tab "Formatvorlage kann nicht angezeigt werden"?</h5>
<p>Sie müssen ein Kind-Thema auf dem Eltern/Kind-Tab für die Ansicht der Vorschau laden. Das kann auch passieren, wenn Ihre WP_CONTENT_URL sich von $bloginfo('site_url') unterscheidet. Ajax kann keine domänenübergreifenden Abfagen machen. Überprüfen Sie, ob der Einstellungen > Allgemein > "WordPress Adresse (URL)"-Wert korrekt ist. (Oft fehlt das "www"" in der Domäne.) </p>
<h5 id="edit_manually">Kann ich das Kind-Thema manuell offline bearbeiten (oder mithilfe des Editors), oder muss ich unbedingt den Konfigurator benutzen?</h5>
<p>Sie können jede gewünschte Änderung an der Formatvorlage vornehmen. Stellen Sie nur sicher, die geänderte Formatvorlage mittels des Eltern/Kind-Panels zu importieren, ansonsten wird der Konfigurator Ihre Änderungen beim nächsten Gebrauch überschreiben. Folgen Sie den üblichen Schritten, aber wählen Sie die Auswahloption "Bestehendes Kind-Thema benutzen" als Kind-Thema-Option. Der Konfigurator wird die internen Daten aus der neuen Formatvorlage automatisch aktualisieren. </p>
<h5 id="update_child">Wenn das Eltern-Thema geändert wird (z.Bsp. Upgrade), muss ich das Kind-Thema aktualisieren?</h5>
<p>Nein. Das ist der Zweck von Kind-Themen. Änderungen am Eltern-Thema werden vom Kind-Thema automatisch geerbt.</p>
<p>Ein Kind-Thema ist keine Kopie eines Eltern-Themas. Es ist eine besondere Funktion von WordPress, die Sie bestimmte Stile und Funktionen übersteuern lässt, während der Rest intakt bleibt. Das einzige Mal, dass Sie nach einem Upgrade Änderungen machen müssen ist, wenn das Eltern-Thema Stile oder Funktionsnamen ändert oder entfernt. Qualitäts-Themen sollten alle veralteten Funktionen oder Stile in den Upgrade-Hinweisen erwähnen, so dass Nutzer von Kind-Themen die entsprechenden Anpassungen vornehmen können. </p>
<h5 id="functions">Wo sind die .PHP-Dateien?</h5>
<p>Der Konfigurator fügt dem Kind-Themenverzeichnis automatisch eine leere functions.php-Datei hinzu. Sie können Dateien des Eltern-Themas mittels des "Dateien"-Tabs kopieren. Wenn Sie neue Vorlagen und Verzeichnisse erstellen möchten, müssen Sie diese manuell via FTP oder SSH hochladen. Beachten Sie, dass ein Kind-Thema die Eltern-Vorlagen automatisch erbt, es sei denn, sie bestehen bereits im Kind-Thema-Verzeichnis. Kopieren Sie nur Vorlagen, die Sie auch wirklich anpassen möchten.</p>
<h5 id="specific_color">Wie ändere ich eine(n) bestimmte Farbe/Schriftarttyp/Hintergrund?</h5>
<p>Sie können einen bestimmten Wert global mittels des Eigenschaften/Wert-Tabs übersteuern. Siehe Eigenschaften/Werte, oben.</p>
<h5 id="add_styles">Wie füge ich Stile hinzu, die nicht im Eltern-Thema enthalten sind?</h5>
<p>Sie können Abfragen und Selektoren mittels des "Rohes CSS"-Textfelds auf dem Abfrage/Selektor-Tab hinzufügen. Siehe Abfrage/Selektor, oben. </p>
<h5 id="add_styles">Wie entferne ich Stile aus dem Eltern-Thema?</h5>
<p>Sie sollten eigentlich Stile aus dem Eltern-Thema nicht entfernen. Sie können allerdings die Eigenschaft auf "Erben"," "Keine," oder Null setzen (abhängig von der Eigenschaft). Das wird den Eltern-Wert negieren. Ein wenig Probieren wird nötig sein.</p>
<h5 id="remove_styles">Wie entferne ich einen Stil aus einem Kind-Thema?</h5>
<p>Löschen Sie den Wert aus der Eingabe der Eigenschaft, die sie entfernen möchten. Der Child Theme Configurator fügt Übersteuerungen nur für Eigenschaften mit Werten hinzu. </p>
<h5 id="important_flag">Wie setze ich den !important-Schalter?</h5>
<p>Wir empfehlen immer ein gutes, verschachteltes Design, anstatt auf globale Übersteuerungen zu vertrauen. Dazu haben Sie die Möglichkeit, die Lade-Reihenfolge von Kind-Formatvorlagen zu ändern, indem Sie einen Wert im "Reihenfolge"-Feld eingeben. Sie können Eigenschaften als wichtig setzen, indem Sie das Kästchen mit dem "!" neben jeder Eingabe setzen. Benutzen Sie es massvoll. </p>
<h5 id="gradients">Wie erstelle ich browser-unabhängige Farbverläufe?</h5>
<p>Der Child Theme Configurator benutzt eine standarisierte Syntax für Farbverläufe und unterstützt nur zweifarbige Verläufe ohne Zwischenstopps. Die Eingaben bestehen aus dem Startpunkt (z.Bsp. top, left, 135deg, etc.), der Startfarbe und der Endfarbe. Browser-spezifische Syntax wird automatisch erstellt, wenn Sie diese Werte speichern. Siehe Tricks/Fallen, unten, für weitere Informationen. </p>
<h5 id="responsive">Wie mache ich das Thema responsive?</h5>
<p>Die kurze Antwort ist, ein responsives Eltern-Thema zu benutzen. Einige übliche Methoden für responsives Design sind:
<ul class="instructions"><li>Vermeiden von festen Breiten und Höhen. Der Gebrauch von max- and min-height Werten und Prozenten sind Wege, das Design dem Browser des Benutzers anzupassen.</li>
<li>Die Kombination von floats und clears mit inline und relative-Positionen erlaubt den Elementen, sich der umgebenden Behälterbreite fließend anzupassen.</li>
<li>Anzeigen und Verbergen von Inhalt mittels JavaScript.</li></ul>
<!-- END tab --> 
<!-- BEGIN tab -->
<h3 id="ctc_glossary">Glossar</h3>
<ul>
  <li id="parent_theme"><strong>Eltern-Thema</strong> Das Wordpress-Thema, welches Sie bearbeiten möchten. Wordpress lädt zuerst das Kind-Thema, dann das Eltern-Thema. Wenn ein Stil im Kind-Thema enthalten ist, übersteuert es das Eltern-Thema.</li>
  <li id="child_theme"><strong>Kind-Thema</strong> Neues Thema, basierend auf einem Eltern-Thema. Sie können eine beliebige Anzahl von Kind-Themen von einem einzigen Eltern-Thema erstellen.</li>
  <li id="class"><strong>Klasse</strong> Ein Begriff, der zum Organisieren von Objekten benutzt wird. Zum Beispiel, ein &lt;div&gt; könnte die "blue-text"-Klasse zugewiesen sein. Die Formatvorlage weist dann Mitgliedern der "blue-text"-Klasse den Wert "color: blue;" zu. Somit würde &lt;div&gt; als blauer Text im Browser dargestellt. Klassen-Selektoren beginnen mit einem Punkt.</li>
  <li id="class"><strong>ID</strong> Eine einzigartige Zeichenkette, die ein bestimmtes Element bezeichnet. ID-Selektoren beginnen mit einem Hashtag (#).</li>
  <li id="selector"><strong>Abfrage</strong> @media-Query-Anweisungen (siehe At-Regel unten).</li>
  <li id="query"><strong>Selektor</strong> Kombination eines oder mehrere Elemente, Klassen, IDs oder andere Begriffe zum Identifizieren von Objektgruppen.</li>
  <li id="property"><strong>Eigenschaft</strong> Einer von vielen standardisierten Begriffen, die dem Browser mitteilen, wie Objekte mit einem bestimmten Selektor anzuzeigen sind. Beispiele sind <strong>color</strong>, <strong>background-image</strong> und <strong>font-size</strong>.</li>
  <li id="value"><strong>Wert</strong> Daten, die mit einer Eigenschaft korrespondieren.</li>
  <li id="at-rule"><strong>At-Regel</strong> Ein CSS-Browser-Befehl, um die Standard-Funktionalität zu erweitern. Der Child Theme Configurator unterstützt zwei At-Regeln:
    <ul>
      <li id="at_import"><strong>@import</strong> Weist den Browser an, zusätzliche CSS-Informationen aus einer externen Quelle zu laden.</li>
      <li id="at_media"><strong>@media (Media Query)</strong> Identifiziert Stilblöcke, die nur benutzt werden, wenn gewisse Browser-Eigenschaften zutreffen. Beispiele sind max-width, screen und print.</li>
    </ul>
  </li>
  <li id="child_theme"><strong>Basis-Stil</strong> Ein Stil einer beliebigen Kind- oder Eltern-Vorlage, der durch die Formatvorlage des Kind-Themas übersteuert werden kann. Meistens stammen diese aus der Formatvorlage des Eltern-Themas.</li>
  <li id="override"><strong>Übersteuerung</strong> Wenn ein Selektor in Kind- und Eltern-Thema besteht, bekommt derjenige aus dem Kind-Thema Priorität. Das ist eines der besonderen Merkmale des Kind-Thema-Konfigurators: es hilft dabei, <strong>exakte Übersteuerungen</strong> von Selektoren des Eltern-Themas zu erstellen, und eliminiert viele Stunden des mühsamen Versuchens.</li>
  <li id="child_theme"><strong>Themenvorlage</strong> Eine PHP-Datei eines Themas ohne Funktionen und Klassen. Andere PHP-Dateien können nicht ohne Weiteres sicher überschrieben werden.</li>
</ul>
<!-- END tab -->
<!-- BEGIN sidebar -->
<h4>Unsere Plugins werden Sie nicht mit Spendeneinblendungen nerven...</h4>
<span style="font-size:smaller">...aber wir LIEBEN Empfehlungen.</span><br/><a href="http://wordpress.org/support/view/plugin-reviews/child-theme-configurator?rate=5#postform">Geben Sie uns 5 Sterne</a>
<h4>Wir stellen CTC Pro vor</h4>
<a href="<?php echo CHLD_THM_CFG_DOCS_URL; ?>/child-theme-configurator-pro/" title="Erfahren Sie mehr über CTC Pro"><img src="<?php echo CHLD_THM_CFG_URL . 'css/pro-banner.jpg'; ?>" width="150" height="48" /></a>
<p style="font-size:smaller">Von Wordpress-Entwicklern entworfen, die es jeden Tag benutzen. CTC Pro stellt Plugin-Formatvorlagen und andere Funktionen zur Verfügung, die Ihre Arbeit schneller und einfacher machen. Das ist ein kostenloses Upgrade für Nutzer, die die Plugin-Erweiterung erworben haben. <a href="<?php echo CHLD_THM_CFG_DOCS_URL; ?>/child-theme-configurator-pro/" title="Child Theme Configurator Pro">Mehr erfahren</a></p>
<h4 id="ctc_help_sidebar">Links/Verknüpfungen</h4>
<ul>
  <li><a href="http://www.lilaeamedia.com/about/contact/">Uns kontaktieren</a></li>
  <li><a href="http://www.childthemeplugin.com/">Plugin-Webseite</a></li>
  <li><a href="http://codex.wordpress.org/Child_Themes">WordPress Codex</a></li>
  <li><a href="http://wordpress.stackexchange.com/">WordPress Development (StackExchange)</a></li>
</ul>
<!-- END sidebar -->