@echo off
rem Dumb-proof migrating, expects this to be installed in xampp/htdocs/k16 (exact folder names are not required)

echo "> Trying global"
php artisan migrate --env=local

if ERRORLEVEL 1 (
	echo "> Fallback: Trying relative"
	..\..\php\php artisan migrate --env=local
)
if ERRORLEVEL 1 (
	echo "Sorry bro, but I have no idea where your php is. (Try adding it to PATH)"
)
