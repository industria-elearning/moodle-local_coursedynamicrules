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

$string['actions'] = 'Actions';
$string['actions_help'] = 'Les actions servent à définir ce qui sera exécuté lorsque les conditions de la règle sont remplies';
$string['addactions'] = 'Ajouter des actions';
$string['addconditions'] = 'Ajouter des conditions';
$string['after'] = 'Après';
$string['allcourseactivitymodules'] = 'Tous les modules d’activité du cours';
$string['availableplaceholders'] = 'Variables disponibles';
$string['backtolistrules'] = 'Retour à la liste des règles';
$string['basedate'] = 'Date de référence';
$string['basedate_help'] = 'Choisissez la date de référence pour évaluer l’inactivité :

* **Depuis la date d’inscription** : Calcule depuis l’inscription de l’utilisateur.
* **Depuis la date de début du cours** : Calcule depuis le début du cours.
* **Depuis maintenant** : Calcule depuis la date actuelle.';
$string['before'] = 'Avant';
$string['checklicensekey'] = 'Vérifier la clé de licence';
$string['complete_activity'] = 'Activité terminée';
$string['complete_activity_condition_info'] = 'Cette condition vérifie quels utilisateurs ont terminé le module d’activité sélectionné.';
$string['complete_activity_description'] = 'Utilisateurs ayant terminé le module d’activité du cours « {$a->moddescription} »';
$string['completiondate'] = 'Date d’achèvement';
$string['conditions'] = 'Conditions';
$string['conditions_help'] = 'Les conditions définissent les critères à satisfaire pour exécuter les actions de la règle';
$string['copiedtoclipboard'] = 'Copié dans le presse‑papiers';
$string['copytoclipboard'] = 'Copier dans le presse‑papiers';
$string['course_inactivity'] = 'Inactivité du cours par intervalles de temps';
$string['course_inactivity_custom_description'] = 'Utilisateurs sans activité dans le cours pendant des intervalles de {$a->intervals} {$a->unit} à partir de {$a->basedate}';
$string['course_inactivity_info'] = 'Cette condition vérifie quels utilisateurs n’ont eu aucune activité dans le cours pendant les intervalles indiqués.';
$string['course_inactivity_recurring_description'] = 'Utilisateurs sans activité dans le cours à des intervalles récurrents de {$a->intervals} {$a->unit} à partir de {$a->basedate}';
$string['course_inactivity_task'] = 'Tâche d’inactivité du cours';
$string['coursedynamicrules:createaction'] = 'Créer des actions';
$string['coursedynamicrules:createcondition'] = 'Créer des conditions';
$string['coursedynamicrules:createrule'] = 'Créer des règles';
$string['coursedynamicrules:deleteaction'] = 'Supprimer des actions';
$string['coursedynamicrules:deletecondition'] = 'Supprimer des conditions';
$string['coursedynamicrules:deleterule'] = 'Supprimer des règles';
$string['coursedynamicrules:manageaction'] = 'Gérer les actions';
$string['coursedynamicrules:managecondition'] = 'Gérer les conditions';
$string['coursedynamicrules:managerule'] = 'Gérer les règles';
$string['coursedynamicrules:notification'] = 'Envoyer une notification';
$string['coursedynamicrules:updateaction'] = 'Mettre à jour les actions';
$string['coursedynamicrules:updatecondition'] = 'Mettre à jour les conditions';
$string['coursedynamicrules:updaterule'] = 'Mettre à jour les règles';
$string['coursedynamicrules:viewaction'] = 'Voir les actions';
$string['coursedynamicrules:viewcondition'] = 'Voir les conditions';
$string['coursedynamicrules:viewrule'] = 'Voir les règles';
$string['courselink'] = 'Lien du cours';
$string['coursename'] = 'Nom du cours';
$string['coursestartdate'] = 'Date de début du cours';
$string['createaiactivity'] = 'Créer une activité de renforcement par IA';
$string['createaiactivity_action_info'] = 'Cette action demandera au service Datacurso AI de générer une activité de renforcement personnalisée pour les utilisateurs qui remplissent les conditions de la règle.';
$string['createaiactivity_beforemod'] = 'Placer avant l’activité';
$string['createaiactivity_beforemod_help'] = 'Sélectionnez l’activité que la nouvelle ressource doit précéder, ou conservez l’option par défaut pour l’ajouter à la fin de la section.';
$string['createaiactivity_beforemod_none'] = 'Ne pas positionner avant une autre activité';
$string['createaiactivity_description'] = 'Générer une activité de renforcement IA dans la section « {$a->section} » avec le prompt « {$a->prompt} »';
$string['createaiactivity_generateimages'] = 'Générer des images avec l’IA';
$string['createaiactivity_generateimages_label'] = 'Autoriser l’IA à inclure des images générées lorsque cela est pris en charge.';
$string['createaiactivity_placeholders_info'] = 'Variables disponibles : <code>{$a->coursename}</code>, <code>{$a->courseurl}</code>, <code>{$a->fullname}</code>, <code>{$a->firstname}</code>, <code>{$a->lastname}</code>.';
$string['createaiactivity_prompt'] = 'Invite IA';
$string['createaiactivity_prompt_help'] = 'Saisissez l’instruction qui sera envoyée au service d’IA. Vous pouvez inclure des variables qui seront remplacées avant l’envoi.';
$string['createaiactivity_section'] = 'Section du cours';
$string['createrule'] = 'Créer une règle';
$string['customintervals'] = 'Intervalles personnalisés';
$string['customintervals_help'] = 'Saisissez des nombres séparés par des virgules représentant des périodes d’inactivité (ex. « 7,14,30 »).';
$string['date_from_course_start'] = 'Depuis la date de début du cours';
$string['date_from_enrollment'] = 'Depuis la date d’inscription';
$string['date_from_now'] = 'Depuis maintenant';
$string['days'] = 'Jours';
$string['deleteactioncheck'] = 'Êtes‑vous absolument sûr de vouloir supprimer complètement cette action ?';
$string['deletecondition'] = 'Supprimer la condition';
$string['deleteconditioncheck'] = 'Êtes‑vous absolument sûr de vouloir supprimer complètement cette condition ?';
$string['deletedaction'] = 'Action supprimée <b>{$a}</b>';
$string['deletedcondition'] = 'Condition supprimée <b>{$a}</b>';
$string['deletedrule'] = 'Règle supprimée <b>{$a}</b>';
$string['deleterule'] = 'Supprimer la règle';
$string['deleterulecheck'] = 'Êtes‑vous absolument sûr de vouloir supprimer complètement cette règle ?';
$string['deletingcondition'] = 'Suppression de la condition « {$a} »';
$string['deletingrule'] = 'Suppression de la règle « {$a} »';
$string['description'] = 'Description';
$string['editactions'] = 'Modifier les actions';
$string['editconditions'] = 'Modifier les conditions';
$string['editrule'] = 'Modifier la règle';
$string['enableactivity'] = 'Activer l’activité';
$string['enableactivity_action_info'] = 'Cette action activera les modules d’activité sélectionnés pour les utilisateurs qui répondent aux critères de la règle.';
$string['enableactivity_description'] = 'Activer les activités « {$a} »';
$string['enablegradegreaterthanorequal_help'] = 'Activer la note supérieure ou égale à';
$string['enablegradelessthan'] = 'Activer la note inférieure à';
$string['enrollmentdate'] = 'Date d’inscription';
$string['errorgradeoutofrange'] = 'La valeur doit être comprise entre {$a->min} et {$a->max}.';
$string['errormaxgradeexceeded'] = 'La note ne peut pas dépasser la note maximale de l’activité.';
$string['errornegativegrade'] = 'La note doit être supérieure ou égale à 0.';
$string['expectedcompletiondate'] = 'Date d’achèvement prévue';
$string['firstname'] = 'Prénom de l’utilisateur';
$string['fullname'] = 'Nom complet de l’utilisateur';
$string['generalsettings'] = 'Paramètres généraux';
$string['grade'] = 'Note';
$string['grade_in_activity'] = 'Note dans l’activité';
$string['grade_in_activity_condition_info'] = 'Cette condition vérifiera quel utilisateur a obtenu la note spécifiée dans le module d’activité sélectionné.';
$string['grade_in_activity_description'] = 'Pour « {$a->moddescription} », les notes suivantes doivent être obtenues : {$a->gradestring}';
$string['gradegreaterthanorequal'] = 'doit être &#x2265;';
$string['gradegreaterthanorequal_help'] = 'La condition est remplie si la note de l’utilisateur est supérieure ou égale à la valeur spécifiée.';
$string['gradegreaterthanorequalvalue'] = '&#x2265; {$a}';
$string['gradelessthan'] = 'doit être <';
$string['gradelessthan_help'] = 'La condition est remplie si la note de l’utilisateur est inférieure à la valeur spécifiée.';
$string['gradelessthanvalue'] = '< {$a}';
$string['hours'] = 'Heures';
$string['intervaltype'] = 'Type d’intervalle';
$string['intervaltype_help'] = 'Sélectionnez la façon dont l’intervalle sera évalué :

* **Intervalles personnalisés** : pour ajouter des valeurs séparées par des virgules (p. ex., 7,14,30) afin d’évaluer l’inactivité à des moments spécifiques.
* **Intervalle récurrent** : pour évaluer l’inactivité à des intervalles récurrents (p. ex., tous les 7 jours).';
$string['intervalunit'] = 'Unité de temps';
$string['intervalunit_help'] = 'Sélectionnez l’unité de temps pour les intervalles.';
$string['invalidbasedate'] = 'Type de date de référence invalide {$a}';
$string['invalidruleid'] = 'ID de règle invalide';
$string['lastname'] = 'Nom de famille de l’utilisateur';
$string['licensekey'] = 'Clé de licence';
$string['licensekey_desc'] = 'Clé de licence requise pour utiliser ce plugin';
$string['licensekeycompany'] = 'Clé de licence pour : {$a}';
$string['licensekeycompany_desc'] = 'Clé de licence requise pour utiliser ce plugin pour l’entreprise : {$a}';
$string['licensekeyinvalid'] = 'La clé de licence a expiré ou n’est pas valide. Veuillez aller sur <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> pour la renouveler ou en acheter une nouvelle.';
$string['licensekeyvalid'] = 'La clé de licence est valide';
$string['messagebody'] = 'Corps';
$string['messagebody_help'] = 'Les variables suivantes peuvent être incluses dans le message :

* Nom du cours {$a->coursename}
* Nom complet de l’utilisateur {$a->fullname}
* Prénom de l’utilisateur {$a->firstname}
* Nom de famille de l’utilisateur {$a->lastname}
* Nom du module d’activité du cours {$a->modulename}
* Nom de l’instance du module d’activité du cours {$a->moduleinstancename}';
$string['messageprovider:smart_rules_ai_notification'] = 'Notification Smart Rules AI';
$string['messagesubject'] = 'Objet';
$string['minutes'] = 'Minutes';
$string['missing_plugins_warning'] = '🔔 Améliorez vos notifications ! Nos plugins <strong>Datacurso Message Hub</strong> vous permettent d’envoyer des notifications via WhatsApp et SMS avec des fournisseurs comme Twilio.
<br>
<a href="https://shop.datacurso.com/clientarea.php" target="_blank">Cliquez ici pour les acheter et les activer maintenant !</a>';
$string['moduleinstancename'] = 'Nom de l’instance du module d’activité du cours';
$string['modulename'] = 'Nom du module d’activité du cours';
$string['months'] = 'Mois';
$string['mustselectonerole'] = 'Vous devez sélectionner au moins un rôle.';
$string['name'] = 'Nom';
$string['no_complete_activity'] = 'Activité non terminée';
$string['no_complete_activity_condition_info'] = 'Cette condition vérifie quel utilisateur n’a pas terminé le module d’activité sélectionné après la date indiquée.';
$string['no_complete_activity_description'] = 'Utilisateurs qui n’ont pas terminé le module d’activité du cours « {$a->moddescription} » après {$a->expectedcompletiondate}';
$string['no_complete_activity_task'] = 'Tâche activité non terminée';
$string['no_course_access'] = 'Aucun accès au cours';
$string['no_course_access_condition_info'] = 'Cette condition vérifie quels utilisateurs n’ont pas accédé à ce cours pendant la période spécifiée.';
$string['no_course_access_description'] = 'Utilisateurs restant plus de {$a->periodvalue} {$a->periodunit} sans accéder à ce cours.';
$string['no_course_access_task'] = 'Tâche absence d’accès au cours';
$string['notification_action_info'] = 'Cette action enverra une notification aux utilisateurs qui répondent aux critères de la règle.';
$string['now'] = 'Maintenant';
$string['passgrade'] = 'Achèvement de l’activité avec note de passage';
$string['passgrade_condition_info'] = 'Cette condition vérifie quel utilisateur a terminé le module d’activité sélectionné avec une note de passage.';
$string['passgrade_description'] = 'Utilisateurs ayant terminé le module d’activité du cours « {$a} » avec une note de passage';
$string['period'] = 'Période';
$string['period_help'] = 'La durée minimale pendant laquelle un utilisateur doit rester sans accéder au cours.';
$string['plugin_disabled'] = 'Cette action nécessite que le plugin <strong>{$a->pluginname}</strong> soit activé. Veuillez accéder à la page <a href="{$a->enableurl}" target="_blank">{$a->enableurl}</a>, rechercher <strong>{$a->visiblename}</strong> et l’activer.';
$string['plugin_missing'] = 'Cette action nécessite que le plugin <strong>{$a->pluginname}</strong> soit installé et activé. Veuillez le télécharger depuis <a href="{$a->downloadurl}" target="_blank">{$a->downloadurl}</a> et l’installer.';
$string['pluginname'] = 'Smart Rules AI';
$string['pluginnotavailable'] = 'Ce plugin n’est pas disponible, car la licence du produit a expiré ou n’est pas valide. Veuillez vous rendre sur <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> pour la renouveler ou en acheter une nouvelle.';
$string['provider_not_enabled_warning'] = 'Activez les notifications avec <strong>Datacurso Message Hub</strong> pour que cette action envoie des notifications via WhatsApp et SMS avec des fournisseurs comme Twilio.
Vous pouvez l’activer depuis <a href="{$a}" target="_blank">Paramètres des notifications</a> en recherchant <strong>Smart Rules AI notification</strong>.
<br>
<a href="https://docs.datacurso.com/index.php?title=Message_Hub" target="_blank">Voir la documentation pour plus d’informations.</a>';
$string['recurringinterval'] = 'Intervalle récurrent';
$string['recurringinterval_help'] = 'Saisissez une valeur numérique représentant un intervalle récurrent d’inactivité (par ex. « 7 » pour tous les 7 jours d’inactivité).';
$string['rolestonotify'] = 'Rôles à notifier';
$string['rolestonotify_help'] = 'Sélectionnez les rôles que l’utilisateur doit avoir pour recevoir la notification. Vous devez en sélectionner au moins un.';
$string['ruleactive'] = 'Active';
$string['ruleactive_help'] = 'Activer ou désactiver la règle';
$string['ruleadd'] = 'Ajouter une règle';
$string['ruleaddedsuccessfully'] = 'Règle ajoutée avec succès';
$string['ruleinactive'] = 'Inactive';
$string['rules'] = 'Règles';
$string['rules_help'] = 'Les règles servent à définir un ensemble de conditions et d’actions qui seront exécutées';
$string['ruleupdatedsuccessfully'] = 'Règle mise à jour avec succès';
$string['searchcourseactivitymodules'] = 'Rechercher des modules d’activité du cours';
$string['sendnotification'] = 'Envoyer une notification';
$string['sendnotification_description'] = 'Envoyer la notification « {$a} » aux utilisateurs';
$string['typemissing'] = 'Valeur « type » manquante';
$string['weeks'] = 'Semaines';
