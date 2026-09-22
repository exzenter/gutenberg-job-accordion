# Ullmer Stellenanzeigen-Stil

Ein Blockstil für den **Core-Accordion-Block**. Kein eigener Block, kein Custom Post Type,
kein JavaScript — eine PHP-Datei und ein Stylesheet.

---

## Die Empfehlung vorweg

**Nimm den Core-Accordion-Block plus dieses CSS.** Kein eigener Block, kein CPT.

Seit WordPress 6.9 (Dezember 2025) gibt es `core/accordion` als echten Core-Block mit
Unterblöcken: Accordion → Accordion Item → Accordion Heading + Accordion Panel. Das Heading
rendert ein `<button>` in einem `<h3>`, die Interactivity API setzt beim Aufklappen
`aria-expanded` und die Klasse `is-open` am Item und nimmt das `hidden`-Attribut vom Panel.

Damit ist alles Schwierige schon erledigt: Tastaturbedienung, Screenreader-Ansage,
optionales Exklusiv-Aufklappen (nur eins offen), Fokusreihenfolge. Was vom Ullmer-Design
fehlt, sind exakt zwei Dinge — die Kartenoptik und der Pfeil-Button. Beides ist CSS.

### Warum kein eigener Block

Ein eigener Block würde bedeuten, Button-Semantik, ARIA-Zustände, Tastatursteuerung und
das Auf-/Zuklappen selbst zu bauen und dauerhaft zu pflegen — für null sichtbaren
Unterschied. Das ist Arbeit gegen den Core statt mit ihm.

### Warum kein Custom Post Type

Ein CPT lohnt sich ab drei Bedingungen, von denen hier keine zutrifft:

1. **Google for Jobs.** Wer bei Google in der Jobsuche auftauchen will, braucht
   `JobPosting`-Structured-Data pro Stelle. Das ist der einzige Grund, der einen CPT hier
   wirklich rechtfertigen würde. **Wenn das gewünscht ist, kippt meine Empfehlung.**
2. **Mehrfachverwendung.** Wenn dieselbe Stelle auf mehreren Seiten erscheinen soll.
3. **Ablaufdatum.** Wenn Stellen automatisch verschwinden sollen.

Ohne das ist ein CPT mehr Verwaltung, als er spart: eigene Admin-Oberfläche, Query-Block
oder Template, Felder pflegen — für rund 27 Anzeigen an drei Standorten, die ein paar Mal
im Jahr angefasst werden. Als Accordion-Items auf der Karriereseite tippt man sie direkt
dort, wo sie stehen.

### Der eine Haken

Der Core-Block versteckt das Panel über das `hidden`-Attribut, und `hidden` heißt
`display: none`. **Eine Höhen-Animation des aufklappenden Inhalts geht damit nicht** — nicht
ohne `hidden` zu überschreiben, was das Panel für Screenreader sichtbar machen würde, obwohl
es zu ist. Das wäre ein echter Barrierefreiheits-Fehler für einen kosmetischen Gewinn.

Für das, was du wolltest, spielt das keine Rolle: Pfeildrehung und Farbwechsel sitzen am
Icon in der Kopfzeile, und die ist immer sichtbar. Beides animiert sauber.

---

## Was das Plugin macht

Es registriert einen Blockstil **„Stellenanzeige"** für `core/accordion`. Du wählst ihn im
Editor rechts unter *Stile* aus. Andere Accordions auf der Seite bleiben unberührt — das CSS
greift nur über `.is-style-ullmer-job`.

Das Stylesheet lädt über `wp_enqueue_block_style()`, also nur auf Seiten, auf denen der
Accordion-Block tatsächlich vorkommt.

### Pfeil und Farben

Der Pfeil ist **keine Bilddatei**, sondern eine CSS-Maske. Das ist der Trick, der die
Animation überhaupt möglich macht:

Die beiden Figma-Exporte (`assets/figma/`) haben **identische Pfade** und unterscheiden sich
nur in zwei Farbwerten — geschlossen ist der Kreis transparent mit cyanem Rand und cyanem
Pfeil, offen ist der Kreis cyan gefüllt mit Pfeil in `#F4F5F6`. Mit zwei fertigen SVG-Dateien
könnte man dazwischen nur hart umschalten. Als Maske gerendert sind Kreisfüllung und
Pfeilfarbe getrennte CSS-Eigenschaften und faden weich.

Die Drehung sind 180°: der Pfad zeigt im Original nach unten links (aufgeklappt), zugeklappt
wird er um 180° gedreht und zeigt nach oben rechts.

Gemessen am Übergang: 313 ms, Kreis von `transparent` auf `#23BAE2`, Pfeil von `#23BAE2` auf
`#F4F5F6`, Drehung gleichmäßig durch alle Zwischenwinkel.

Bei `prefers-reduced-motion: reduce` bleibt der Farbwechsel, nur die Drehung schaltet hart —
die Drehung ist die Bewegung, die stört, die Farbe nicht.

---

## Installation

1. Ordner nach `wp-content/plugins/` kopieren
2. Plugin aktivieren
3. Accordion-Block einfügen, rechts unter *Stile* **„Stellenanzeige"** wählen

Voraussetzung: WordPress **6.9+** (davor gibt es `core/accordion` nicht).

---

## Design-Werte

Aus Component-Set `158:719` des Figma-Files:

| | |
|---|---|
| Karte | `#F4F5F6`, Radius `40px` |
| Abstand zwischen Items | `14px` |
| Innenabstand | `20px` oben/unten, `40px` links, `20px` rechts |
| Titel | Merriweather Bold `25px`, `#23BAE2` |
| Fließtext | Inter Regular `18px` / `26px`, schwarz |
| Icon | `71px`, Ring `2px` `#23BAE2` |
| Icon geschlossen | Kreis transparent, Pfeil `#23BAE2`, um 180° gedreht |
| Icon offen | Kreis `#23BAE2`, Pfeil `#F4F5F6`, ungedreht |

Alles hängt an CSS-Custom-Properties auf `.wp-block-accordion.is-style-ullmer-job` und lässt
sich im Theme überschreiben:

```css
.wp-block-accordion.is-style-ullmer-job {
	--ullmer-accent: #1b94b4;
	--ullmer-duration: 0.2s;
}
```

Das Plugin lädt keine Schriften — Merriweather und Inter kommen aus dem Theme.

---

## Exportierte Assets

Unter `assets/figma/` als Referenz, nicht vom Plugin geladen:

| Datei | Inhalt |
|---|---|
| `pfeil-button-closed-outline.svg` | Icon geschlossen, Kreis als Outline |
| `pfeil-button-open-filled.svg` | Icon offen, Kreis gefüllt |
| `accordion-beide-zustaende.png` | Component-Set, beide Zustände |
| `jobs-section-referenz.png` | Die Stellen-Sektion im Kontext |

---

## Grenzen

- Getestet mit einer nachgebauten Testseite, die das Core-Markup nachbildet — **nicht in einer
  laufenden WordPress-6.9-Installation.** Die Klassennamen stammen aus der offiziellen
  Dokumentation und dem WordPress-Developer-Blog; falls Core sie ändert, bricht das CSS still.
- Die Kartenoptik geht davon aus, dass das Theme dem Accordion-Item keine eigene
  Hintergrundfarbe oder Rahmen aufzwingt. Falls doch, muss die Spezifität hoch.
- Keine Höhen-Animation des Panels, siehe oben.

---

## Hersteller

Entwickelt und vertrieben von **exzent** — <https://exzent.de/>

Das Design stammt aus dem Ullmer-Website-Redesign. Die Bezeichner im Code
(`.is-style-ullmer-job`, `--ullmer-*`) benennen dieses Projekt und sind keine
Herstellerangabe.

## Lizenz

GPL-2.0-or-later · © exzent
