[⬅ Volver al índice](../README.md)

# Instalación de Node.js (Librerías y dependencias)

Node.js es un entorno de ejecución de código abierto que permite ejecutar código JavaScript fuera del navegador web.

Descargamos el instalador (.msi) de Node.js en **https://nodejs.org/es/download**, para luego realizar el proceso de instalación simple.

Una vez instalado, para incorporarlo en el proyecto nos ubicamos en este y escribimos:

```script
node -v
```

para confirmar la versión de Node, y:

```script
npm -v
```

para confirmar la versión de npm.

Para instalar paquetes o librerías externas en tu proyecto, utiliza el comando:

```script
npm install
```

### 1. Instalar una librería específica

Por ejemplo, si necesitas instalar Express (un framework web muy popular), escribe:

```script
npm install express
```

### 2. Resultado

Al hacerlo, npm descargará los archivos en una carpeta llamada `node_modules` y actualizará automáticamente tu archivo `package.json`.

---

Siguiente: [Configuración del proyecto y base de datos →](configuracion-proyecto.md)
