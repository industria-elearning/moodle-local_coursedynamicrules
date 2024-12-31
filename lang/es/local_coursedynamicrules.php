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
$string['rule:name'] = 'Nombre';
$string['rule:description'] = 'Descripción';
$string['rule:conditions'] = 'Condiciones';
$string['rule:actions'] = 'Acciones';
$string['rule:active'] = 'Activo';
$string['rule:active_help'] = 'Habilitar o deshabilitar la regla';
$string['rule:add'] = 'Agregar regla';
$string['rule:addedsuccessfully'] = 'Regla agregada con éxito';
$string['rule:edit'] = 'Editar regla';
$string['rule:delete'] = 'Eliminar regla';
$string['condition:add'] = 'Agregar condiciones';
$string['condition:edit'] = 'Editar condiciones';
$string['condition:passgrade'] = 'Finalización de actividad con calificación aprobatoria';
$string['allcourseactivitymodules'] = 'Todos los módulos de actividad del curso';
$string['searchcourseactivitymodules'] = 'Buscar módulos de actividad del curso';
$string['condition:passgrade:description'] = 'Usuarios que hayan completado el módulo de actividad del curso \'{$a}\' con una calificación aprobatoria';
$string['nocompleteactivity_description'] = 'Usuarios que no hayan completado el módulo de actividad del curso \'{$a->moddescription}\' después de {$a->expectedcompletiondate}';
$string['invalidruleid'] = 'ID de regla inválido';
$string['deletecondition'] = 'Eliminar condición';
$string['messagesubject'] = 'Asunto';
$string['messagebody'] = 'Cuerpo';
$string['messagebody_help'] = 'Se pueden incluir los siguientes marcadores de posición en el mensaje:

* Nombre del curso {$a->coursename}
* Nombre completo del usuario {$a->fullname}
* Nombre del usuario {$a->firstname}
* Apellido del usuario {$a->lastname}
* Nombre del módulo de actividad del curso {$a->modulename}
* Nombre de instancia del módulo de actividad del curso {$a->moduleinstancename}';
$string['sendnotification'] = 'Enviar notificación';
$string['sendnotification_description'] = 'Enviar la notificación \'{$a}\' a los usuarios';
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
$string['deletingrule'] = 'Eliminando la regla \'{$a}\'';
$string['deletingcondition'] = 'Eliminando la condición \'{$a}\'';
$string['deleterulecheck'] = '¿Está absolutamente seguro de que desea eliminar completamente esta regla?';
$string['deleteconditioncheck'] = '¿Está absolutamente seguro de que desea eliminar completamente esta condición?';
$string['deleteactioncheck'] = '¿Está absolutamente seguro de que desea eliminar completamente esta acción?';
$string['deletedrule'] = 'Regla eliminada <b>{$a}</b>';
$string['deletedcondition'] = 'Condición eliminada <b>{$a}</b>';
$string['deletedaction'] = 'Acción eliminada <b>{$a}</b>';
$string['rule:updatedsuccessfully'] = 'Regla actualizada con éxito';
$string['createrule'] = 'Crear regla';
$string['editrule'] = 'Editar regla';
$string['local_coursedynamicrules:editrules'] = 'Editar reglas dinámicas del curso';
$string['completiondate'] = 'Fecha de finalización';
$string['before'] = 'Antes';
$string['after'] = 'Después';
$string['condition:no_complete_activity'] = 'Actividad no completada';
$string['no_complete_activity_task'] = 'Tarea de actividad no completada';
$string['expectedcompletiondate'] = 'Fecha de finalización esperada';




