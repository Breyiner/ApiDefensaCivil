# PHP y COMPOSER | instalación y preparación

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


## 7. Configuración de variables .ENV para mantener sesión activa en el dominio
Se necesita poder manejar de manera correcta los Token Refresh directamente en el dominio con el fin de evitar que la sesión del usuario expire de forma prematura en lugar de refrescarse automáticamente.

Como alternativa técnica viable dentro del archivo .env, se podría aumentar deliberadamente el tiempo de duración predeterminado de la sesión antes de que esta alcance su expiración modificando el parámetro de tiempo de vida (configurado en minutos):

```Plaintext
    SESSION_LIFETIME=120
```