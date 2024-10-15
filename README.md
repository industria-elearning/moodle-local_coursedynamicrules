# Course dynamic rules #

TODO Describe the plugin shortly here.

TODO Provide more detailed description here.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/coursedynamicrules

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2024 Industria Elearning <info@industriaelearning.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.

# Roadmap 

Se debe crear un plugin que permita agregar reglas dinamicas a las actividades de un curso.

Como punto de partida se tomarán las siguientes reglas:

### Condiciones

1. Condiciones Basadas en Finalización
     - **Finalización de actividad específica**: La regla se activa cuando una actividad (tarea, cuestionario, foro, etc.) se marca como completada.
     - **No finalización dentro de un plazo**: Si una actividad no se completa dentro de un período de tiempo establecido.

### Acciones
1. Enviar notificación de recordatorio a los usuarios coincidentes
