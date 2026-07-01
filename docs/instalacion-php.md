[⬅ Volver al índice](../README.md)

# Instalación de PHP y Composer

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

**Importante:** Luego de realizar todo ello, reiniciamos el sistema o la consola para aplicar los cambios.

---

Siguiente: [Instalación de Node.js →](instalacion-node.md)
