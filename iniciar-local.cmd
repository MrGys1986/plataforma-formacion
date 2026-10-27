@echo off
setlocal
cd /d "%~dp0"

if not defined FORMACION_PHP set "FORMACION_PHP=D:\php84\php.exe"
if not exist "%FORMACION_PHP%" (
    echo No se encontro PHP en "%FORMACION_PHP%".
    echo Define FORMACION_PHP con la ruta de tu php.exe y vuelve a ejecutar este archivo.
    exit /b 1
)

"%FORMACION_PHP%" -r "$socket = @fsockopen('127.0.0.1', 8000, $errno, $error, 1); if ($socket) { fclose($socket); fwrite(STDERR, 'El puerto 8000 ya esta ocupado. Deten el servidor anterior antes de iniciar otro.'.PHP_EOL); exit(1); }"
if errorlevel 1 exit /b 1

echo Preparando las vistas de la plataforma...
"%FORMACION_PHP%" -d opcache.enable_cli=0 -d memory_limit=256M artisan view:cache
if errorlevel 1 exit /b 1

echo Abre http://127.0.0.1:8000/login
echo Para detener el servidor, presiona Ctrl+C.
cd public
"%FORMACION_PHP%" -d opcache.enable_cli=0 -d memory_limit=256M -d max_execution_time=60 -S 127.0.0.1:8000 "..\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php"
exit /b %errorlevel%
