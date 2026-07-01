[⬅ Volver al índice](../README.md)

# Supervisor y colas en producción

## ¿Por qué el envío de correos necesita Supervisor?

Cuando alguien se registra o pide recuperar su contraseña, el correo no se envía al instante: se guarda como tarea pendiente, y un proceso aparte (el *worker*) se encarga de enviarlo. Si ese proceso no está corriendo, el correo nunca sale, sin ningún error visible.

En desarrollo, ese proceso se deja corriendo en una terminal abierta. Pero en producción eso no sirve: el servidor puede reiniciarse solo (corte de luz, mantenimiento, error del sistema), y nadie está ahí para volver a encenderlo a mano. Si depende de que alguien se acuerde, tarde o temprano se queda caído sin que nadie lo note.

## La solución: Supervisor

Supervisor es una herramienta que se instala en el servidor (no en el proyecto) y vigila el proceso de correos: lo arranca solo cuando el servidor prende, y si se cae, lo vuelve a levantar sin que nadie intervenga.

## Paso a paso (en el servidor)

```bash
# 1. Instalar
sudo apt-get update
sudo apt-get install supervisor

# 2. Que arranque solo con el sistema (el paso más importante)
sudo systemctl enable supervisor

# 3. Configurar qué proceso debe vigilar
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

Contenido del archivo:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/al/proyecto/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
stdout_logfile=/ruta/al/proyecto/storage/logs/worker.log
```

```bash
# 4. Activar
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*

# 5. Confirmar que corre
sudo supervisorctl status
```

## Prueba real (no solo teoría)

```bash
sudo reboot
```

Cuando el servidor vuelva, sin escribir nada más:

```bash
sudo supervisorctl status
```

Si aparece `RUNNING` sin intervención manual, queda comprobado que sobrevive un reinicio real.

## Documentos relacionados

- [Correos de verificación y recuperación (local)](correo-verificacion-recuperacion.md)
- [Colas en producción: configuración detallada](colas-produccion-servidor.md)

---

[⬅ Volver al índice](../README.md)