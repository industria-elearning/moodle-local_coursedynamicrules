<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_coursedynamicrules
 * @category    string
 * @copyright   2024 Industria Elearning <info@industriaelearning.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Reglas dinámicas del curso';
$string['coursedynamicrules:notification'] = 'Enviar notificación';
$string['messageprovider:coursedynamicrules_notification'] = 'Notificación de reglas dinámicas del curso';
$string['coursedynamicrules:managerule'] = 'Gestionar reglas';
$string['coursedynamicrules:createrule'] = 'Crear reglas';
$string['coursedynamicrules:updaterule'] = 'Actualizar reglas';
$string['coursedynamicrules:viewrule'] = 'Ver reglas';
$string['coursedynamicrules:deleterule'] = 'Eliminar reglas';
$string['coursedynamicrules:manageaction'] = 'Gestionar acciones';
$string['coursedynamicrules:createaction'] = 'Crear acciones';
$string['coursedynamicrules:updateaction'] = 'Actualizar acciones';
$string['coursedynamicrules:viewaction'] = 'Ver acciones';
$string['coursedynamicrules:deleteaction'] = 'Eliminar acciones';
$string['coursedynamicrules:managecondition'] = 'Gestionar condiciones';
$string['coursedynamicrules:createcondition'] = 'Crear condiciones';
$string['coursedynamicrules:updatecondition'] = 'Actualizar condiciones';
$string['coursedynamicrules:viewcondition'] = 'Ver condiciones';
$string['coursedynamicrules:deletecondition'] = 'Eliminar condiciones';
$string['typemissing'] = 'Falta el valor "type"';
$string['name'] = 'Nombre';
$string['description'] = 'Descripción';
$string['conditions'] = 'Condiciones';
$string['conditions_help'] = 'Las condiciones se utilizan para definir las condiciones que deben cumplirse para ejecutar las acciones de la regla';
$string['actions'] = 'Acciones';
$string['actions_help'] = 'Las acciones se utilizan para definir las acciones que se ejecutarán cuando se cumplan las condiciones de la regla';
$string['ruleactive'] = 'Activa';
$string['ruleactive_help'] = 'Habilitar o deshabilitar la regla';
$string['ruleinactive'] = 'Inactiva';
$string['ruleadd'] = 'Agregar regla';
$string['ruleaddedsuccessfully'] = 'Regla añadida con éxito';
$string['editrule'] = 'Editar regla';
$string['deleterule'] = 'Eliminar regla';
$string['addconditions'] = 'Agregar condiciones';
$string['editconditions'] = 'Editar condiciones';
$string['passgrade'] = 'Finalización de actividad con calificación aprobatoria';
$string['allcourseactivitymodules'] = 'Todos los módulos de actividad del curso';
$string['searchcourseactivitymodules'] = 'Buscar módulos de actividad del curso';
$string['passgrade_description'] = 'Usuarios que han completado el módulo de actividad del curso \'{$a}\' con una calificación aprobatoria';
$string['no_complete_activity_description'] = 'Usuarios que no han completado el módulo de actividad del curso \'{$a->moddescription}\' después de {$a->expectedcompletiondate}';
$string['invalidruleid'] = 'ID de regla no válido';
$string['deletecondition'] = 'Eliminar condición';
$string['messagesubject'] = 'Asunto';
$string['messagebody'] = 'Cuerpo';
$string['messagebody_help'] = 'Los siguientes marcadores de posición pueden incluirse en el mensaje:

* Nombre del curso {$a->coursename}
* Nombre completo del usuario {$a->fullname}
* Nombre del usuario {$a->firstname}
* Apellido del usuario {$a->lastname}
* Nombre del módulo de actividad del curso {$a->modulename}
* Nombre de instancia del módulo de actividad del curso {$a->moduleinstancename}';
$string['sendnotification'] = 'Enviar notificación';
$string['sendnotification_description'] = 'Enviar notificación \'{$a}\' a los usuarios';
$string['createaiactivity'] = 'Crear actividad de refuerzo con IA';
$string['createaiactivity_description'] = 'Generar una actividad de refuerzo con IA en la sección "{$a->section}" usando el prompt "{$a->prompt}"';
$string['addactions'] = 'Agregar acciones';
$string['editactions'] = 'Editar acciones';
$string['backtolistrules'] = 'Volver a la lista de reglas';
$string['availableplaceholders'] = 'Marcadores de posición disponibles';
$string['coursename'] = 'Nombre del curso';
$string['courselink'] = 'Enlace del curso';
$string['fullname'] = 'Nombre completo del usuario';
$string['firstname'] = 'Nombre del usuario';
$string['lastname'] = 'Apellido del usuario';
$string['modulename'] = 'Nombre del módulo de actividad del curso';
$string['moduleinstancename'] = 'Nombre de instancia del módulo de actividad del curso';
$string['deletingrule'] = 'Eliminando regla \'{$a}\'';
$string['deletingcondition'] = 'Eliminando condición \'{$a}\'';
$string['deleterulecheck'] = '¿Está completamente seguro de que desea eliminar esta regla?';
$string['deleteconditioncheck'] = '¿Está completamente seguro de que desea eliminar esta condición?';
$string['deleteactioncheck'] = '¿Está completamente seguro de que desea eliminar esta acción?';
$string['deletedrule'] = 'Regla eliminada <b>{$a}</b>';
$string['deletedcondition'] = 'Condición eliminada <b>{$a}</b>';
$string['deletedaction'] = 'Acción eliminada <b>{$a}</b>';
$string['ruleupdatedsuccessfully'] = 'Regla actualizada con éxito';
$string['createrule'] = 'Crear regla';
$string['completiondate'] = 'Fecha de finalización';
$string['before'] = 'Antes de';
$string['after'] = 'Después de';
$string['no_complete_activity'] = 'Actividad no completada';
$string['no_complete_activity_task'] = 'Tarea de actividad no completada';
$string['expectedcompletiondate'] = 'Fecha esperada de finalización';
$string['grade_in_activity'] = 'Calificación en actividad';
$string['grade_in_activity_description'] = 'Para "{$a->moddescription}", se deben obtener las siguientes calificaciones: {$a->gradestring}';
$string['grade'] = 'Calificación';
$string['enablegradegreaterthanorequal_help'] = 'Habilitar calificación mayor o igual que';
$string['enablegradelessthan'] = 'Habilitar calificación menor que';
$string['errornegativegrade'] = 'La calificación debe ser 0 o mayor.';
$string['errormaxgradeexceeded'] = 'La calificación no puede exceder la calificación máxima para la actividad.';
$string['enableactivity'] = 'Habilitar actividad';
$string['enableactivity_description'] = 'Habilitar actividades \'{$a}\'';
$string['createaiactivity_action_info'] = 'Esta acción solicitará al servicio de IA generar una actividad de refuerzo personalizada para los usuarios que cumplan con las condiciones de la regla.';
$string['errorgradeoutofrange'] = 'El valor debe estar entre {$a->min} y {$a->max}.';
$string['notification_action_info'] = 'Esta acción enviará una notificación a los usuarios que cumplan con los criterios de las condiciones de la regla.';

$string['missing_plugins_warning'] = '🔔 ¡Mejora tus notificaciones! Nuestros plugins de <strong>Datacurso Message Hub</strong> te permiten enviar notificaciones por WhatsApp y SMS usando proveedores como Twilio.
<br>
<a href="https://shop.datacurso.com/clientarea.php" target="_blank">Haz clic aquí para comprarlos y activarlos ahora!</a>';

$string['provider_not_enabled_warning'] = 'Habilita las notificaciones con <strong>Datacurso Message Hub</strong> para que esta acción envíe notificaciones por WhatsApp y SMS usando proveedores como Twilio.
Puedes habilitarlo desde la <a href="{$a}" target="_blank">Configuración de notificaciones</a> buscando <strong>Notificación de reglas dinámicas del curso</strong>.
<br>
<a href="https://docs.datacurso.com/index.php?title=Message_Hub" target="_blank">Consulta la documentación para más información.</a>';

$string['rules'] = 'Reglas';
$string['rules_help'] = 'Las reglas se utilizan para definir un conjunto de condiciones y acciones que se ejecutarán';
$string['missing_availability_user'] = 'Esta acción requiere que el plugin <strong>Restriction by user</strong> esté instalado y habilitado. Por favor descárguelo desde <a href="https://moodle.org/plugins/availability_user/versions" target="_blank">https://moodle.org/plugins/availability_user/versions</a> e instálelo.';
$string['disabled_availability_user'] = 'Esta acción requiere que el plugin <strong>Restriction by user</strong> esté habilitado. Por favor acceda a la página <a href="{$a}" target="_blank">Gestionar restricciones</a>, busque <strong>Restricción por usuario</strong> y habilítelo.';
$string['enableactivity_action_info'] = 'Esta acción habilitará los módulos de actividades seleccionados para los usuarios que cumplan con los criterios de las condiciones de la regla.';
$string['createaiactivity_prompt'] = 'Prompt para IA';
$string['createaiactivity_prompt_help'] = 'Escribe la instrucción que se enviará al servicio de IA. Puedes incluir marcadores que se reemplazarán antes de enviar el prompt.';
$string['createaiactivity_generateimages'] = 'Generar imágenes con IA';
$string['createaiactivity_generateimages_label'] = 'Permitir que la IA incluya imágenes generadas cuando sea posible.';
$string['createaiactivity_section'] = 'Sección del curso';
$string['createaiactivity_beforemod'] = 'Ubicar antes de la actividad';
$string['createaiactivity_beforemod_help'] = 'Selecciona la actividad que el nuevo recurso debe preceder, o deja la opción predeterminada para agregarlo al final de la sección.';
$string['createaiactivity_beforemod_none'] = 'No ubicar antes de otra actividad';
$string['createaiactivity_placeholders_info'] = 'Marcadores disponibles: <code>{$a->coursename}</code>, <code>{$a->courseurl}</code>, <code>{$a->fullname}</code>, <code>{$a->firstname}</code>, <code>{$a->lastname}</code>.';
$string['grade_in_activity_condition_info'] = 'Esta condición verificará cuál usuario ha obtenido la calificación especificada en el módulo de actividad seleccionado.';
$string['no_complete_activity_condition_info'] = 'Esta condición verificará cuál usuario no ha completado el módulo de actividad seleccionado después de la fecha especificada.';
$string['passgrade_condition_info'] = 'Esta condición verificará cuál usuario ha completado el módulo de actividad seleccionado con una calificación aprobatoria.';
$string['licensekey'] = 'Clave de licencia';
$string['generalsettings'] = 'Configuración general';
$string['checklicensekey'] = 'Verificar clave de licencia';
$string['licensekeyvalid'] = 'La clave de licencia es válida';
$string['licensekeyinvalid'] = 'La clave de licencia ha caducado o no es válida. Por favor, vaya a <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> para renovar o comprar una nueva licencia.';
$string['gradegreaterthanorequal'] = 'debe ser &#x2265;';
$string['gradegreaterthanorequal_help'] = 'La condición se cumple si la calificación del usuario es mayor o igual al valor especificado.';
$string['gradelessthan'] = 'debe ser <';
$string['gradelessthan_help'] = 'La condición se cumple si la calificación del usuario es menor al valor especificado.';
$string['no_course_access_condition_info'] = 'Esta condición verificará qué usuarios no han accedido a este curso dentro del período de tiempo especificado.';
$string['no_course_access'] = 'Sin acceso al curso';
$string['no_course_access_description'] = 'Usuarios que tarden más de {$a->periodvalue} {$a->periodunit} sin acceder a este curso.';
$string['period'] = 'Período';
$string['period_help'] = 'El tiempo mínimo que un usuario debe pasar sin acceder al curso.';
$string['no_course_access_task'] = 'Tarea de sin acceso al curso';
$string['minutes'] = 'Minutos';
$string['hours'] = 'Horas';
$string['days'] = 'Días';
$string['weeks'] = 'Semanas';
$string['months'] = 'Meses';
$string['course_inactivity_task'] = 'Tarea de inactividad en el curso';
$string['intervaltype'] = 'Tipo de intervalo';
$string['intervaltype_help'] = 'Selecciona cómo se evaluará el intervalo:

* **Intervalos personalizados**: Para agregar valores separados por comas (ej. 7,14,30) y evaluar la inactividad en puntos de tiempo específicos.
* **Intervalo recurrente**: Para evaluar la inactividad en intervalos recurrentes (ej. cada 7 días).';
$string['customintervals'] = 'Intervalos personalizados';
$string['customintervals_help'] = 'Ingresa números separados por comas que representen períodos de inactividad (ej. "7,14,30").';
$string['recurringinterval'] = 'Intervalo recurrente';
$string['recurringinterval_help'] = 'Ingresa un valor numérico que represente un intervalo recurrente de inactividad (ej. "7" para cada 7 días de inactividad).';
$string['intervalunit'] = 'Unidad de tiempo';
$string['intervalunit_help'] = 'Selecciona la unidad de tiempo para los intervalos.';
$string['date_from_enrollment'] = 'Desde la fecha de matriculación';
$string['date_from_course_start'] = 'Desde la fecha de inicio del curso';
$string['date_from_now'] = 'Desde ahora';
$string['course_inactivity_custom_description'] = 'Usuarios sin actividad en el curso durante intervalos de {$a->intervals} {$a->unit} desde {$a->basedate}';
$string['course_inactivity_recurring_description'] = 'Usuarios sin actividad en el curso en intervalos recurrentes de {$a->intervals} {$a->unit} desde {$a->basedate}';
$string['enrollmentdate'] = 'Fecha de matriculación';
$string['coursestartdate'] = 'Fecha de inicio del curso';
$string['now'] = 'Ahora';
$string['course_inactivity_info'] = 'Esta condición verificará qué usuarios no han tenido actividad en el curso dentro de los intervalos de tiempo especificados.';
$string['course_inactivity'] = 'Inactividad en el curso en intervalos de tiempo';
$string['basedate'] = 'Fecha base';
$string['complete_activity_description'] = 'Usuarios que han completado el módulo de actividad del curso \'{$a->moddescription}\'';
$string['complete_activity_condition_info'] = 'Esta condición verificará qué usuario ha completado el módulo de actividad seleccionado.';
$string['complete_activity'] = 'Actividad completada';
$string['copytoclipboard'] = 'Copiar al portapapeles';
$string['copiedtoclipboard'] = 'Copiado al portapapeles';
$string['rolestonotify'] = 'Roles a notificar';
$string['rolestonotify_help'] = 'Seleccione los roles que el usuario debe tener para recibir la notificación. Debe seleccionar al menos uno.';
$string['mustselectonerole'] = 'Debe seleccionar al menos un rol.';

// License.
$string['pluginnotavailable'] = 'Este plugin no está disponible porque la licencia del producto ha expirado o es inválida. Por favor, visita <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> para renovar o comprar una nueva licencia.';
$string['licensekey_desc'] = 'Clave de licencia requerida para utilizar este plugin.';
