[⬅ Volver al índice](../README.md)

# Variables de entorno (.env)

El archivo `.env` se encuentra en la raíz del proyecto y contiene toda la configuración sensible del entorno. **Este archivo no debe subirse al repositorio.** A continuación se describen todas las variables disponibles.

## Aplicación

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `APP_NAME` | `Laravel` | Nombre de la aplicación |
| `APP_ENV` | `local` | Entorno de ejecución (`local`, `production`, `testing`) |
| `APP_KEY` | *(vacío)* | Clave de cifrado de la app. Se genera con `php artisan key:generate` |
| `APP_DEBUG` | `true` | Activa el modo debug. Debe ser `false` en producción |
| `APP_URL` | `http://localhost` | URL base de la aplicación |
| `APP_LOCALE` | `en` | Idioma principal de la aplicación |
| `APP_FALLBACK_LOCALE` | `en` | Idioma de respaldo si no se encuentra una traducción |
| `APP_FAKER_LOCALE` | `en_US` | Idioma usado por Faker para generar datos de prueba |

## Base de datos

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `DB_CONNECTION` | `mysql` | Motor de base de datos (`mysql`, `pgsql`, `sqlite`) |
| `DB_HOST` | *(host)* | Host del servidor de base de datos (ej. `127.0.0.1`) |
| `DB_PORT` | *(port)* | Puerto del servidor de base de datos |
| `DB_DATABASE` | *(nombre)* | Nombre de la base de datos |
| `DB_USERNAME` | *(vacío)* | Usuario de la base de datos |
| `DB_PASSWORD` | *(vacío)* | Contraseña de la base de datos |

## Sesión

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `SESSION_DRIVER` | `database` | Driver de almacenamiento de sesiones |
| `SESSION_LIFETIME` | `120` | Tiempo de vida de la sesión en minutos |
| `SESSION_ENCRYPT` | `false` | Activa el cifrado de los datos de sesión |
| `SESSION_PATH` | `/` | Ruta de la cookie de sesión |
| `SESSION_DOMAIN` | `null` | Dominio de la cookie de sesión |

## Autenticación y tokens

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `ACCESS_TOKEN_EXPIRATION` | *(vacío)* | Tiempo de expiración del token de acceso |
| `REFRESH_TOKEN_EXPIRATION` | *(vacío)* | Tiempo de expiración del token de refresco |

> Estas dos variables controlan la duración de las sesiones autenticadas. Si se dejan vacías, se usarán los valores por defecto del paquete de autenticación instalado. Ver también [Sesiones y tokens](sesiones-tokens.md).

## Logs

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `LOG_CHANNEL` | `stack` | Canal principal de logs |
| `LOG_STACK` | `single` | Canales que componen el stack de logs |
| `LOG_LEVEL` | `debug` | Nivel mínimo de log (`debug`, `info`, `warning`, `error`) |

## Correo electrónico

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `MAIL_MAILER` | `log` | Driver de envío de correos (`log`, `smtp`, `sendmail`) |
| `MAIL_HOST` | `127.0.0.1` | Host del servidor de correo |
| `MAIL_PORT` | `2525` | Puerto del servidor de correo |
| `MAIL_USERNAME` | `null` | Usuario para autenticación SMTP |
| `MAIL_PASSWORD` | `null` | Contraseña para autenticación SMTP |
| `MAIL_FROM_ADDRESS` | `hello@example.com` | Dirección de remitente por defecto |
| `MAIL_FROM_NAME` | `${APP_NAME}` | Nombre del remitente por defecto |

## Caché y colas

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `CACHE_STORE` | `database` | Driver de caché (`database`, `redis`, `file`) |
| `QUEUE_CONNECTION` | `database` | Driver de colas de trabajo |
| `BROADCAST_CONNECTION` | `log` | Driver de broadcasting de eventos |
| `FILESYSTEM_DISK` | `local` | Disco de almacenamiento de archivos |

## Redis

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `REDIS_CLIENT` | `phpredis` | Cliente PHP para Redis |
| `REDIS_HOST` | `127.0.0.1` | Host del servidor Redis |
| `REDIS_PASSWORD` | `null` | Contraseña de Redis (si aplica) |
| `REDIS_PORT` | `6379` | Puerto de Redis |

## AWS (opcional)

| Variable | Descripción |
|---|---|
| `AWS_ACCESS_KEY_ID` | ID de clave de acceso de AWS |
| `AWS_SECRET_ACCESS_KEY` | Clave secreta de AWS |
| `AWS_DEFAULT_REGION` | Región por defecto (`us-east-1`) |
| `AWS_BUCKET` | Nombre del bucket S3 |

## Mantenimiento

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `APP_MAINTENANCE_DRIVER` | `file` | Driver para el modo mantenimiento |
| `BCRYPT_ROUNDS` | `12` | Número de rondas de cifrado para contraseñas |

---

Siguiente: [Sesiones y tokens →](sesiones-tokens.md)
