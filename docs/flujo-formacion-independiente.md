# Prueba de formación independiente

## Iniciar el servidor local en Windows

Desde la carpeta del proyecto, ejecuta `.\iniciar-local.cmd` y abre `http://127.0.0.1:8000/login`.
Si ya tienes un servidor usando el puerto 8000, detén primero esa terminal con `Ctrl+C`.
El comando comprueba que el puerto 8000 esté libre, prepara las vistas Blade antes de iniciar PHP y utiliza un proceso de desarrollo sin OPcache, con 256 MB de memoria y un límite de 60 segundos por petición. Los ajustes solo afectan este proceso local. No ejecutes este comando y `artisan serve` al mismo tiempo: en Windows se observaron dos procesos escuchando en el mismo puerto y atendiendo peticiones con configuraciones distintas.
Usa `D:\php84\php.exe` por defecto; si tu PHP está en otra carpeta, define la variable `FORMACION_PHP` con su ruta.
Las subidas y descargas de Cloudinary requieren que el proceso PHP tenga acceso a Internet. Inicia el servidor desde una terminal normal de Windows; si se inicia desde una herramienta con red restringida, el acceso a Cloudinary puede fallar aunque el navegador sí tenga conexión.

## Programas de prueba

Los ejemplos son independientes y no se agregan a rutas de aprendizaje:

- Curso de prueba: Herramientas digitales (8 horas).
- Minicurso de prueba: Organización del estudio (2 horas).
- Taller de prueba: Trabajo en equipo (4 horas).

Se registran con `D:\php84\php.exe artisan db:seed --class=StandaloneTrainingDemoSeeder`.
Repetir este seeder conserva los programas, sus avances y las contraseñas existentes.
No crea inscripciones, calificaciones ni constancias: esos pasos se realizan desde la aplicación.

## Cuentas nuevas de prueba

| Rol | Correo | Contraseña inicial |
| --- | --- | --- |
| Alumno | alumno.flujo@formacion.test | Formacion2026! |
| Profesor | profesor.flujo@formacion.test | Formacion2026! |

Estas credenciales corresponden únicamente a cuentas de demostración.
Usa ventanas separadas del navegador para probar los dos roles al mismo tiempo.

## Recorrido del alumno y del profesor

1. **Alumno:** entra a **Catálogo de cursos**, abre uno de los ejemplos y solicita la inscripción. Aparece como solicitada en **Mi formación**.
2. **Profesor:** entra a **Cursos que imparto**, abre el mismo programa y, en **Solicitudes de inscripción**, pulsa **Aprobar inscripción**. También puede rechazarla indicando el motivo.
3. **Profesor:** en el participante aprobado captura una calificación de 0 a 100, selecciona **Completado** y pulsa **Guardar evaluación**.
4. **Profesor:** en ese participante sube su constancia en PDF de hasta 10 MB. Antes de completar la formación, la subida no está permitida.
5. **Alumno:** abre **Mi formación** para consultar el estado y la calificación. En el menú de su nombre entra a **Mi perfil**: allí aparece la formación completada y el enlace **Descargar constancia**. El documento también aparece en **Mis constancias**.
6. **Alumno:** abre la formación completada y pulsa **Evaluar curso**. Responde la encuesta y envíala. La página confirma que ya fue respondida; cada encuesta se contesta una vez por actividad.

El mismo recorrido funciona para curso, minicurso y taller. La encuesta evalúa la satisfacción con la formación; la calificación académica la registra el profesor o la administración.

## Recorrido desde administración

1. En **Gestión de formación → Programas de formación**, busca el programa y pulsa **Administrar**.
2. **Configurar inscripción** permite asignar un profesor y cambiar a **Publicado en catálogo** o **Inscripciones abiertas**. Los tres ejemplos ya están publicados y tienen profesor asignado.
3. En **Inscripciones**, pulsa **Aprobar** en una solicitud. Para capturar la calificación usa **Editar**, selecciona **Completado** y guarda. La inscripción debe estar aprobada y tener calificación.
4. En **Constancias → Subir constancia**, selecciona al participante que terminó, adjunta el PDF y guarda. La constancia queda vinculada a su inscripción y a su cuenta automáticamente.

Para sustituir una constancia, usa **Editar**, retira el archivo anterior del campo y selecciona el nuevo PDF. El alumno tendrá acceso al documento actualizado.

Las constancias se descargan con su nombre original y extensión `.pdf`, incluso si Cloudinary conserva un nombre temporal. El límite actual es de 10 MB por archivo, conforme al plan gratuito de Cloudinary. Si el PDF supera ese tamaño, reduce su peso antes de subirlo.

En el PHP local de Windows, `upload_max_filesize = 20M` y `post_max_size = 24M` permiten que Laravel reciba la petición y muestre el aviso de 10 MB sin una interrupción de PHP. Estos valores de transporte no aumentan el límite de documentos de la aplicación ni el de Cloudinary. Tras modificar `php.ini`, reinicia el servidor con `Ctrl+C` y `& "D:\php84\php.exe" artisan serve`.

No es necesario asignar una ruta. Las constancias se suben como documentos PDF; esta prueba no fabrica certificados académicos.

## Cupo opcional

En **Programas de formación → Administrar → Configurar inscripción**, el campo **Cupo máximo (opcional)** puede quedar vacío: así no hay límite de inscripciones. También puedes borrar un límite existente y guardar para dejar la actividad sin límite.

Si capturas un número, debe ser un entero mayor que cero. Las solicitudes pendientes y las inscripciones aprobadas ocupan un lugar; rechazar o cancelar una inscripción libera ese lugar. Aprobar una solicitud existente conserva el lugar reservado. El control se aplica tanto al alumno como a las altas y cambios desde administración y las asignaciones de rutas.

Si reduces el límite por debajo de las inscripciones existentes, se conservan los alumnos actuales y se bloquean las nuevas altas hasta tener lugares disponibles. No se modifica el cupo de cursos existentes automáticamente. Cada alumno conserva una sola inscripción por actividad, incluso si pulsa varias veces el botón.

## Avisos dentro de la plataforma

La campanita del encabezado muestra los avisos de la cuenta actual. Funciona con los correos de prueba y no envía mensajes por correo electrónico ni requiere un worker de colas.

- Al solicitar una inscripción, recibe el aviso el profesor asignado. Si no hay profesor activo, se avisa a los superadministradores activos. En actividades con pago, el aviso se genera después de validar el pago.
- El alumno recibe avisos cuando se aprueba o rechaza su inscripción, cambia su calificación o se emite o reemplaza su constancia.
- Pulsa un aviso para marcarlo como leído y abrir la formación o las constancias. También puedes marcar uno con la palomita o usar **Marcar todas como leídas**.
- La campanita actualiza los avisos cada 30 segundos. Puedes recargar la página para verlos inmediatamente y consultar los anteriores con la paginación del menú.
- Los avisos se generan por acciones nuevas; no se fabrican avisos retroactivos para los cursos que ya estaban completados.
