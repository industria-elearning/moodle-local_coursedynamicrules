La regla asocia un grupo de condiciones con un grupo de acciones.

Las condiciones determinan cuando se ejecutan las acciones.

Las condiciones se evalúan a traves de eventos y tareas programadas.

## Caso #1:
Evento: Estudiante completa una actividad del un curso.
Condición: Validar si el estudiante ha completado la actividad con una nota de aprobación.
Acción: Enviar notificación al estudiante indicando que ha completado la actividad con una nota de aprobación.

## Caso #2:
Condición: Validar si una actividad no ha sido completada por un estudiante en un tiempo determinado.
Acción: Enviar notificación al estudiante indicando que no ha completado la actividad.


## Entidades
- Regla
- Condición
- Acción
- Evento
- Tarea programada
- Curso
- Estudiante

## TODO:
[ ] agregar campos en el formulario de creacion de la regla para definir cuantas veces se puede ejecutar la regla.

[ ] Agregar campos en la tabla `mdl_cdr_rule` para guardar la informacion de los nuevos campos agregados en el formulario de creacion de la regla.
