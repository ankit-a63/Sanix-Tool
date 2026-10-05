@echo off
title Sanix Tool - Hosts File Setup
echo ===================================================
echo Adding sanix-tool.local to Windows hosts file...
echo ===================================================
echo.

:: Check for administrative permissions
net session >nul 2>&1
if %errorLevel% == 0 (
    echo Administrator permissions confirmed.
    attrib -r C:\Windows\System32\drivers\etc\hosts
    
    findstr /c:"sanix-tool.local" C:\Windows\System32\drivers\etc\hosts >nul
    if %errorlevel% == 0 (
        echo [INFO] sanix-tool.local is already in hosts file!
    ) else (
        echo. >> C:\Windows\System32\drivers\etc\hosts
        echo 127.0.0.1    sanix-tool.local >> C:\Windows\System32\drivers\etc\hosts
        echo [SUCCESS] Added "127.0.0.1 sanix-tool.local" to hosts file!
    )
    
    ipconfig /flushdns >nul
    echo [SUCCESS] Windows DNS cache flushed!
    echo.
    echo ===================================================
    echo DONE! Now open your browser and go to:
    echo http://sanix-tool.local/
    echo ===================================================
) else (
    echo [ERROR] This script MUST be run as Administrator!
    echo.
    echo Please right-click this file "Add_Sanix_Hosts.bat"
    echo and select "Run as administrator".
)

echo.
pause
