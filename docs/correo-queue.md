[⬅ Volver al índice](../README.md)

# Correos de verificación y recuperación

Este documento explica cómo funcionan los correos de **verificación de email** y **recuperación de contraseña** en desarrollo local, incluyendo el uso de Mailtrap y los procesos en segundo plano (colas) necesarios para que se envíen.

## 1. ¿Por qué se necesitan colas (queues)?

Los correos de verificación y recuperación no se envían de forma inmediata en el mismo request. Las notificaciones implementan `ShouldQueue`, lo que significa que Laravel:

1. Guarda el envío como un **job** en la tabla `jobs` de la base de datos (según `QUEUE_CONNECTION=database`).
2. No lo procesa hasta que un **worker** lo tome y lo ejecute.

Si no hay un worker corriendo, el correo nunca sale, el job se queda pendiente en la tabla `jobs` indefinidamente.

## 2. Levantar el worker en local

Para procesar la cola en desarrollo, se debe correr en una terminal aparte (dejándola abierta mientras se prueba):

```script
php artisan queue:work
```

Esto mantiene el proceso escuchando y enviando cada job apenas se genera (por ejemplo, al registrar un usuario o pedir recuperación de contraseña).

### Comandos útiles de colas

| Comando | Función |
|---|---|
| `php artisan queue:work` | Levanta un worker que procesa jobs continuamente |
| `php artisan queue:listen` | Similar a `work`, pero recarga cambios de código sin reiniciar manualmente (más lento, útil en desarrollo activo) |
| `php artisan queue:clear` | Elimina todos los jobs pendientes de la cola |
| `php artisan queue:failed` | Lista los jobs que fallaron tras agotar sus reintentos |
| `php artisan queue:retry all` | Reintenta todos los jobs fallidos |
| `php artisan queue:restart` | Reinicia los workers activos (necesario tras cambios en el código de los jobs) |

> **Importante:** si se modifica el código de una notificación o un job (por ejemplo `CustomVerifyEmail`), hay que correr `php artisan queue:restart` o reiniciar manualmente el `queue:work`, ya que el worker mantiene en memoria una copia del código cargada al iniciar.

## 3. Verificar jobs pendientes o fallidos

Para revisar manualmente el estado de la cola sin worker corriendo:

```script
php artisan tinker
>>> DB::table('jobs')->count();
>>> DB::table('failed_jobs')->count();
```

Si `jobs` acumula registros y no baja, confirma que el worker no está corriendo o se cayó.

## 4. Mailtrap para pruebas locales

En local, el envío real de correos se prueba con [Mailtrap](https://mailtrap.io) (modo Sandbox), que **intercepta** los correos sin enviarlos a una bandeja real — todos quedan visibles en el panel web de Mailtrap. Esto es solo para testeo; en producción se debe usar un proveedor SMTP real (ver documentación de despliegue).

### Configuración en `.env` (solo local)

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_de_mailtrap
MAIL_PASSWORD=tu_password_de_mailtrap
```

Estas credenciales se obtienen desde el panel de Mailtrap: **Email Testing → Inboxes → tu inbox → SMTP**.

| Campo | Valor |
|---|---|
| Host | `sandbox.smtp.mailtrap.io` |
| Puerto | `25`, `465`, `587` o `2525` |
| Auth | `PLAIN`, `LOGIN` o `CRAM-MD5` |
| TLS | Opcional (STARTTLS disponible en todos los puertos) |

> ⚠️ Cada integrante del equipo tiene su propio usuario/contraseña de Mailtrap (sandbox individual o compartido del proyecto). No se debe subir el `.env` con estas credenciales al repositorio — cada quien las coloca localmente.

## 5. Flujo completo en local

1. Un usuario se registra o solicita recuperar contraseña.
2. Laravel dispara la notificación (`CustomVerifyEmail` o el reset de contraseña).
3. Como la notificación implementa `ShouldQueue`, se crea un job en la tabla `jobs`.
4. El worker (`php artisan queue:work`) toma el job y ejecuta el envío usando la configuración `MAIL_*` del `.env`.
5. Al estar apuntando a Mailtrap, el correo no llega a una bandeja real — aparece en el inbox del panel de Mailtrap para poder revisar el contenido, el asunto y el enlace generado.

---

Siguiente: [Supervisor y colas en producción →](supervisor-queue.md)