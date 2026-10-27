# Conectar MySQL y publicar el proyecto en Hostinger

Esta guía corresponde a hosting web o cloud con hPanel. Un VPS necesita configurar su propio servidor web, PHP y servicios. Los datos `DB_*` permiten entrar a MySQL; no sustituyen el acceso a la cuenta de Hostinger que administra el hosting.

## 1. Entrar al hosting y elegir el sitio

1. Entra a la cuenta de Hostinger que tiene el hosting del proyecto.
2. Abre **Sitios web** y el **Panel / Dashboard** del dominio.
3. Si el sitio aún no existe, crea un sitio de tipo **PHP/HTML personalizado** y asigna su dominio o dominio temporal.
4. En **Configuración PHP**, selecciona PHP 8.3 o superior; el proyecto se ha usado con PHP 8.4. Comprueba también la versión del PHP de consola mediante `php -v`.
5. Comprueba las extensiones de Laravel, especialmente `pdo_mysql`, `mbstring`, `fileinfo`, `curl`, `openssl`, `dom` y `xml`.

Referencias oficiales: [crear un sitio](https://www.hostinger.com/support/1583214-how-to-add-a-website-in-hostinger/), [configurar PHP](https://www.hostinger.com/support/1575755-how-to-change-the-php-version-of-your-hostinger-hosting-plan/).

## 2. Usar o crear la base de datos

1. En el panel del sitio, entra a **Bases de datos → Administración / Management**.
2. Busca la base y el usuario que te entregó el administrador. Si ya aparecen, usa esa base.
3. Si necesitas una base nueva para pruebas, completa **Crear una nueva base MySQL y usuario** con un nombre, usuario y contraseña propios.
4. Copia los nombres completos que muestre Hostinger, incluido su prefijo `u..._`. En otra cuenta, el prefijo será distinto al de la foto.
5. La contraseña es la que se asignó a ese usuario al crear la base. Si se desconoce, el administrador debe revisarla o cambiarla y actualizar todas las aplicaciones que usen ese usuario.

Crear una base no crea las tablas de Laravel ni copia los registros de SQLite.

Referencias oficiales: [crear MySQL](https://www.hostinger.com/support/1583542-how-to-create-a-new-mysql-database-in-hostinger/), [consultar datos de conexión](https://www.hostinger.com/support/1583552-how-to-find-your-mysql-database-details-in-hostinger/).

## 3. Conectarse desde tu computadora

Estos pasos corresponden a tu `.env` local, mientras abres la página en `127.0.0.1`.

1. En hPanel, busca **MySQL remoto / Remote MySQL**.
2. Añade la IP pública actual desde la que te conectas, indicada en el error o diagnóstico de MySQL. Puede cambiar si cambias de conexión.
3. Selecciona la base de datos del proyecto y pulsa **Crear**.
4. Copia el hostname o IP para acceso remoto que indique esa pantalla.
5. Configura tu `.env` local con esos datos:

```dotenv
DB_CONNECTION=mysql
DB_HOST=HOST_REMOTO_MOSTRADO_EN_HPANEL
DB_PORT=3306
DB_DATABASE=NOMBRE_COMPLETO_DE_LA_BASE
DB_USERNAME=USUARIO_COMPLETO
DB_PASSWORD="CONTRASEÑA_DEL_USUARIO_MYSQL"
```

6. Desde la carpeta del proyecto, comprueba la conexión sin modificar datos:

```powershell
& "D:\php84\php.exe" artisan config:clear
& "D:\php84\php.exe" artisan db:show --database=mysql
```

Si PHP está en otra ruta, reemplaza `D:\php84\php.exe`. El servidor local necesita reiniciarse si acabas de habilitar una extensión de PHP.

El error `1045 / Access denied` significa que el servidor rechazó la autenticación. El administrador debe comprobar las credenciales y el acceso permitido desde el origen indicado. Ejecutar migraciones no resuelve ese rechazo.

Referencia oficial: [habilitar MySQL remoto](https://www.hostinger.com/support/1583546-how-to-set-up-remote-mysql-access-in-hostinger/).

## 4. Subir el proyecto para publicarlo

Estos pasos son para la copia que se ejecutará dentro de Hostinger. Conserva un respaldo si el sitio ya está en uso.

1. Habilita **Acceso SSH** en el panel del sitio. Copia su usuario, host y puerto; son los datos SSH, no los de MySQL.
2. Conéctate usando el comando que muestra Hostinger.
3. Entra a la carpeta del dominio, por ejemplo `/home/TU_USUARIO/domains/TU_DOMINIO`.
4. Descarga la rama del proyecto en una carpeta nueva fuera de `public_html`:

```bash
git clone --branch Luis-Alberto --single-branch https://github.com/MrGys1986/plataforma-formacion.git plataforma
cd plataforma
composer install --no-dev --optimize-autoloader
composer check-platform-reqs --no-dev
```

Usa Composer 2 y el PHP compatible del hosting. No omitas los requisitos de plataforma si Composer indica un problema.

5. En tu computadora compila los archivos visuales:

```powershell
npm.cmd ci
npm.cmd run build
```

6. Sube la carpeta generada `public/build` al `public/build` de la copia del servidor. Esta carpeta no se incluye en GitHub.

Referencia oficial: [habilitar SSH](https://www.hostinger.com/support/1583645-how-to-enable-ssh-access-in-hostinger/).

## 5. Configurar el .env del servidor

Crea el `.env` del servidor a partir de `.env.example` si todavía no existe. Configura al menos:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://TU_DOMINIO

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=NOMBRE_COMPLETO_DE_LA_BASE
DB_USERNAME=USUARIO_COMPLETO
DB_PASSWORD="CONTRASEÑA_DEL_USUARIO_MYSQL"

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
CACHE_STORE=database

QUEUE_CONNECTION=sync
```

`localhost` corresponde al hosting y su base MySQL dentro del mismo servicio, según Hostinger. Si la aplicación y la base están en servidores distintos, utiliza el hostname de la base y los permisos remotos correspondientes. No cambies el host de tu computadora a `localhost` para conectarte al MySQL de Hostinger.

`QUEUE_CONNECTION=sync` permite comenzar sin un worker. Si después usas colas en segundo plano, configura `database` junto con su worker o tarea adecuada.

Configura también Cloudinary en ese `.env` con las credenciales de la cuenta que utilizará el sitio. Mantén este archivo privado.

Solo para una instalación nueva que todavía no tenga `APP_KEY`, ejecuta:

```bash
php artisan key:generate
```

Conserva la llave de una instalación existente.

Referencia oficial del host local: [datos MySQL de Hostinger](https://www.hostinger.com/support/1583552-how-to-find-your-mysql-database-details-in-hostinger/).

## 6. Preparar las tablas y el administrador

En la carpeta `plataforma` del servidor:

```bash
php artisan config:clear
php artisan db:show --database=mysql
```

Continúa solo cuando la conexión funcione. En una base nueva destinada a esta instalación, crea las tablas:

```bash
php artisan migrate --force
```

En una base existente, respáldala y revisa primero sus migraciones con el administrador. No uses `migrate:fresh`: elimina tablas y datos.

Para una instalación nueva, crea los catálogos iniciales sin las cuentas de demostración:

```bash
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=AreaSeeder --force
php artisan db:seed --class=ActivityTypeSeeder --force
php artisan db:seed --class=SurveySeeder --force
php artisan make:filament-user
```

El último comando solicita nombre, correo y contraseña para el administrador. Después asígnale el rol institucional:

```bash
php artisan tinker
```

Dentro de Tinker, sustituye el correo por el que acabas de registrar:

```php
$admin = App\Models\User::where('email', 'TU_CORREO_ADMIN')->firstOrFail();
$admin->update(['status' => 'activo']);
$admin->syncRoles(['Superadministrador']);
exit;
```

Las migraciones y estos catálogos no transfieren los usuarios, cursos, calificaciones ni archivos de tu SQLite. Para conservar esos registros se necesita una migración de datos aparte. No subas el archivo SQLite como si fuera un respaldo SQL de MySQL.

## 7. Publicar solamente la carpeta pública

Laravel debe servir `plataforma/public/index.php`. El servidor necesita poder escribir en `storage` y `bootstrap/cache`.

Si puedes definir el directorio público del dominio, apúntalo a `plataforma/public`. Confirma con el soporte de Hostinger si tu servicio permite ajustar esa ruta.

Si tu hosting utiliza obligatoriamente `public_html`, utiliza esta distribución:

```text
carpeta-del-dominio/
├── plataforma/      # Proyecto, .env, vendor, storage y artisan
└── public_html/     # Solo el contenido de plataforma/public
```

Copia el contenido de `plataforma/public` a `public_html`, incluyendo `.htaccess`, `build`, imágenes y los archivos públicos de Filament. No copies el archivo local `public/hot` ni uses una carpeta pública que contenga otro sitio sin revisarlo antes.

En la copia `public_html/index.php`, conserva el código del archivo original y cambia estas rutas:

```php
__DIR__.'/../storage/framework/maintenance.php'
// pasa a:
__DIR__.'/../plataforma/storage/framework/maintenance.php'

__DIR__.'/../vendor/autoload.php'
// pasa a:
__DIR__.'/../plataforma/vendor/autoload.php'

__DIR__.'/../bootstrap/app.php'
// pasa a:
__DIR__.'/../plataforma/bootstrap/app.php'
```

Después de obtener `$app` y antes de `$app->handleRequest(...)`, añade en esa copia del servidor:

```php
$app->usePublicPath(__DIR__);
```

Mantén `.env`, `storage/app/private` y `vendor` dentro de `plataforma`, fuera de `public_html`. En siguientes despliegues vuelve a copiar los archivos públicos y conserva esta adaptación del punto de entrada.

Referencia oficial: [despliegue de Laravel](https://laravel.com/docs/13.x/deployment).

## 8. Terminar y comprobar

1. Activa HTTPS para el dominio en Hostinger y verifica que `APP_URL` coincida.
2. En la carpeta del proyecto ejecuta `php artisan optimize`.
3. Abre `https://TU_DOMINIO/login` y entra con el administrador creado.
4. Comprueba imágenes, registro de un curso, solicitud de inscripción, calificación y descarga de una constancia de prueba.
5. Para las tareas programadas del proyecto, configura un cron cada minuto que ejecute `artisan schedule:run`, con las rutas reales del PHP y del proyecto. Revisa las tareas de limpieza antes de habilitarlas sobre archivos existentes.
6. Establece respaldos de la base de datos y los archivos privados del proyecto.

El `.env` no debe descargarse desde una dirección web ni guardarse en GitHub.
