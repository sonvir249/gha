<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

use Drupal\Core\Url;
use Drupal\user\Entity\User;

/**
 * Helper method for building a greeting message and theming it.
 */
trait GreetingThemeTrait {

  /**
   * Build greeting message.
   *
   * @param \Drupal\user\Entity\User $user
   *   The user.
   * @param \Drupal\Core\Url $url
   *   The url.
   * @param string $label
   *
   * @return array
   *   Render array.
   */
  public function buildGreeting(User $user, Url $url, string $label): array {
    return [
      '#theme' => 'server_theme_greeting',
      '#user' => $user,
      '#url' => $url,
      '#label' => $label,
    ];
  }

}
