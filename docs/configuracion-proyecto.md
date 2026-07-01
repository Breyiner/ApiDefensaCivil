[⬅ Volver al índice](../README.md)

# Configuración del proyecto y base de datos

Tras completar la [instalación de PHP y Composer](instalacion-php.md), procedemos a acceder desde la consola (Git Bash) al directorio local de nuestro proyecto backend, como lo es la API de Defensa Civil. Una vez nos ubicamos dentro de la carpeta del proyecto, escribimos el siguiente comando para instalar las dependencias:

```script
composer install
```

(También se puede utilizar su abreviación `composer i`).

## Configurar el .env

Tras la instalación exitosa de Composer en el proyecto de la API, entramos al archivo de configuración ambiental denominado `.env` ubicado en la raíz. Buscamos las variables correspondientes y colocamos la contraseña y el usuario de la base de datos previamente creada (en gestores como MySQL, MariaDB, etc.) en los campos correspondientes:

```Plaintext
DB_USERNAME=tu_usuario_aqui
DB_PASSWORD=tu_contraseña_aqui
```

> Ver la referencia completa de todas las variables en [Variables de entorno (.env)](variables-entorno.md).

## Migración de datos

Después de guardar los cambios en el archivo `.env`, ingresa los siguientes comandos en la consola según el flujo que necesites:

### 1. Clonación y creación inicial de la base de datos

El siguiente comando realizará la clonación del proyecto en caso de no haber una base de datos activa, lo que permitirá que esta se cree desde cero junto a todos sus elementos y registros iniciales en el motor seleccionado:

```script
php artisan migrate --seed
```

### 2. Actualización por cambios en el Backend

Este comando se utiliza específicamente cuando se hace un cambio en el backend relacionado de manera directa con las estructuras de las bases de datos. Su función es borrar por completo la migración anterior y sustituirla por una totalmente nueva con los nuevos datos actualizados:

```script
php artisan migrate:refresh --seed
```

---

Siguiente: [Servidor de desarrollo →](servidor-desarrollo.md)
