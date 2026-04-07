<?php

/**
 * ============================================================================
 * RUTAS API PRINCIPAL
 * ============================================================================
 *
 * Este archivo está intencionalmente vacío.
 *
 * Las rutas de la API fueron separadas por módulos y se cargan
 * automáticamente desde bootstrap/app.php.
 *
 * Estructura actual:
 *
 * routes/api/
 * ├── account.php        → Gestión de cuenta (email, contraseña, verificación sensible)
 * ├── action-plans.php   → Planes de acción, tipos de acción y acciones asociadas
 * ├── audits.php         → Dashboards y métricas de auditoría
 * ├── auth.php           → Autenticación, verificación de email, recuperación de contraseña
 * ├── catalogs.php       → Catálogos protegidos del sistema
 * ├── family-plans.php   → Planes familiares, vivienda y gráficos
 * ├── geography.php      → Zonas, sectores, departamentos y ciudades
 * ├── members.php        → Miembros, parentescos, condiciones, nacionalidades, etc.
 * ├── notifications.php  → Notificaciones del sistema
 * ├── organizations.php  → Seccionales, organizaciones y perfiles
 * ├── pets.php           → Mascotas, especies, géneros animales y vacunas
 * ├── public.php         → Catálogos públicos sin autenticación
 * ├── resources.php      → Recursos y recursos disponibles por plan familiar
 * ├── risk.php           → Amenazas, factores de riesgo y acciones de reducción
 * ├── users.php          → CRUD de usuarios y acciones administrativas
 * └── vulnerability.php  → Vulnerabilidades, factores, grados, preguntas y test
 *
 * Carga automática:
 * - Rutas públicas:
 *   - auth.php
 *   - public.php
 *
 * - Rutas protegidas:
 *   - Todos los demás archivos dentro de routes/api/
 *
 * Middleware aplicado desde bootstrap/app.php:
 * - Públicas: api
 * - Protegidas: api + auth:sanctum + verified
 *
 * Nota:
 * Este archivo solo existe para conservar el punto de entrada
 * configurado en withRouting(), pero no define rutas directamente.
 */