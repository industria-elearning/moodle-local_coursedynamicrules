# Reglas dinámicas del curso

## Sobre reglas dinámicas del curso

Este plugin permite crear reglas dinamicas para los cursos de Moodle.

## Instalación mediante archivo ZIP subido

1. Inicie sesión en su sitio Moodle como administrador y vaya a `Administración del sitio > Extensiones > Instalar complementos`.
2. Suba el archivo ZIP con el código del plugin. Solo se le pedirá que agregue
    detalles adicionales si el tipo de plugin no se detecta automáticamente.
3. Verifique el informe de validación del plugin y finalice la instalación.

## Instalación manual

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


## Agregar reglas dinámicas a un curso

1. Accedemos al curso en el que queremos agregar reglas dinámicas y damos click en `Reglas dinámicas del curso` en el menú de administración del curso.
   
    ![Course administration](__docs/images/course-administration.png)

2. Damos click en `Agregar regla`.
   
    ![Add rule](__docs/images/add-rule.png)

3. Ingresamos el nombre de la regla, la descripción marcamos la casilla `Activa`
   
    ![Rule configuration](__docs/images/rule-configuration.png)

    
## Agregar condiciones a la regla

1. Damos click en el enlace `Agregar condiciones`.
   
    ![Add conditions](__docs/images/add-conditions.png)

2. Nos aparecerá una vista como la siguiente con la lista de condiciones disponibles desde donde podemos seleccionar la que deseamos agregar, la informaciona a cerca de cada condición la podemos encontrar en la siguiente sección [Condiciones disponibles](#condiciones-disponibles).
   
    ![Conditions list](__docs/images/conditions-list.png)

## Condiciones disponibles

### Calificación en actividad

#### Descripción  
Esta condición se utiliza para evaluar las calificaciones de los usuarios en una actividad que requiere calificación. Si la actividad seleccionada tiene múltiples ítems de calificación, se mostrará una lista desplegable con los ítems disponibles.  

Por ejemplo, en el caso del módulo `Foro`, los ítems de calificación disponibles podrían incluir:  
- Evaluación del foro completo  
- Calificaciones  

![Forum whole forum item](__docs/images/forum-whole-forum-item.png)  
![Forum grades item](__docs/images/forum-grades-item.png)  

#### Configuración de la condición

1. **Buscar módulos de actividad del curso**:
   Desde este campo de busqueda podemos podemos seleccionar el módulo de actividad en el que deseamos verificar las calificaciones. 

   ![Grade in activity search](__docs/images/grade-in-activity-search.png)
   
   **Nota**: Solo aparecerán los modulos de actividad que tengan minimo la siguiente configuración en la sección de `Finalización de actividad`:

    ![Activity completion with require grade](__docs/images/activity-completion-with-require-grade.png)

2. **Opciones de comparación**:  
   Una vez seleccionada la actividad, podemos definir las condiciones de comparación según los ítems de calificación disponibles. Se ofrecen dos opciones:  
   - `Debe ser ≥`: La condición se cumple si la calificación del usuario es mayor o igual al valor especificado.  
   - `Debe ser <`: La condición se cumple si la calificación del usuario es menor al valor especificado.  
  
    ![Comparison options](__docs/images/comparison-options.png)


### Actividad no completada

#### Descripción

Esta condición se utiliza para evaluar si un usuario no ha completado una actividad específica despues de un período de tiempo determinado.

#### Configuración de la condición

1. **Buscar módulos de actividad del curso**:
   Desde este campo de busqueda podemos podemos seleccionar el módulo de actividad en el que deseamos verificar las calificaciones. 

   ![No complete activity search](__docs/images/no-complete-activity-search.png)
   
   **Nota**: Solo aparecerán los modulos de actividad que tengan minimo la siguiente configuración en la sección de `Finalización de actividad`:

    ![Activity completion automatic](__docs/images/activity-completion-automatic.png)

2. **Fecha esperada de finalización**:
    Definimos la fecha en la que esperamos que los usuarios hayan completado la actividad.

    ![Expected completion date](__docs/images/expected-completion-date.png)


### Finalización de actividad con calificación aprobatoria

#### Descripción

Esta condición se utiliza para evaluar si los usuarios han completado una actividad con una calificación aprobatoria.

#### Configuración de la condición

1. **Buscar módulos de actividad del curso**:
   Desde este campo de busqueda podemos podemos seleccionar el módulo de actividad en el que deseamos verificar las calificaciones. 

    ![Pass grade search](__docs/images/pass-grade-search.png)
   
   **Nota**: Solo aparecerán los modulos de actividad que tengan minimo la siguiente configuración en la sección de `Finalización de actividad`:

    ![Pass grade search](__docs/images/activity-completion-with-pass-grade.png)


## Agregar acciones a la regla

1. Damos click en el enlace `Agregar acciones`.
   
    ![Add actions](__docs/images/add-actions.png)

2. Nos aparecerá una vista como la siguiente con la lista de acciones disponibles desde donde podemos seleccionar la que deseamos agregar, la informaciona a cerca de cada acción la podemos encontrar en la siguiente sección [Acciones disponibles](#acciones-disponibles).
   
    ![Actions list](__docs/images/action-lists.png)

## Acciones disponibles

### Habilitar actividad

#### Descripción

Esta acción se utiliza para habilitar actividades específicas en el curso para usuarios que cumplan con las condiciones de la regla.

**Nota**: Para poder usar esta acción es necesario instalar el plugin `Restriction by user` el cual se puede descargar de forma gratuita desde el siguiente enlace [https://moodle.org/plugins/availability_user/versions](https://moodle.org/plugins/availability_user/versions).

#### Configuración de la acción

1. **Buscar módulos de actividad del curso**:
   Desde este campo de busqueda podemos podemos seleccionar los modulos de actividad que deseamos habilitar.

    ![Enable activity search](__docs/images/enable-activity-search.png)

    Las actividades seleccionadas solo quedarán disponibles para los usuarios que cumplan con las condiciones de la regla, desde la vista del curso para roles con permisos necesarios (profesores, administradores, etc.) se mostrará la siguiente información que indica los usuarios para los cuales la actividad está habilitada:

    ![Restriction by user](__docs/images/restriction-by-user.png)














