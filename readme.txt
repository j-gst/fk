/**
* Installationsanleitung
* @Author: Gerrit Storm
*/


***** System Requirements ********************************************

PHP: ab Version 5.3.0
MySQL: ab Version 5.5.24
WebServer: empfohlen Apache ab Version 2.2.22

***** Installstion ********************************************

- Den Inhalt von pages in das Server Root Verveichnisses, 
  oder ein beliebiges Unterverzeichnis, kopieren.
  
- Den Ordner fk_app ausserhalb des Server Root Verveichnisses platzieren.
- In der Datei index.php den Pfad zum Applikationsverzeichnis (fk_app) eintragen.

- Einen Ordner fuer die hochgeladenen Bilder ausserhalb des Server Root Verveichnisses platzieren.
- In der Datei index.php den Pfad zum Bilderverzeichnis eintragen.


***** Konfiguration PHP  *******************************************************


Folgende Anpassungen der php.ini sind noetig:

; Einbinden des php_fileinfo Moduls 
; http://php.net/manual/en/book.fileinfo.php
extension=php_fileinfo.dll

; Anpassen des Wertes fuer maximale Dateigroesse beim Upload auf einen gewuenschten Wert
; Maximum allowed size for uploaded files.
; http://php.net/upload-max-filesize
upload_max_filesize = 512M



Folgende Werte m�ssen gegebenenfalls angepasst werden:


; Temporary directory for HTTP uploaded files (system default if not specified).
; http://php.net/upload-tmp-dir
upload_tmp_dir = "/path/to/tmp"


; Maximum execution time 
; http://php.net/max-execution-time
max_execution_time = 500     


; Maximum amount of memory a script may consume (128MB)
; http://php.net/memory-limit
memory_limit = 512M

***** besondere Berechtigungen  **************************************************

Folgende Rechte muessen korrekt gesetzt sein:

PHP upload_tmp_dir (s.o.) lesen, schreiben, loeschen
Verzeichnis fuer die hochgeladenen Bilder lesen, schreiben, loeschen





