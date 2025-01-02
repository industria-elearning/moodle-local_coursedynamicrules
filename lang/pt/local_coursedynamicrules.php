<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'local_coursedynamicrules', language 'pt'
 *
 * @package    local_coursedynamicrules
 * @category   string
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
$string['pluginname'] = 'Regras dinâmicas do curso';
$string['coursedynamicrules:notification'] = 'Enviar notificação';
$string['messageprovider:coursedynamicrules_notification'] = 'Notificação de regras dinâmicas do curso';
$string['coursedynamicrules:managerule'] = 'Gerir regras';
$string['coursedynamicrules:createrule'] = 'Criar regras';
$string['coursedynamicrules:updaterule'] = 'Atualizar regras';
$string['coursedynamicrules:viewrule'] = 'Ver regras';
$string['coursedynamicrules:deleterule'] = 'Eliminar regras';
$string['coursedynamicrules:manageaction'] = 'Gerir ações';
$string['coursedynamicrules:createaction'] = 'Criar ações';
$string['coursedynamicrules:updateaction'] = 'Atualizar ações';
$string['coursedynamicrules:viewaction'] = 'Ver ações';
$string['coursedynamicrules:deleteaction'] = 'Eliminar ações';
$string['coursedynamicrules:managecondition'] = 'Gerir condições';
$string['coursedynamicrules:createcondition'] = 'Criar condições';
$string['coursedynamicrules:updatecondition'] = 'Atualizar condições';
$string['coursedynamicrules:viewcondition'] = 'Ver condições';
$string['coursedynamicrules:deletecondition'] = 'Eliminar condições';
$string['typemissing'] = 'Valor em falta "tipo"';
$string['name'] = 'Nome';
$string['description'] = 'Descrição';
$string['conditions'] = 'Condições';
$string['actions'] = 'Ações';
$string['ruleactive'] = 'Ativo';
$string['ruleactive_help'] = 'Ativar ou desativar a regra';
$string['ruleadd'] = 'Adicionar regra';
$string['ruleaddedsuccessfully'] = 'Regra adicionada com sucesso';
$string['editrule'] = 'Editar regra';
$string['deleterule'] = 'Eliminar regra';
$string['addconditions'] = 'Adicionar condições';
$string['editconditions'] = 'Editar condições';
$string['passgrade'] = 'Conclusão de atividade com nota de aprovação';
$string['allcourseactivitymodules'] = 'Todos os módulos de atividade do curso';
$string['searchcourseactivitymodules'] = 'Pesquisar módulos de atividade do curso';
$string['passgrade_description'] = 'Utilizadores que concluíram o módulo de atividade \'{$a}\' com nota de aprovação';
$string['no_complete_activity_description'] = 'Utilizadores que não concluíram o módulo de atividade \'{$a->moddescription}\' após {$a->expectedcompletiondate}';
$string['invalidruleid'] = 'ID de regra inválido';
$string['deletecondition'] = 'Eliminar condição';
$string['messagesubject'] = 'Assunto';
$string['messagebody'] = 'Corpo';
$string['messagebody_help'] = 'Os seguintes marcadores podem ser incluídos na mensagem:

* Nome do curso {$a->coursename}
* Nome completo do utilizador {$a->fullname}
* Nome próprio do utilizador {$a->firstname}
* Apelido do utilizador {$a->lastname}
* Nome do módulo de atividade do curso {$a->modulename}
* Nome da instância do módulo de atividade do curso {$a->moduleinstancename}';
$string['sendnotification'] = 'Enviar notificação';
$string['sendnotification_description'] = 'Enviar notificação \'{$a}\' aos utilizadores';
$string['addactions'] = 'Adicionar ações';
$string['editactions'] = 'Editar ações';
$string['backtolistrules'] = 'Voltar à lista de regras';
$string['availableplaceholders'] = 'Marcadores disponíveis';
$string['coursename'] = 'Nome do curso';
$string['courselink'] = 'Ligação do curso';
$string['fullname'] = 'Nome completo do utilizador';
$string['firstname'] = 'Nome próprio do utilizador';
$string['lastname'] = 'Apelido do utilizador';
$string['modulename'] = 'Nome do módulo de atividade do curso';
$string['moduleinstancename'] = 'Nome da instância do módulo de atividade do curso';
$string['deletingrule'] = 'A eliminar regra \'{$a}\'';
$string['deletingcondition'] = 'A eliminar condição \'{$a}\'';
$string['deleterulecheck'] = 'Tem a certeza absoluta que pretende eliminar completamente esta regra?';
$string['deleteconditioncheck'] = 'Tem a certeza absoluta que pretende eliminar completamente esta condição?';
$string['deleteactioncheck'] = 'Tem a certeza absoluta que pretende eliminar completamente esta ação?';
$string['deletedrule'] = 'Regra eliminada <b>{$a}</b>';
$string['deletedcondition'] = 'Condição eliminada <b>{$a}</b>';
$string['deletedaction'] = 'Ação eliminada <b>{$a}</b>';
$string['ruleupdatedsuccessfully'] = 'Regra atualizada com sucesso';
$string['createrule'] = 'Criar Regra';
$string['completiondate'] = 'Data de conclusão';
$string['before'] = 'Antes';
$string['after'] = 'Depois';
$string['no_complete_activity'] = 'Atividade não concluída';
$string['no_complete_activity_task'] = 'Tarefa de atividade não concluída';
$string['expectedcompletiondate'] = 'Data prevista de conclusão';
$string['grade_in_activity'] = 'Nota na atividade';
$string['grade_in_activity_description'] = 'Para "{$a->moddescription}", devem ser obtidas as seguintes notas: {$a->gradestring}';
$string['grade'] = 'Nota';
$string['enablegradegreaterthanorequal_help'] = 'Ativar nota maior ou igual a';
$string['enablegradelessthan'] = 'Ativar nota menor que';
$string['errornegativegrade'] = 'A nota deve ser 0 ou superior.';
$string['errormaxgradeexceeded'] = 'A nota não pode exceder a nota máxima para a atividade.';
$string['enableactivity'] = 'Ativar atividade';
$string['enableactivity_description'] = 'Ativar atividades \'{$a}\'';
$string['errorgradeoutofrange'] = 'O valor deve estar entre {$a->min} e {$a->max}.';
$string['notification_action_info'] = 'Esta ação enviará uma notificação aos utilizadores que cumpram os critérios das condições da regra.';
$string['missing_plugins_warning'] = '🔔 Melhore as suas notificações! Os nossos plugins <strong>Datacurso Message Hub</strong> permitem enviar notificações via WhatsApp e SMS utilizando fornecedores como Twilio.
<br>
<a href="https://shop.datacurso.com/clientarea.php" target="_blank">Clique aqui para comprar e ativar agora!</a>';
$string['provider_not_enabled_warning'] = 'Ative as notificações com o <strong>Datacurso Message Hub</strong> para esta ação enviar notificações via WhatsApp e SMS utilizando fornecedores como Twilio.
Pode ativar nas <a href="{$a}" target="_blank">Definições de notificação</a> e procurar <strong>Notificação de regras dinâmicas do curso</strong>.
<br>
<a href="https://docs.datacurso.com/index.php?title=Message_Hub" target="_blank">Consulte a documentação para mais informações.</a>';
$string['rules'] = 'Regras';
$string['rules_help'] = 'As regras são usadas para definir um conjunto de condições e ações que serão executadas';
$string['missing_availability_user'] = 'Esta ação requer que o plugin <strong>Restrição por usuário</strong> esteja instalado e ativado. Por favor, faça o download em <a href="https://moodle.org/plugins/availability_user/versions" target="_blank">https://moodle.org/plugins/availability_user/versions</a> e instale-o.';
$string['disabled_availability_user'] = 'Esta ação requer que o plugin <strong>Restrição por usuário</strong> esteja ativado. Por favor, acesse a página <a href="{$a}" target="_blank">Gerenciar restrições</a>, procure por <strong>Restrição por usuário</strong> e ative-o.';
$string['enableactivity_action_info'] = 'Esta ação habilitará os módulos de atividades selecionados para os usuários que atenderem aos critérios das condições da regra.';
$string['grade_in_activity_condition_info'] = 'Esta condição verificará qual usuário obteve a nota especificada no módulo de atividade selecionado.';
$string['no_complete_activity_condition_info'] = 'Esta condição verificará qual usuário não concluiu o módulo de atividade selecionado após a data especificada.';
$string['passgrade_condition_info'] = 'Esta condição verificará qual usuário concluiu o módulo de atividade selecionado com uma nota de aprovação.';
$string['licencekey'] = 'Chave de licença';
$string['generalsettings'] = 'Configurações gerais';
$string['checklicensekey'] = 'Verificar chave de licença';
$string['licensekeyvalid'] = 'A chave de licença é válida';
$string['licensekeyinvalid'] = 'A chave de licença expirou ou é inválida. Por favor, vá para <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> para renovar ou comprar uma nova licença.';
$string['gradegreaterthanorequal'] = 'deve ser &#x2265;';
$string['gradegreaterthanorequal_help'] = 'A condição é satisfeita se a nota do utilizador for maior ou igual ao valor especificado.';
$string['gradelessthan'] = 'deve ser <';
$string['gradelessthan_help'] = 'A condição é satisfeita se a nota do utilizador for menor que o valor especificado.';

