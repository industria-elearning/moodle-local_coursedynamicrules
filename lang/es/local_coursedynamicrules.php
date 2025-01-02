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
$string['actions'] = 'Acciones';
$string['ruleactive'] = 'Activa';
$string['ruleactive_help'] = 'Habilitar o deshabilitar la regla';
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
$string['gradegreaterthanorequal'] = 'debe ser &#x2265;';
$string['gradegreaterthanorequal_help'] = 'Calificación mínima (inclusiva)';
$string['gradelessthan'] = 'debe ser <';
$string['gradelessthan_help'] = 'Calificación máxima (exclusiva)';
$string['enablegradelessthan'] = 'Habilitar calificación menor que';
$string['errornegativegrade'] = 'La calificación debe ser 0 o mayor.';
$string['errormaxgradeexceeded'] = 'La calificación no puede exceder la calificación máxima para la actividad.';
$string['enableactivity'] = 'Habilitar actividad';
$string['enableactivity_description'] = 'Habilitar actividades \'{$a}\'';
$string['errorgradeoutofrange'] = 'El valor debe estar entre {$a->min} y {$a->max}.';
$string['notification_action_info'] = 'Esta acción enviará una notificación a los usuarios que cumplan con los criterios de las condiciones de la regla.';

$string['missing_plugins_warning'] = '🔔 ¡Mejora tus notificaciones! Nuestros plugins de <strong>Datacurso Message Hub</strong> te permiten enviar notificaciones por WhatsApp y SMS usando proveedores como Twilio.
<br>
<a href="https://yoursite.com/buy-plugins" target="_blank">Haz clic aquí para comprarlos y activarlos ahora!</a>';

$string['provider_not_enabled_warning'] = 'Habilita las notificaciones con <strong>Datacurso Message Hub</strong> para que esta acción envíe notificaciones por WhatsApp y SMS usando proveedores como Twilio.
Puedes habilitarlo/deshabilitarlo desde la <a href="{$a}" target="_blank">Configuración de notificaciones</a> buscando <strong>Notificación de reglas dinámicas del curso</strong>.
<br>
<a href="https://docs.com/plugin" target="_blank">Consulta la documentación para más información.</a>';








