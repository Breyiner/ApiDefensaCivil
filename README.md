## PHP y COMPOSER | instalación y preparación
1. Instalación PHP
```script
     Para manejar el backend lo principal que se necesita Instalar y buscar COMPOSER y PHP (8.5.0)
Se instala el php thread save (.zip), se abre variables del entorno del sistema, se ingresa la contraseña y se ingresa la variable del entorno, se selecciona "nuevo" y se ingresa la direccion de la carpeta, en estecaso php, para guardarlo. Para saber si la instalación de php fue exitosa entramos en la consola, como git bash, y se escribe "php -v" para asegurarnos que este presente como variable en el sistema.
```
2. Instalación Composer
```script
     A la hora de instalar composer solicitara una ubicación, buscamos la carpeta php y seleccionamos el ejecutable de php con destino.
Con el composer realizaremos todas las peticiones e instalación de laravel.
```

3. Habilitar PHP
```script
     Ejecutamos bloc de notas como administrador, luego abrimos en este un archivo de la carpeta php llamado de la misma forma, pero el icono tiene una tuerca, una vez dentro con el buscador del bloc (ctrl + F), se busca "extension=zip", "extension=pdo_mysql" y "extension=fileinfo", a cada una de estas se le borra el punto y coma (;) del principio y guardamos.
Luego de todo ello reiniciamos todo.
```

3. Instalación de composer en el proyecto y migración de datos PHP a la base de datos
```script
     Tras lo anterior procedemos a acceder desde consola al proyecto backend, como lo es el api de Defensa Civil, una vez nos ubicamos escribimos "composer i" o "composer install".
     tras la instalación de composer en el proyecto api entramos al archivo ".env" y colocamos la contraseña y el usuario de la base de datos previamente creada (MySQL, mariaDB, etc) en "DB_PASSWORD=" y "DB_USERNAME=", Despues de ello ingresa los siguientes comando en consola:

        1. php artisan migrate --seed (El comando realizara la clonación del proyecto en caso de no haber una base de datos, lo que me permitira que esta se cree junto a todos sus elementos en la base de datos seleccionada)

        2. php artisan migrate:refresh --seed (Esta se utiliza cuando se hace un cambio en el backend relacionado con las bases de datos, lo que hace es borrar la anterior migración y sustituirla por una nueva con nuevos datos)

Para iniciar el servidor se debe ingresar en consola "php artisan serve" o "php artisan serve --host=localhost"
Para cerrar cuanquier proceso de serve en git bash se ingresa el comando "ctrl + c"
```
## ! ENV para mantener seción en dominio

```script
    Se necesita poder manejar bien las token refresh en el dominio para evitar que la sesión se expire en lugar de refrescarse, como alternativa se podria aumentar el tiempo de duración de la sesión antes de su expiración

```