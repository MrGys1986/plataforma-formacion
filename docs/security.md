# Seguridad

## Decisiones implementadas

- Las llaves `id` autoincrementales se conservan para relaciones internas.
- Las rutas sensibles resuelven modelos mediante `public_id` ULID.
- Las Policies y `visibleTo()` aplican propiedad, rol, área, oferta interna/externa y asignación de evaluador.
- Filament falla cerrado para modelos sin Policy, filtra registros y relaciones por usuario, y no ofrece borrado físico.
- Evidencias, constancias, comprobantes y otros archivos sensibles permanecen en discos privados.
- Las descargas requieren autenticación, URL firmada temporal, Policy, existencia física y auditoría.
- La verificación pública de constancias sólo muestra folio, validez, titular, actividad, tipo, fecha e institución.
- La API de microcredenciales requiere firma y rate limit; sólo expone `public_id`.
- El login social vincula `google_id` o `microsoft_id` además del correo para evitar sustitución de identidad.
- El registro público sólo acepta Google verificado con dominio personal `@gmail.com` y fuerza el rol `Externo`.
- Login, descargas, verificación pública, API y portales autenticados tienen límites de frecuencia.
- Las respuestas incluyen headers contra MIME sniffing, framing y filtración excesiva de referencias.
- La auditoría elimina valores sensibles y nunca interrumpe el flujo principal si falla.

## Datos sensibles

- CURP, teléfono, rutas, credenciales, payloads externos y referencias de pago no se escriben en auditoría.
- CURP no aparece en tablas generales ni en la validación pública.
- `json_payload` y `external_response` no aparecen en listados de Filament.
- `masked_curp` y `masked_email` están disponibles en `User` para futuras vistas autorizadas.

## Producción

- Configurar `APP_ENV=production` y `APP_DEBUG=false`.
- Usar HTTPS y `SESSION_SECURE_COOKIE=true`.
- Mantener `SESSION_HTTP_ONLY=true` y `SESSION_SAME_SITE=lax` o `strict` según integraciones.
- Mantener `FILESYSTEM_DISK` en almacenamiento privado; no mover evidencias a `public/`.
- Ajustar `SECURITY_UPLOAD_MAX_KB` y `SECURITY_SIGNED_URL_MINUTES` según política institucional.
- Ejecutar colas con un usuario de sistema restringido y rotar secretos fuera del repositorio.

## Pendientes deliberados

- MFA y recuperación reforzada.
- `auth:sanctum` o token dedicado para operaciones futuras de envío/estado de microcredenciales.
- Firma criptográfica del payload, reintentos en queue y rotación de credenciales externas.
- Antivirus/antimalware y validación de contenido para archivos cargados.
- Cifrado de campos de alta sensibilidad y política institucional de retención.

## Verificación local

```powershell
php artisan migrate
php artisan route:list
php -d extension=php_pdo_sqlite.dll -d extension=php_sqlite3.dll vendor\bin\phpunit
vendor\bin\pint --test
npm run build
```

Para que `php artisan test` funcione sin opciones adicionales en Windows, habilitar `extension=php_pdo_sqlite.dll` y `extension=php_sqlite3.dll` en el `php.ini` del PHP CLI.
