<?php

declare(strict_types=1);

namespace Drupal\server_general\Plugin\EntityViewBuilder;

use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\server_general\EntityDateTrait;
use Drupal\server_general\EntityViewBuilder\NodeViewBuilderAbstract;
use Drupal\server_general\ThemeTrait\ElementLayoutThemeTrait;
use Drupal\server_general\ThemeTrait\ElementNodeNewsThemeTrait;
use Drupal\server_general\ThemeTrait\GreetingThemeTrait;
use Drupal\server_general\ThemeTrait\LinkThemeTrait;
use Drupal\server_general\ThemeTrait\SearchThemeTrait;
use Drupal\server_general\ThemeTrait\TitleAndLabelsThemeTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The "Group" plugin.
 *
 * @EntityViewBuilder(
 *   id = "node.group",
 *   label = @Translation("Node - Group"),
 *   description = "Node view builder for Group bundle."
 * )
 */
final class NodeGroup extends NodeViewBuilderAbstract {

  use ElementLayoutThemeTrait;
  use ElementNodeNewsThemeTrait;
  use EntityDateTrait;
  use SearchThemeTrait;
  use TitleAndLabelsThemeTrait;
  use LinkThemeTrait;
  use GreetingThemeTrait;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Membership manager.
   *
   * @var \Drupal\og\MembershipManagerInterface
   */
  protected $membershipManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $plugin->renderer = $container->get('renderer');
    $plugin->entityTypeManager = $container->get('entity_type.manager');
    $plugin->currentUser = $container->get('current_user');
    $plugin->membershipManager = $container->get('og.membership_manager');

    return $plugin;
  }

  /**
   * Build full view mode.
   *
   * @param array $build
   *   The existing build.
   * @param \Drupal\node\NodeInterface $node
   *   The Node.
   *
   * @return array
   *   Render array.
   */
  public function buildFull(array $build, NodeInterface $node): array {
    $user = $this->currentUser->getAccount();
    $user_entity = $this->entityTypeManager->getStorage('user')->load($user->id());

    // Show greeting message to authenticated users who are not members.
    if ($user->isAuthenticated() && !$this->membershipManager->isMember($node, $user->id())) {
      $url = Url::fromRoute('og.subscribe', [
        'entity_type_id' => $node->getEntityTypeId(),
        'group' => $node->id(),
        'og_membership_type' => 'default',
      ]);

      $greeting_element = $this->buildGreeting($user_entity, $url, $node->label());
      $build[] = $this->wrapContainerWide($greeting_element);
    }

    $header_element = $this->buildHeader(
      $node->label(),
      $node->type->entity->label(),
      $this->getFieldOrCreatedTimestamp($node, 'field_publish_date'),
    );

    $build[] = $this->wrapContainerWide($header_element);
    $body_element = $this->buildProcessedText($node, 'body');
    $build[] = $this->wrapContainerWide($body_element);

    return $build;
  }

}
