<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 2/4/20
 * Time: 5:03 PM
 */

namespace Drupal\spotify_service\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/*
    Class: SpotifyAdminForm

    admin all spotify functions
*/

class SpotifyAdminForm extends ConfigFormBase {

    /**
     * Returns a unique string identifying the form.
     *
     * @return string The unique string identifying the form.
     */
    public function getFormId() {
        return 'spotify_service_admin_form';
    }

    /**
     * Returns a unique string identifying the form.
     *
     * @return array config Names
     */
    protected function getEditableConfigNames() {
        return [
            'spotify_service.settings',
        ];
    }

    /**
     * Form constructor.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     *
     * @return array
     *   The form structure.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('spotify_service.settings');

        $form['content'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Config Spotify Service'),
        ];

        $form['content']['client_id'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Client ID Spotify Service'),
            '#default_value' => $config->get('client_id'),
        ];
        $form['content']['client_secret'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Client Secret Spotify Service'),
            '#default_value' => $config->get('client_secret'),
        ];

        $form['content']['end_point'] = [
            '#type' => 'textfield',
            '#title' => $this->t('End point TOKEN Spotify Service'),
            '#default_value' => $config->get('end_point'),
        ];

        $form['content']['end_point'] = [
            '#type' => 'textfield',
            '#title' => $this->t('End point TOKEN Spotify Service'),
            '#default_value' => $config->get('end_point'),
        ];

        $form['content']['end_point_api'] = [
            '#type' => 'textfield',
            '#title' => $this->t('End point API to get Spotify Services'),
            '#default_value' => $config->get('end_point_api'),
        ];


        return parent::buildForm($form, $form_state);
    }


    /**
     *
     * @param array $form
     * @param FormStateInterface $form_state
     *
     *  submit form and add config
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        \Drupal::configFactory()->getEditable('spotify_service.settings')
            ->set('client_id', $form_state->getValue('client_id'))
            ->set('client_secret', $form_state->getValue('client_secret'))
            ->set('end_point', $form_state->getValue('end_point'))
            ->set('end_point_api', $form_state->getValue('end_point_api'))
            ->save();
        parent::submitForm($form, $form_state);
    }
}
