<?php

namespace Drupal\drupal8_forms\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\drupal8_forms\DbtngExampleService;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Form to add a database entry, with all the interesting fields.
 *
 * @ingroup drupal8_forms
 */
class DbExampleAddForm extends FormBase {

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * Our database repository service.
   *
   * @var \Drupal\drupal8_forms\DbtngExampleService
   */
  protected $customdatabase;

   /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dbtng_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $form['message'] = [
      '#markup' => $this->t('Add an entry to the dbtng_example table.'),
    ];

    $form['add'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Add a person entry'),
    ];
    $form['add']['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#size' => 15,
    ];
    $form['add']['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#size' => 15,
    ];
    $form['add']['kids'] = [
     '#type' => 'checkbox',
      '#title' => $this->t('Do you have kids?'),
    ];
    $form['add']['kid_number'] = [
     '#type' => 'textfield',
     '#title' => $this->t('How many kids do you have?'),
     '#states' => [
       'visible' => [
         'input[name="kids"]' => ['checked' => TRUE],
    ], ],
    ];

    $form['add']['qualification'] = [
      '#type' => 'select',
      '#title' => $this->t('Qualification'),
      '#options' => [
        'ug' => $this->t('U.G.'),
        'pg' => $this->t('P.G.'),
        'other' => $this->t('Other'),
      ],
    ];

    $form['add']['please_specify'] = [
     '#type' => 'textfield',
     '#title' => $this->t('If others, please specify'),
     '#states' => array(
            'visible' => array(
                ':input[name=qualification]' => array('value' => t('other')),
      ),
      ),
    ];

    $form['add']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Gather the current user so the new record has ownership.
    $this->repository = \Drupal::service('drupal8_forms.drupal8_formsservice');
    // Save the submitted entry.
    $entry = [
      'first_name' => $form_state->getValue('first_name'),
      'last_name' => $form_state->getValue('last_name'),
    ];
    $return = $this->repository->insert($entry);
    if ($return) {
      $this->messenger()->addMessage($this->t('Created entry @entry', ['@entry' => print_r($entry, TRUE)]));
    }
  }


}
