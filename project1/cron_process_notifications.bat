@echo off
echo Starting WebSocket notification processor...
cd /d C:\laragon\www\project1

:loop
echo Processing notifications at %time%...
php process_notifications.php
timeout /t 2 /nobreak > nul
goto loop 