<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Helpers to build an person card.
 */
trait PersonCardThemeTrait {

  /**
   * Build an person card item.
   *
   * @param string $profile_image
   *   The profile image.
   * @param string $name
   *   The name.
   * @param string $role
   *   The role.
   * @param string $badge_label
   *   The badge label.
   * @param string $email
   *   The email.
   * @param string $phone
   *   The phone.
   *
   * @return array
   *   The render array.
   */
  protected function buildElementPersonCardItem(string $profile_image = '', string $name = '', string $role = '', string $badge_label = '', string $email = '', string $phone = ''): array {
    return [
      '#theme' => 'server_theme_element__person_card_item',
      '#profile_image' => $profile_image,
      '#name' => $name,
      '#role' => $role,
      '#badge_label' => $badge_label,
      '#email' => $email,
      '#phone' => $phone,
    ];
  }

  /**
   * Build an Person cards.
   *
   * @param array $cards
   *   Items rendered with `PersonCardThemeTrait::buildElementPersonCardItem`.
   *
   * @return array
   *   The render array.
   */
  protected function buildElementPersonCards(array $cards): array {
    // Person cards.
    return [
      '#theme' => 'server_theme_element__person_cards',
      '#items' => $cards,
    ];
  }

}
