<?php

namespace Drupal\drupal8_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;

/**
 * This example uses Ajax to populate one dropdown based on the value of another.
 */
class ExampleAjaxForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_example_dependentdropdown';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Prepare the options that will be presented in the instrument family, and
    // instrument select lists.
    //
    // The first dropdown, instrument family, is a fixed set of options. String,
    // Woodwind, Brass, or Percussion.
    $instrument_family_options = static::getFirstDropdownOptions();
    // The options available in the second dropdown, instrument, depend on
    // what instrument family was chosen.
    //
    // If form state does not contain a value for instrument family yet -- for
    // example when the form is first built -- we can just use whatever is first
    // in the list of options for the instrument family field as the default.
    if (empty($form_state->getValue('instrument_family_dropdown'))) {
      // Use a default value.
      $selected_family = key($instrument_family_options);
    }
    // When the user changes the value of the instrument family field, an Ajax
    // request occurs that submits the form. Our submitForm() method sets the
    // rebuild flag, which causes buildForm() to be called again, and thus this
    // code gets executed and we can retrieve the current value of the
    // instrument family field from form state.
    //
    // Because we do this logic in buildForm() it has essentially the same
    // effect if the form is submitted without Ajax when someone clicks the
    // "Choose" button below. The difference is that when this is an Ajax
    // request our Ajax callback will be used to return a response, in which
    // case we can return just this updated field instead of the whole form.
    else {
      // Get the value if it already exists.
      $selected_family = $form_state->getValue('instrument_family_dropdown');
    }

    $form['instrument_family_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Choose an instrument family'),
    ];

    $form['instrument_family_fieldset']['instrument_family_dropdown'] = [
      '#type' => 'select',
      '#title' => $this->t('Instrument Type'),
      '#options' => $instrument_family_options,
      '#default_value' => $selected_family,
      // Bind an Ajax callback to the element.
      '#ajax' => [
        // Name of the method to call. This will be responsible for returning
        // a response, and will be called after submitForm() when processing an
        // Ajax request.
        'callback' => '::instrumentDropdownCallback',
        // ID of the element to affect after the Ajax request is complete. The
        // default action is to replace the specified DOM element with any new
        // content returned from the above callback.
        'wrapper' => 'instrument-fieldset-container',
        // The 'event' key can be used to determine what event will trigger the
        // Ajax request. Generally this can be left blank and Drupal will figure
        // out a sane default depending on the element type. However, you can
        // use this to specify any valid jQuery event such as, 'mousedown', 
        // 'blur', or 'submit'.
        'event' => 'change',
      ],
    ];

    // This submit button is for non JS enabled clients. We add the button to
    // the form, and then hide it using JS. If a non JS client views the page
    // the button will be displayed and can be used to submit the form. The
    // request will cause a page refresh, but will ultimately have the same
    // effect as the Ajax request. The form will be rebuilt, and displayed, and
    // the instrument type dropdown will be updated as necessary.
    //
    // Since we don't know if the user has JS or not, we always need to output
    // this element, then hide it if JavaScript is enabled.
    $form['instrument_family_fieldset']['choose_family'] = [
      '#type' => 'submit',
      '#value' => $this->t('Choose'),
      // This hides the button using the #states system. Because #states relies
      // on JavaScript, if it's not available this button won't be hidden.
      // You could also do this using CSS or custom JS.
      '#states' => [
        'visible' => ['body' => ['value' => TRUE]],
      ],
    ];

    // Create the instrument selection field, and a button to submit the form.
    // Since these both depend on the state/value of the instrument family field
    // we place them into a container. This container can then be updated after
    // each Ajax request.
    $form['instrument_fieldset_container'] = [
      '#type' => 'container',
      // Note that the ID here matches with the 'wrapper' value use for the
      // instrument family field's #ajax property.
      '#attributes' => ['id' => 'instrument-fieldset-container'],
    ];

    $form['instrument_fieldset_container']['instrument_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Choose an instrument'),
    ];

    $form['instrument_fieldset_container']['instrument_fieldset']['instrument_dropdown'] = [
      '#type' => 'select',
      '#title' => $instrument_family_options[$selected_family] . ' ' . $this->t('Instruments'),
      // When the form is rebuilt during Ajax processing, the $selected_family
      // variable will contain the current value of the instrument family field
      // and so the options will change here to reflect that.
      '#options' => static::getSecondDropdownOptions($selected_family),
      '#default_value' => !empty($form_state->getValue('instrument_dropdown')) ? $form_state->getValue('instrument_dropdown') : '',
    ];

    // This submit button triggers a normal (non Ajax) submission of the form.
    $form['instrument_fieldset_container']['instrument_fieldset']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    // We might normally use #states to disable the instrument fields based on
    // the instrument family fields. But since the premise is that we don't have
    // JavaScript running, #states won't work either. We have to set up the
    // state of the instrument type fieldset here, based on the selected instrument
    // family.
    //
    // If there is no option selected for instrument family, disable the
    // instrument type fields.
    if ($selected_family == 'none') {
      // Change the field title to provide user with some feedback on why the
      // field is disabled.
      $form['instrument_fieldset_container']['instrument_fieldset']['instrument_dropdown']['#title'] = $this->t('You must choose an instrument family first.');
      $form['instrument_fieldset_container']['instrument_fieldset']['instrument_dropdown']['#disabled'] = TRUE;
      $form['instrument_fieldset_container']['instrument_fieldset']['submit']['#disabled'] = TRUE;
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Figure out what element triggered the form submission. If it was the
    // main "Submit" button, process the form as per usual. If it's anything else
    // like the #ajax on the select field, set the rebuild flag so that the form
    // is rebuilt before executing the Ajax callback.
    $trigger = (string) $form_state->getTriggeringElement()['#value'];
    if ($trigger == 'Submit') {
      // Process submitted form data.
      $this->messenger->addStatus($this->t('Your values have been submitted. Instrument family: @family, Instrument: @instrument', [
        '@family' => $form_state->getValue('instrument_family_dropdown'),
        '@instrument' => $form_state->getValue('instrument_dropdown'),
      ]));
    }
    else {
      // Rebuild the form. This causes buildForm() to be called again before the
      // associated Ajax callback. Allowing the logic in buildForm() to execute
      // and update the $form array so that it reflects the current state of
      // the instrument family select list.
      $form_state->setRebuild();
    }
  }

  /**
   * Ajax callback for instrument family select field.
   *
   * This callback will occur *after* the form has been rebuilt by buildForm().
   * Since that's the case, the $form array should contain the right values for
   * the instrument type field that reflect the current value of the instrument
   * family field.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The portion of the render structure that will replace the
   *   instrument-dropdown-replace form element.
   */
  public function instrumentDropdownCallback(array $form, FormStateInterface $form_state) {
    return $form['instrument_fieldset_container'];
  }

  /**
   * Helper function to populate the first dropdown.
   *
   * This would normally be pulling data from the database.
   *
   * @return array
   *   Dropdown options.
   */
  public static function getFirstDropdownOptions() {
    return [
      'none' => 'none',
      'String' => 'String',
      'Woodwind' => 'Woodwind',
      'Brass' => 'Brass',
      'Percussion' => 'Percussion',
    ];
  }

  /**
   * Helper function to populate the second dropdown.
   *
   * This would normally be pulling data from the database.
   *
   * @param string $key
   *   This will determine which set of options is returned.
   *
   * @return array
   *   Dropdown options
   */
  public static function getSecondDropdownOptions($key = '') {
    switch ($key) {
      case 'String':
        $options = [
          'Violin' => 'Violin',
          'Viola' => 'Viola',
          'Cello' => 'Cello',
          'Double Bass' => 'Double Bass',
        ];
        break;

      case 'Woodwind':
        $options = [
          'Flute' => 'Flute',
          'Clarinet' => 'Clarinet',
          'Oboe' => 'Oboe',
          'Bassoon' => 'Bassoon',
        ];
        break;

      case 'Brass':
        $options = [
          'Trumpet' => 'Trumpet',
          'Trombone' => 'Trombone',
          'French Horn' => 'French Horn',
          'Euphonium' => 'Euphonium',
        ];
        break;

      case 'Percussion':
        $options = [
          'Bass Drum' => 'Bass Drum',
          'Timpani' => 'Timpani',
          'Snare Drum' => 'Snare Drum',
          'Tambourine' => 'Tambourine',
        ];
        break;

      default:
        $options = ['none' => 'none'];
        break;
    }
    return $options;
  }

}