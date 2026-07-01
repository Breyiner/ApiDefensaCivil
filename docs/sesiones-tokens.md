[⬅ Volver al índice](../README.md)

# Sesiones y tokens

## Configuración de variables .env para mantener sesión activa en el dominio

Se necesita poder manejar de manera correcta los Token Refresh directamente en el dominio con el fin de evitar que la sesión del usuario expire de forma prematura en lugar de refrescarse automáticamente.

Como alternativa técnica viable dentro del archivo `.env`, se podría aumentar deliberadamente el tiempo de duración predeterminado de la sesión antes de que esta alcance su expiración, modificando el parámetro de tiempo de vida (configurado en minutos):

```Plaintext
SESSION_LIFETIME=120
```

> Ver también la referencia completa de variables relacionadas en [Variables de entorno (.env)](variables-entorno.md#autenticación-y-tokens).

---

Siguiente: [Correos de verificación y recuperación →](correo-queue.md)
