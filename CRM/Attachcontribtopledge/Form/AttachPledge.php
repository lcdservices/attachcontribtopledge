<?php

use CRM_Attachcontribtopledge_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Attachcontribtopledge_Form_AttachPledge extends CRM_Core_Form {

  public $_contributionId;
  public $_contactId;
  public $_ppId;

  /**
   * check permissions
   */
  public function preProcess() {
    if (!CRM_Core_Permission::checkActionPermission('CiviPledge', CRM_Core_Action::UPDATE)) {
      CRM_Core_Error::statusBounce('You do not have permission to access this page.');
    }

    parent::preProcess();
  }

  public function buildQuickForm() {
    $this->_contributionId = CRM_Utils_Request::retrieve('id', 'Positive', $this);
    $this->_contactId = civicrm_api3('contribution', 'getvalue', [
      'id' => $this->_contributionId,
      'return' => 'contact_id',
    ]);

    $this->_ppId = CRM_Utils_Request::retrieve('ppid', 'Positive', $this);
    if ($this->_ppId) {
      try {
        $pledgeId = civicrm_api3('PledgePayment', 'getvalue', [
          'id' => $this->_ppId,
          'return' => 'pledge_id',
        ]);
        $pledge = civicrm_api3('Pledge', 'getsingle', ['id' => $pledgeId]);
        //Civi::log()->debug(__METHOD__, ['pledge' => $pledge]);

        $pledgeDate = date('m/d/Y', strtotime($pledge['pledge_start_date']));
        $pledgeLabel = '$'."{$pledge['pledge_amount']}, ".'$'."{$pledge['pledge_next_pay_amount']} per {$pledge['pledge_frequency_interval']} {$pledge['pledge_frequency_unit']} (starting {$pledgeDate})";
        $this->assign('existingPledge', $pledgeLabel);
      }
      catch (CiviCRM_API3_Exception $e) {}
    }

    //get current contact name.
    $this->assign('currentContactName', CRM_Contact_BAO_Contact::displayName($this->_contactId));

    $pledgeList = $pledges = [];
    try {
      $pledges = civicrm_api3('Pledge', 'get', ['contact_id' => $this->_contactId]);
    }
    catch (CiviCRM_API3_Exception $e) {}

    foreach ($pledges['values'] as $pledge) {
      //Civi::log()->debug(__METHOD__, ['pledge' => $pledge]);
      $pledgeDate = date('m/d/Y', strtotime($pledge['pledge_start_date']));
      $pledgeLabel = '$'."{$pledge['pledge_amount']}, ".'$'."{$pledge['pledge_next_pay_amount']} per {$pledge['pledge_frequency_interval']} {$pledge['pledge_frequency_unit']} (starting {$pledgeDate})";
      $pledgeList[$pledge['id']] = $pledgeLabel;
    }

    $this->add('select', 'pledge_id', ts('Select Pledge'), $pledgeList, TRUE);
    $this->add('hidden', 'contribution_id', $this->_contributionId, ['id' => 'contribution_id']);
    $this->add('hidden', 'contact_id', $this->_contactId, ['id' => 'contact_id']);
    $this->add('hidden', 'existing_pp_id', $this->_ppId, ['id' => 'existing_pp_id']);

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());

    $this->addButtons([
      [
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ],
    ]);

    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    //Civi::log()->debug('postProcess', array('values' => $values));

    //process
    $result = $this->attachToPledge($values);

    if ($result) {
      CRM_Core_Session::setStatus(ts('Contribution attached to pledge successfully.'), ts('Attached'), 'success');
    }
    else {
      CRM_Core_Session::setStatus(ts('Unable to attach contribution to pledge.'), ts('Error'), 'error');
    }

    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

  function attachToPledge($params) {
    try {
      $contrib = civicrm_api3('Contribution', 'getsingle', ['id' => $params['contribution_id']]);

      $ppParams = [
        'pledge_id' => $params['pledge_id'],
        'contribution_id' => $params['contribution_id'],
        'actual_amount' => $contrib['total_amount'],
        'status_id' => 'Completed',
      ];

      if (!empty($params['existing_pp_id'])) {
        $ppParams['id'] = $params['existing_pp_id'];
      }
      else {
        //get the earliest pledge payment for this pledge without a contrib ID attached
        $result = civicrm_api3('PledgePayment', 'get', [
          'sequential' => 1,
          'pledge_id' => $params['pledge_id'],
          'contribution_id' => ['IS NULL' => 1],
          'options' => ['limit' => 1, 'sort' => "id ASC"],
        ]);

        $ppParams['id'] = $result['id'];
      }

      $pp = civicrm_api3('PledgePayment', 'create', $ppParams);

      if ($pp) {
        $subject = "Contribution #{$params['contribution_id']} Attached to Pledge";
        $details = "Contribution #{$params['contribution_id']} was attached to pledge #{$params['pledge_id']}.";

        $activityTypeID = CRM_Core_OptionGroup::getValue('activity_type',
          'contribution_attached_to_pledge',
          'name'
        );

        $activityParams = [
          'activity_type_id' => $activityTypeID,
          'activity_date_time' => date('YmdHis'),
          'subject' => $subject,
          'details' => $details,
          'status_id' => 2,
        ];

        $activityParams['source_contact_id'] = CRM_Core_Session::getLoggedInContactID();
        $activityParams['target_contact_id'][] = $params['contact_id'];

        civicrm_api3('Activity', 'create', $activityParams);

        return TRUE;
      }
    }
    catch (CiviCRM_API3_Exception $e) {
      Civi::log()->debug(__FUNCTION__, ['$e' => $e]);
    }

    return FALSE;
  }
}
