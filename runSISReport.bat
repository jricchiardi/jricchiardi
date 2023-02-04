@echo off
cd D:\sites\dow.forecast
echo %date% %time% >> D:\sites\dow.forecast\sisReport.log
yii job/create-sis-report --show-output >> D:\sites\dow.forecast\sisReport.log
