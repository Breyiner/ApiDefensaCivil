![Banner Defensa Civil](public/assets/images/logos/covermd.png)

# API Defensa Civil | Guía de instalación y configuración

## 1. Instalación PHP
Para manejar el backend lo principal que se necesita instalar y buscar es **COMPOSER** y **PHP (8.5.0)**.

Se instala el PHP en su versión **Thread Safe (.zip)**. Una vez descargado y descomprimido, se procede con los siguientes pasos en el sistema:
1. Se abre **Variables de entorno del sistema**.
2. Se ingresa la contraseña de administrador en caso de ser solicitada.
3. Se ingresa a las variables de entorno propiamente dichas.
4. En las variables del sistema, se selecciona la variable `Path`, se hace clic en **"Nuevo"** y se ingresa la dirección exacta de la carpeta donde se descomprimió el programa (en este caso, la carpeta de PHP) para guardarlo.

Para saber si la instalación de PHP fue exitosa, entramos en una consola (como **Git Bash**) y ejecutamos el siguiente comando para asegurarnos de que esté presente como variable en el sistema:

```script
php -v
```

## 2. Instalación Composer
A la hora de **instalar Composer**, el asistente de instalación solicitará una ubicación en el disco. Para configurarlo correctamente:

Buscamos la carpeta de PHP previamente configurada.

Seleccionamos el ejecutable **php.exe** como el destino requerido.

Con el Composer ya instalado en el sistema, realizaremos todas las peticiones futuras y la instalación de Laravel.

## 3. Habilitar PHP
Para habilitar los módulos necesarios de PHP que requiere el ecosistema de Laravel, realizamos el siguiente procedimiento de edición:

Ejecutamos el Bloc de notas **con permisos de administrador**.

Desde el Bloc de notas, abrimos el archivo de configuración dentro de la carpeta PHP llamado exactamente de la misma forma (php.ini), cuyo icono característico tiene una tuerca.

Una vez dentro del archivo, abrimos el buscador del bloc presionando la combinación de teclas:

```Plaintext
Ctrl + F
```
Buscamos individualmente los siguientes términos:

```Plaintext

extension=zip

extension=pdo_mysql

extension=fileinfo

```

A cada una de estas líneas encontradas se le borra el punto y coma (;) ubicado al puro principio de la línea y guardamos el archivo.

Importante: Luego de realizar todo ello, reiniciamos el sistema o la consola para aplicar los cambios.


## 4. Instalación de composer en el proyecto y migración de datos PHP a la base de datos
Tras completar los pasos anteriores, procedemos a acceder desde la consola (Git Bash) al directorio local de nuestro proyecto backend, como lo es la API de Defensa Civil. Una vez nos ubicamos dentro de la carpeta del proyecto, escribimos el siguiente comando para instalar las dependencias:

```script
composer install
```
(También se puede utilizar su abreviación composer i).

Tras la instalación exitosa de Composer en el proyecto de la API, entramos al archivo de configuración ambiental denominado .env ubicado en la raíz. Buscamos las variables correspondientes y colocamos la contraseña y el usuario de la base de datos previamente creada (en gestores como MySQL, MariaDB, etc.) en los campos correspondientes:

```Plaintext

DB_USERNAME=tu_usuario_aqui
DB_PASSWORD=tu_contraseña_aqui

```

Después de guardar los cambios en el archivo .env, ingresa los siguientes comandos en la consola según el flujo que necesites:

#### 1. Clonación y creación inicial de la base de datos
El siguiente comando realizará la clonación del proyecto en caso de no haber una base de datos activa, lo que permitirá que esta se cree desde cero junto a todos sus elementos y registros iniciales en el motor seleccionado:

```script
php artisan migrate --seed
```

#### 2. Actualización por cambios en el Backend
Este comando se utiliza específicamente cuando se hace un cambio en el backend relacionado de manera directa con las estructuras de las bases de datos. Su función es borrar por completo la migración anterior y sustituirla por una totalmente nueva con los nuevos datos actualizados:

```script
php artisan migrate:refresh --seed
```


## 5. Control del Servidor de Desarrollo
Para levantar e iniciar el servidor de desarrollo local, se debe ingresar en la consola cualquiera de los siguientes comandos:

```script
php artisan serve
```
O especificando de forma explícita el host local:

```script
php artisan serve --host=localhost
```
Para cerrar o tumbar cualquier proceso activo del comando serve en Git Bash, se debe presionar la siguiente combinación de teclas en la terminal:

```Plaintext
Ctrl + C
```

## 6. instalación NODE.js (Librerias y dependencias)
Node.js es un entorno de ejecución de código abierto que permite ejecutar código JavaScript fuera del navegador web

Descargamos el instalador (.msi) de NODE.js en **https://nodejs.org/es/download** , para luego realizar el proceso de instalación simple.

una vez instalado para incorporarlo en el projecto nos ubicamos en este y escribimos:
```script
node -v
```
para confirmar la versión del node y
```script
npm -v
```
para confirmar la versión del npm.

Para instalar paquetes o librerías externas en tu proyecto, utiliza el comando:
```script
npm install
```

#### 1. Por ejemplo, si necesitas instalar Express (un framework web muy popular), escribe:
```script
npm install express
```
#### 2. Al hacerlo, npm descargará los archivos en una carpeta llamada node_modules y actualizará automáticamente tu archivo package.json.

## 7. Variables de entorno (.ENV)

El archivo `.env` se encuentra en la raíz del proyecto y contiene toda la configuración sensible del entorno. **Este archivo no debe subirse al repositorio.** A continuación se describen todas las variables disponibles:

### Aplicación

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

### Base de datos

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `DB_CONNECTION` | `mysql` | Motor de base de datos (`mysql`, `pgsql`, `sqlite`) |
| `DB_HOST` | *(host)* | Host del servidor de base de datos (ej. `127.0.0.1`) |
| `DB_PORT` | *port* | Puerto del servidor de base de datos |
| `DB_DATABASE` | *(nombre)* | Nombre de la base de datos |
| `DB_USERNAME` | *(vacío)* | Usuario de la base de datos |
| `DB_PASSWORD` | *(vacío)* | Contraseña de la base de datos |

### Sesión

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `SESSION_DRIVER` | `database` | Driver de almacenamiento de sesiones |
| `SESSION_LIFETIME` | `120` | Tiempo de vida de la sesión en minutos |
| `SESSION_ENCRYPT` | `false` | Activa el cifrado de los datos de sesión |
| `SESSION_PATH` | `/` | Ruta de la cookie de sesión |
| `SESSION_DOMAIN` | `null` | Dominio de la cookie de sesión |

### Autenticación y Tokens

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `ACCESS_TOKEN_EXPIRATION` | *(vacío)* | Tiempo de expiración del token de acceso |
| `REFRESH_TOKEN_EXPIRATION` | *(vacío)* | Tiempo de expiración del token de refresco |

> Estas dos variables controlan la duración de las sesiones autenticadas. Si se dejan vacías, se usarán los valores por defecto del paquete de autenticación instalado.

### Logs

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `LOG_CHANNEL` | `stack` | Canal principal de logs |
| `LOG_STACK` | `single` | Canales que componen el stack de logs |
| `LOG_LEVEL` | `debug` | Nivel mínimo de log (`debug`, `info`, `warning`, `error`) |

### Correo electrónico

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `MAIL_MAILER` | `log` | Driver de envío de correos (`log`, `smtp`, `sendmail`) |
| `MAIL_HOST` | `127.0.0.1` | Host del servidor de correo |
| `MAIL_PORT` | `2525` | Puerto del servidor de correo |
| `MAIL_USERNAME` | `null` | Usuario para autenticación SMTP |
| `MAIL_PASSWORD` | `null` | Contraseña para autenticación SMTP |
| `MAIL_FROM_ADDRESS` | `hello@example.com` | Dirección de remitente por defecto |
| `MAIL_FROM_NAME` | `${APP_NAME}` | Nombre del remitente por defecto |

### Caché y Colas

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `CACHE_STORE` | `database` | Driver de caché (`database`, `redis`, `file`) |
| `QUEUE_CONNECTION` | `database` | Driver de colas de trabajo |
| `BROADCAST_CONNECTION` | `log` | Driver de broadcasting de eventos |
| `FILESYSTEM_DISK` | `local` | Disco de almacenamiento de archivos |

### Redis

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `REDIS_CLIENT` | `phpredis` | Cliente PHP para Redis |
| `REDIS_HOST` | `127.0.0.1` | Host del servidor Redis |
| `REDIS_PASSWORD` | `null` | Contraseña de Redis (si aplica) |
| `REDIS_PORT` | `6379` | Puerto de Redis |

### AWS (opcional)

| Variable | Descripción |
|---|---|
| `AWS_ACCESS_KEY_ID` | ID de clave de acceso de AWS |
| `AWS_SECRET_ACCESS_KEY` | Clave secreta de AWS |
| `AWS_DEFAULT_REGION` | Región por defecto (`us-east-1`) |
| `AWS_BUCKET` | Nombre del bucket S3 |

### Mantenimiento

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `APP_MAINTENANCE_DRIVER` | `file` | Driver para el modo mantenimiento |
| `BCRYPT_ROUNDS` | `12` | Número de rondas de cifrado para contraseñas |

## 8. Configuración de variables .ENV para mantener sesión activa en el dominio
Se necesita poder manejar de manera correcta los Token Refresh directamente en el dominio con el fin de evitar que la sesión del usuario expire de forma prematura en lugar de refrescarse automáticamente.

Como alternativa técnica viable dentro del archivo .env, se podría aumentar deliberadamente el tiempo de duración predeterminado de la sesión antes de que esta alcance su expiración modificando el parámetro de tiempo de vida (configurado en minutos):

```Plaintext
    SESSION_LIFETIME=120
```