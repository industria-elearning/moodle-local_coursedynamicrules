# Reglas dinámicas del curso

## Sobre reglas dinámicas del curso

Este plugin permite crear reglas dinamicas para los cursos de Moodle.

## Instalación mediante archivo ZIP subido ##

1. Inicie sesión en su sitio Moodle como administrador y vaya a `Administración del sitio > Extensiones > Instalar complementos`.
2. Suba el archivo ZIP con el código del plugin. Solo se le pedirá que agregue
    detalles adicionales si el tipo de plugin no se detecta automáticamente.
3. Verifique el informe de validación del plugin y finalice la instalación.

## Instalación manual ##

El plugin también se puede instalar colocando el contenido de este directorio en

`{su/moodle/dirroot}/local/coursedynamicrules`

Después, inicie sesión en su sitio Moodle como administrador y vaya a `Administración del sitio > General > Notificaciones` para completar la instalación.

Alternativamente, puede ejecutar
```bash
php admin/cli/upgrade.php
```

para completar la instalación desde la línea de comandos.

## Activación del plugin

Una vez que el plugin esté instalado, será necesario activarlo utilizando la llave de licencia proporcionada en la tienda del plugin, para ellos hacemos lo siguiente:

1. Accedemos a [https://shop.datacurso.com/clientarea.php](https://shop.datacurso.com/clientarea.php) y damos click sobre el servicio que queremos activar en este caso `Moodle - Reglas dinámicas del Curso para Moodle LMS`.
   
   ![Service to active](__docs/images/service-to-active.png)

2. Copiamos la clave de licencia.

    ![Shop licence key](__docs/images/shop-license-key.png)

3. En nuestra plataforma de Moodle accedemos a `Administración del sitio > Extensiones > Extensiones locales > Reglas dinámicas del curso > Configuración general` 
   
    ![Local plugins](__docs/images/general-settings.png)

4. Pegamos la llave de licencia en el campo `Clave de licencia` y damos click en `Guardar cambios`.

    ![Plugin activation](__docs/images/plugin-activation.png)


4. Para validar si la licencia fue activada correctamente, accedemos a `Administración del sitio > Extensiones > Extensiones locales > Reglas dinámicas del curso > Verificar clave de licencia`
   
    ![Verify licence](__docs/images/verify-licence.png)

5. Si la licencia fue activada correctamente, veremos un mensaje de confirmación.

    ![Licence activated](__docs/images/licence-activated.png)





