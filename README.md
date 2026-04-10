# CosmosAPI API REST

API REST construida con Laravel 12, centrada en el manejo de misiones espaciales (`SpaceMission`) con versión de API, autenticación y documentación automática.

## Resumen del proyecto

- Laravel 12 con estructura limpia y modular.
- Laravel Sanctum para autenticación API y gestión de tokens.
- Endpoints versionados bajo `api/v1`.
- CRUD completo para el modelo `SpaceMission`.
- Validaciones robustas en create/update.
- Respuestas JSON unificadas mediante middleware global.
- Filtros avanzados y paginación en listados.
- Tests automatizados por caso de uso.
- Documentación automática con Scribe y Scramble.

## Características principales

- Modelo `SpaceMission` con migración, factory y seeder.
- API Resources (`SpaceMissionResource`) para respuesta de datos.
- Query Builder y filtros por campos como `destination`, `status`, `mission_type` y `budget_millions`.
- Patrones de diseño:
  - Actions para `create`, `update` y `delete`.
  - Query builder/filtros para mantener controladores limpios.
  - Middleware de respuesta API aplicado globalmente.
- Auth token con Sanctum para proteger los endpoints.

## Estructura destacada

- `app/Models/SpaceMission.php`
- `app/Http/Controllers/V1/SpaceMissionController.php`
- `app/Http/Middleware/V1/ApiResponseMiddleware.php`
- `app/Http/Resources/V1/SpaceMissionResource.php`
- `app/Actions/V1/SpaceMission/`
- `app/Builders/SpaceMissionQueryBuilder.php`
- `app/Filters/`
- `routes/api.php`
- `routes/api/v1.php`
- `config/scribe.php`
- `config/scramble.php`

## Entorno local

El proyecto está preparado para ejecutarse en Docker con Docker Compose y Sail.

Servicios relevantes:

- `laravel.test` – servidor web PHP/Apache con la aplicación Laravel.
- `mysql` – base de datos MySQL.
- `phpmyadmin` – interfaz web para gestionar la base de datos.
- `mailpit` – servidor SMTP de pruebas.

## Notas de configuración de phpMyAdmin

- El contenedor MySQL carga scripts de inicialización desde `./docker/mysql/`.
- `docker/mysql/create-phpmyadmin-database.sh` crea la base de datos `phpmyadmin` y aplica el esquema de phpMyAdmin.
- `docker/mysql/create-phpmyadmin-tables.sql` define las tablas internas de phpMyAdmin (`pma__*`).
- El servicio `phpmyadmin` solo necesita conectar con MySQL (`PMA_HOST`, `PMA_PORT`, `PMA_PMADB`).

## Comandos útiles

- `sail artisan migrate:fresh --seed`
- `sail artisan migrate`
- `sail artisan db:seed`
- `sail test`
- `sail artisan route:list`
- `sail artisan scribe:generate`

## Ejemplos de endpoints

La API está disponible bajo `http://localhost:80/api/v1` y utiliza autenticación con tokens Bearer de Laravel Sanctum.

### Autenticación

- `POST /api/v1/login`
- Payload ejemplo:

  ```json
  {
    "email": "user@example.com",
    "password": "password"
  }
  ```

- Respuesta esperada:

  ```json
  {
    "token": "<token de acceso>",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "Example User",
      "email": "user@example.com"
    }
  }
  ```

### Usar el token Bearer

Para todas las rutas protegidas, envía la cabecera:

- `Authorization: Bearer <token>`

Ejemplo con `curl`:

```bash
curl -H "Authorization: Bearer <token>" \
  -H "Accept: application/json" \
  http://localhost:80/api/v1/space-missions
```

### Endpoints públicos

- `POST /api/v1/login` — iniciar sesión y obtener token.

### Endpoints protegidos

- `POST /api/v1/logout` — cerrar sesión y revocar el token.
- `GET /api/v1/space-missions` — listado paginado de misiones.
- `GET /api/v1/space-missions/{space_mission}` — detalle de una misión.
- `POST /api/v1/space-missions` — crear nueva misión.
- `PUT /api/v1/space-missions/{space_mission}` — actualizar misión.
- `PATCH /api/v1/space-missions/{space_mission}` — actualizar parcialmente misión.
- `DELETE /api/v1/space-missions/{space_mission}` — eliminar misión.
- `GET /api/v1/space-missions/index-with-pipeline` — listado con pipeline de filtros adicional.

### Payload de ejemplo para crear una misión

```json
{
  "name": "Mars Exploration",
  "destination": "Mars",
  "mission_type": "Exploration",
  "status": "planned",
  "budget_millions": 250
}
```

### Payload de ejemplo para actualizar una misión

```json
{
  "status": "in_progress",
  "budget_millions": 300
}
```

## Documentación de la API

- Scribe para generar documentación estática y configurada con autenticación Sanctum.
- Scramble para documentación automática en tiempo real.
- Ambas opciones ayudan a documentar los endpoints de `SpaceMission` y otros recursos de la API.

## Objetivo del proyecto

Construir una API REST moderna y bien estructurada con Laravel 12, enfocado en:

- buen diseño de código,
- pruebas automatizadas,
- versionado de API,
- control de autenticación,
- generación automática de documentación.
