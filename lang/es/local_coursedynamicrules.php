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
 * @copyright   2025 Wilber Narvaez <https://datacurso.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['actions'] = 'Acciones';
$string['actions_help'] = 'Las acciones se utilizan para definir las tareas que se ejecutarán cuando se cumplan las condiciones de la regla';
$string['addactions'] = 'Añadir acciones';
$string['addconditions'] = 'Añadir condiciones';
$string['after'] = 'Después';
$string['allcourseactivitymodules'] = 'Todos los módulos de actividad del curso';
$string['availableplaceholders'] = 'Marcadores disponibles';
$string['backtolistrules'] = 'Volver a la lista de reglas';
$string['basedate'] = 'Fecha base';
$string['basedate_help'] = 'Elija la fecha de referencia para evaluar la inactividad:

* **Desde la fecha de matrícula**: Calcula desde cuando el usuario se matriculó.
* **Desde la fecha de inicio del curso**: Calcula desde la fecha de inicio del curso.
* **Desde ahora**: Calcula desde la fecha actual.';
$string['before'] = 'Antes';
$string['checklicensekey'] = 'Comprobar clave de licencia';
$string['complete_activity'] = 'Actividad completada';
$string['complete_activity_condition_info'] = 'Esta condición comprobará qué usuario ha completado el módulo de actividad seleccionado.';
$string['complete_activity_description'] = 'Usuarios que han completado el módulo de actividad del curso "{$a->moddescription}"';
$string['completiondate'] = 'Fecha de finalización';
$string['conditions'] = 'Condiciones';
$string['conditions_help'] = 'Las condiciones se utilizan para definir los criterios que deben cumplirse para ejecutar las acciones de la regla';
$string['copiedtoclipboard'] = 'Copiado al portapapeles';
$string['copytoclipboard'] = 'Copiar al portapapeles';
$string['course_inactivity'] = 'Inactividad en el curso por intervalos de tiempo';
$string['course_inactivity_custom_description'] = 'Usuarios sin actividad en el curso durante intervalos de {$a->intervals} {$a->unit} desde {$a->basedate}';
$string['course_inactivity_info'] = 'Esta condición comprobará qué usuarios no han tenido actividad en el curso dentro de los intervalos de tiempo especificados.';
$string['course_inactivity_recurring_description'] = 'Usuarios sin actividad en el curso en intervalos recurrentes de {$a->intervals} {$a->unit} desde {$a->basedate}';
$string['course_inactivity_task'] = 'Tarea de inactividad en el curso';
$string['coursedynamicrules:createaction'] = 'Crear acciones';
$string['coursedynamicrules:createcondition'] = 'Crear condiciones';
$string['coursedynamicrules:createrule'] = 'Crear reglas';
$string['coursedynamicrules:deleteaction'] = 'Eliminar acciones';
$string['coursedynamicrules:deletecondition'] = 'Eliminar condiciones';
$string['coursedynamicrules:deleterule'] = 'Eliminar reglas';
$string['coursedynamicrules:manageaction'] = 'Gestionar acciones';
$string['coursedynamicrules:managecondition'] = 'Gestionar condiciones';
$string['coursedynamicrules:managerule'] = 'Gestionar reglas';
$string['coursedynamicrules:notification'] = 'Enviar notificación';
$string['coursedynamicrules:updateaction'] = 'Actualizar acciones';
$string['coursedynamicrules:updatecondition'] = 'Actualizar condiciones';
$string['coursedynamicrules:updaterule'] = 'Actualizar reglas';
$string['coursedynamicrules:viewaction'] = 'Ver acciones';
$string['coursedynamicrules:viewcondition'] = 'Ver condiciones';
$string['coursedynamicrules:viewrule'] = 'Ver reglas';
$string['courselink'] = 'Enlace del curso';
$string['coursename'] = 'Nombre del curso';
$string['coursestartdate'] = 'Fecha de inicio del curso';
$string['createaiactivity'] = 'Crear actividad de refuerzo con IA';
$string['createaiactivity_action_info'] = 'Esta acción solicitará al servicio Datacurso AI que genere una actividad de refuerzo personalizada para los usuarios que cumplan las condiciones de la regla.';
$string['createaiactivity_beforemod'] = 'Colocar antes de la actividad';
$string['createaiactivity_beforemod_help'] = 'Seleccione la actividad que el nuevo recurso debe preceder, o mantenga la opción predeterminada para añadirlo al final de la sección.';
$string['createaiactivity_beforemod_none'] = 'No posicionar antes de otra actividad';
$string['createaiactivity_description'] = 'Generar una actividad de refuerzo con IA en la sección "{$a->section}" usando el prompt "{$a->prompt}"';
$string['createaiactivity_generateimages'] = 'Generar imágenes con IA';
$string['createaiactivity_generateimages_label'] = 'Permitir que la IA incluya imágenes generadas cuando sea compatible.';
$string['createaiactivity_placeholders_info'] = 'Marcadores disponibles: <code>{$a->coursename}</code>, <code>{$a->courseurl}</code>, <code>{$a->fullname}</code>, <code>{$a->firstname}</code>, <code>{$a->lastname}</code>.';
$string['createaiactivity_prompt'] = 'Prompt de IA';
$string['createaiactivity_prompt_help'] = 'Escriba la instrucción que se enviará al servicio de IA. Puede incluir marcadores que se reemplazarán antes de enviar el prompt.';
$string['createaiactivity_section'] = 'Sección del curso';
$string['createrule'] = 'Crear regla';
$string['customintervals'] = 'Intervalos personalizados';
$string['customintervals_help'] = 'Ingrese números separados por comas que representen periodos de inactividad (p. ej., "7,14,30").';
$string['date_from_course_start'] = 'Desde la fecha de inicio del curso';
$string['date_from_enrollment'] = 'Desde la fecha de matrícula';
$string['date_from_now'] = 'Desde ahora';
$string['days'] = 'Días';
$string['deleteactioncheck'] = '¿Está absolutamente seguro de que desea eliminar por completo esta acción?';
$string['deletecondition'] = 'Eliminar condición';
$string['deleteconditioncheck'] = '¿Está absolutamente seguro de que desea eliminar por completo esta condición?';
$string['deletedaction'] = 'Acción eliminada <b>{$a}</b>';
$string['deletedcondition'] = 'Condición eliminada <b>{$a}</b>';
$string['deletedrule'] = 'Regla eliminada <b>{$a}</b>';
$string['deleterule'] = 'Eliminar regla';
$string['deleterulecheck'] = '¿Está absolutamente seguro de que desea eliminar por completo esta regla?';
$string['deletingcondition'] = 'Eliminando condición "{$a}"';
$string['deletingrule'] = 'Eliminando regla "{$a}"';
$string['description'] = 'Descripción';
$string['editactions'] = 'Editar acciones';
$string['editconditions'] = 'Editar condiciones';
$string['editrule'] = 'Editar regla';
$string['enableactivity'] = 'Habilitar actividad';
$string['enableactivity_action_info'] = 'Esta acción habilitará los módulos de actividades seleccionados para los usuarios que cumplan los criterios de la regla.';
$string['enableactivity_description'] = 'Habilitar actividades "{$a}"';
$string['enablegradegreaterthanorequal_help'] = 'Habilitar calificación mayor o igual que';
$string['enablegradelessthan'] = 'Habilitar calificación menor que';
$string['enrollmentdate'] = 'Fecha de matrícula';
$string['errorgradeoutofrange'] = 'El valor debe estar entre {$a->min} y {$a->max}.';
$string['errormaxgradeexceeded'] = 'La calificación no puede exceder la calificación máxima de la actividad.';
$string['errornegativegrade'] = 'La calificación debe ser 0 o mayor.';
$string['expectedcompletiondate'] = 'Fecha de finalización prevista';
$string['firstname'] = 'Nombre del usuario';
$string['fullname'] = 'Nombre completo del usuario';
$string['generalsettings'] = 'Configuración general';
$string['grade'] = 'Calificación';
$string['grade_in_activity'] = 'Calificación en la actividad';
$string['grade_in_activity_condition_info'] = 'Esta condición comprobará qué usuario ha obtenido la calificación indicada en el módulo de actividad seleccionado.';
$string['grade_in_activity_description'] = 'Para "{$a->moddescription}", se deben obtener las siguientes calificaciones: {$a->gradestring}';
$string['gradegreaterthanorequal'] = 'debe ser &#x2265;';
$string['gradegreaterthanorequal_help'] = 'La condición se cumple si la calificación del usuario es mayor o igual que el valor especificado.';
$string['gradegreaterthanorequalvalue'] = '&#x2265; {$a}';
$string['gradelessthan'] = 'debe ser <';
$string['gradelessthan_help'] = 'La condición se cumple si la calificación del usuario es menor que el valor especificado.';
$string['gradelessthanvalue'] = '< {$a}';
$string['hours'] = 'Horas';
$string['intervaltype'] = 'Tipo de intervalo';
$string['intervaltype_help'] = 'Seleccione cómo se evaluará el intervalo:

* **Intervalos personalizados**: Para agregar valores separados por comas (p. ej., 7,14,30) y evaluar la inactividad en puntos de tiempo específicos.
* **Intervalo recurrente**: Para evaluar la inactividad en intervalos recurrentes (p. ej., cada 7 días).';
$string['intervalunit'] = 'Unidad de tiempo';
$string['intervalunit_help'] = 'Seleccione la unidad de tiempo para los intervalos.';
$string['invalidbasedate'] = 'Tipo de fecha base no válido {$a}';
$string['invalidruleid'] = 'ID de regla no válido';
$string['lastname'] = 'Apellido del usuario';
$string['licensekey'] = 'Clave de licencia';
$string['licensekey_desc'] = 'Clave de licencia requerida para usar este complemento';
$string['licensekeycompany'] = 'Clave de licencia para: {$a}';
$string['licensekeycompany_desc'] = 'Clave de licencia requerida para usar este complemento para la empresa: {$a}';
$string['licensekeyinvalid'] = 'La clave de licencia ha caducado o no es válida. Vaya a <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> para renovarla o comprar una nueva.';
$string['licensekeyvalid'] = 'La clave de licencia es válida';
$string['messagebody'] = 'Cuerpo';
$string['messagebody_help'] = 'Se pueden incluir los siguientes marcadores en el mensaje:

* Nombre del curso {$a->coursename}
* Nombre completo del usuario {$a->fullname}
* Nombre del usuario {$a->firstname}
* Apellido del usuario {$a->lastname}
* Nombre del módulo de actividad del curso {$a->modulename}
* Nombre de la instancia del módulo de actividad del curso {$a->moduleinstancename}';
$string['messageprovider:smart_rules_ai_notification'] = 'Notificación de Smart Rules AI';
$string['messagesubject'] = 'Asunto';
$string['minutes'] = 'Minutos';
$string['missing_plugins_warning'] = '🔔 ¡Mejore sus notificaciones! Nuestros complementos de <strong>Datacurso Message Hub</strong> le permiten enviar notificaciones por WhatsApp y SMS utilizando proveedores como Twilio.
<br>
<a href="https://shop.datacurso.com/clientarea.php" target="_blank">¡Haga clic aquí para comprarlos y habilitarlos ahora!</a>';
$string['moduleinstancename'] = 'Nombre de la instancia del módulo de actividad del curso';
$string['modulename'] = 'Nombre del módulo de actividad del curso';
$string['months'] = 'Meses';
$string['mustselectonerole'] = 'Debe seleccionar al menos un rol.';
$string['name'] = 'Nombre';
$string['no_complete_activity'] = 'Actividad no completada';
$string['no_complete_activity_condition_info'] = 'Esta condición comprobará qué usuario no ha completado el módulo de actividad seleccionado después de la fecha especificada.';
$string['no_complete_activity_description'] = 'Usuarios que no han completado el módulo de actividad del curso "{$a->moddescription}" después de {$a->expectedcompletiondate}';
$string['no_complete_activity_task'] = 'Tarea de actividad no completada';
$string['no_course_access'] = 'Sin acceso al curso';
$string['no_course_access_condition_info'] = 'Esta condición comprobará qué usuarios no han accedido a este curso dentro del periodo de tiempo especificado.';
$string['no_course_access_description'] = 'Usuarios que tardan más de {$a->periodvalue} {$a->periodunit} sin acceder a este curso.';
$string['no_course_access_task'] = 'Tarea de falta de acceso al curso';
$string['notification_action_info'] = 'Esta acción enviará una notificación a los usuarios que cumplan los criterios de la regla.';
$string['now'] = 'Ahora';
$string['passgrade'] = 'Finalización de actividad con calificación aprobatoria';
$string['passgrade_condition_info'] = 'Esta condición comprobará qué usuario ha completado el módulo de actividad seleccionado con calificación aprobatoria.';
$string['passgrade_description'] = 'Usuarios que han completado el módulo de actividad del curso "{$a}" con calificación aprobatoria';
$string['period'] = 'Periodo';
$string['period_help'] = 'La cantidad mínima de tiempo que un usuario debe pasar sin acceder al curso.';
$string['plugin_disabled'] = 'Esta acción requiere que el complemento <strong>{$a->pluginname}</strong> esté habilitado. Acceda a la página <a href="{$a->enableurl}" target="_blank">{$a->enableurl}</a>, busque <strong>{$a->visiblename}</strong> y habilítelo.';
$string['plugin_missing'] = 'Esta acción requiere que el complemento <strong>{$a->pluginname}</strong> esté instalado y habilitado. Descárguelo desde <a href="{$a->downloadurl}" target="_blank">{$a->downloadurl}</a> e instálelo.';
$string['pluginname'] = 'Smart Rules AI';
$string['pluginnotavailable'] = 'Este complemento no está disponible porque la licencia del producto ha caducado o no es válida. Vaya a <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> para renovarla o comprar una nueva.';
$string['provider_not_enabled_warning'] = 'Habilite las notificaciones con <strong>Datacurso Message Hub</strong> para que esta acción envíe notificaciones por WhatsApp y SMS utilizando proveedores como Twilio.
Puede habilitarlo desde <a href="{$a}" target="_blank">Configuración de notificaciones</a> buscando <strong>Smart Rules AI notification</strong>.
<br>
<a href="https://docs.datacurso.com/index.php?title=Message_Hub" target="_blank">Consulte la documentación para más información.</a>';
$string['recurringinterval'] = 'Intervalo recurrente';
$string['recurringinterval_help'] = 'Ingrese un valor numérico que represente un intervalo recurrente de inactividad (p. ej., "7" para cada 7 días de inactividad).';
$string['rolestonotify'] = 'Roles a notificar';
$string['rolestonotify_help'] = 'Seleccione los roles que el usuario debe tener para recibir la notificación. Debe seleccionar al menos uno.';
$string['ruleactive'] = 'Activa';
$string['ruleactive_help'] = 'Habilitar o deshabilitar la regla';
$string['ruleadd'] = 'Añadir regla';
$string['ruleaddedsuccessfully'] = 'Regla añadida correctamente';
$string['ruleinactive'] = 'Inactiva';
$string['rules'] = 'Reglas';
$string['rules_help'] = 'Las reglas se utilizan para definir un conjunto de condiciones y acciones que se ejecutarán';
$string['ruleupdatedsuccessfully'] = 'Regla actualizada correctamente';
$string['searchcourseactivitymodules'] = 'Buscar módulos de actividad del curso';
$string['sendnotification'] = 'Enviar notificación';
$string['sendnotification_description'] = 'Enviar notificación "{$a}" a los usuarios';
$string['typemissing'] = 'Falta el valor "type"';
$string['weeks'] = 'Semanas';
