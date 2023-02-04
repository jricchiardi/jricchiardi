@echo off
set /p id="Ingrese el nombre de la migration: "

cmd /k yii migrate/create %id%