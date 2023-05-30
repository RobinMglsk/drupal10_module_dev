<?php

/**
 * @file
 * Hello world salutation block plugin
 */

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\hello_world\IHelloWorldSalutation;

/**
 * Hello World salutation block
 * 
 * @Block(
 *   id = "hello_world_salutation_block",
 *   admin_label = @Translation("Hello world salutation"),
 * )
 */
class HelloWorldSalutationBlock extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * The salutation service
     *
     * @var Drupal\hello_world\IHelloWorldSalutation
     */
    protected IHelloWorldSalutation $salutation;

    /**
     * Constructs a new instance of the class.
     *
     * @param array $configuration The configuration for the class.
     * @param mixed $plugin_id The plugin ID.
     * @param mixed $plugin_definition The plugin definition.
     * @param IHelloWorldSalutation $salutation The salutation object.
     * @return void
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, IHelloWorldSalutation $salutation) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->salutation = $salutation;
    }

    /**
     * {@inheritDoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('hello_world.salutation')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function build() {
        $config =  $this->getConfiguration();

        return [
            '#markup' => $this->salutation->getSalutation() . $config['enabled'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function defaultConfiguration() {
        return [
            'enabled' => 1,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function blockForm($form, FormStateInterface $form_state) {
        $config = $this->getConfiguration();

        $form['enabled'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Enabled'),
            '#default_value' => $config['enabled'],
            '#description' => $this->t('Enable or disable the salutation block.'),
        ];

        return $form;
    }

    public function blockSubmit($form, FormStateInterface $form_state) {
        $this->configuration['enabled'] = $form_state->getValue('enabled');
    }
}