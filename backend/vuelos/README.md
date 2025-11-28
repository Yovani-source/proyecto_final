# Microservicio Vuelos

Base URL: http://127.0.0.1:9000/vuelos

## Dependencias
- PHP 7.4+
- Composer
- Slim 4
- illuminate/database (Eloquent)
- MySQL

## Instalación
1. `composer install`
2. Configurar variables de BD (archivo .env o editar app/Config/data-BS.php)
3. Ejecutar migraciones / importar SQL
4. Levantar servidor:
   cd backend/vuelos/public
   php -S 127.0.0.1:9000 -t .
