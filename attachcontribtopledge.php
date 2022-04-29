<?php

require_once 'attachcontribtopledge.civix.php';
// phpcs:disable
use CRM_Attachcontribtopledge_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function attachcontribtopledge_civicrm_config(&$config) {
  _attachcontribtopledge_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function attachcontribtopledge_civicrm_xmlMenu(&$files) {
  _attachcontribtopledge_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function attachcontribtopledge_civicrm_install() {
  _attachcontribtopledge_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function attachcontribtopledge_civicrm_postInstall() {
  _attachcontribtopledge_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function attachcontribtopledge_civicrm_uninstall() {
  _attachcontribtopledge_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function attachcontribtopledge_civicrm_enable() {
  _attachcontribtopledge_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function attachcontribtopledge_civicrm_disable() {
  _attachcontribtopledge_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function attachcontribtopledge_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _attachcontribtopledge_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function attachcontribtopledge_civicrm_managed(&$entities) {
  _attachcontribtopledge_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Add CiviCase types provided by this extension.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function attachcontribtopledge_civicrm_caseTypes(&$caseTypes) {
  _attachcontribtopledge_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Add Angular modules provided by this extension.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function attachcontribtopledge_civicrm_angularModules(&$angularModules) {
  // Auto-add module files from ./ang/*.ang.php
  _attachcontribtopledge_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function attachcontribtopledge_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _attachcontribtopledge_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function attachcontribtopledge_civicrm_entityTypes(&$entityTypes) {
  _attachcontribtopledge_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function attachcontribtopledge_civicrm_themes(&$themes) {
  _attachcontribtopledge_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function attachcontribtopledge_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function attachcontribtopledge_civicrm_navigationMenu(&$menu) {
//  _attachcontribtopledge_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _attachcontribtopledge_civix_navigationMenu($menu);
//}

function attachcontribtopledge_civicrm_searchColumns($objectName, &$headers, &$rows, &$selector) {
  /*Civi::log()->debug(__FUNCTION__, [
    'objectName' => $objectName,
    '$headers' => $headers,
    '$rows' => $rows,
    //'$selector' => $selector,
  ]);*/

  if ($objectName == 'contribution') {
    foreach ($rows as &$row) {
      //if currently attached to a membership, skip
      $memConnExists = CRM_Core_DAO::singleValueQuery("
        SELECT COUNT(id)
        FROM civicrm_membership_payment
        WHERE contribution_id = %1
      ", [
        1 => [$row['contribution_id'], 'Positive'],
      ]);

      if ($memConnExists) {
        continue;
      }

      //if currently attached to a participant, skip
      $partConnExists = CRM_Core_DAO::singleValueQuery("
        SELECT COUNT(id)
        FROM civicrm_participant_payment
        WHERE contribution_id = %1
      ", [
        1 => [$row['contribution_id'], 'Positive'],
      ]);

      if ($partConnExists) {
        continue;
      }

      //if currently attached to a pledge, skip
      //pledge payment records are less portable than membership/participant payments because of how
      //they are pre-constructed. so we can't carry this through and make it editable
      $pledgeConnId = CRM_Core_DAO::singleValueQuery("
        SELECT id
        FROM civicrm_pledge_payment
        WHERE contribution_id = %1
        LIMIT 1
      ", [
        1 => [$row['contribution_id'], 'Positive'],
      ]);

      if ($pledgeConnId) {
        continue;
      }

      $actionLabel = 'Attach';
      $pledgeParam = '';

      if ($pledgeConnId) {
        $actionLabel = 'Move';
        $pledgeParam = "&ppid={$pledgeConnId}";
      }

      //action column is either a series of links, or a series of links plus a subset
      //unordered list (more button) -- all of which is enclosed in a span
      //we want to inject our option at the end, regardless, so we look for the existence
      //of a <ul> tag and adjust our injection accordingly
      $url = CRM_Utils_System::url('civicrm/attachtopledge', "reset=1&id={$row['contribution_id']}{$pledgeParam}");
      $urlLink = "<a href='{$url}' class='action-item crm-hover-button medium-popup move-contrib'>{$actionLabel} to Pledge</a>";

      if (strpos($row['action'], '</ul>') !== FALSE) {
        $row['action'] = str_replace('</ul>', '<li>'.$urlLink.'</li></ul>', $row['action']);
      }
      else {
        //if there is no more... link, let's create one
        $more = "
        <span class='btn-slide crm-hover-button'>more
          <ul class='panel' style='display: none;'>
            <li>{$urlLink}</li>
          </ul>
        </span>
      ";
        $row['action'] = str_replace('</span>', '</span>'.$more, $row['action']);
      }
    }
  }
}