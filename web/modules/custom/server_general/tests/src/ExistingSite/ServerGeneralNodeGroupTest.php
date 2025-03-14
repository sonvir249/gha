<?php

namespace Drupal\Tests\server_general\ExistingSite;

use Symfony\Component\HttpFoundation\Response;

/**
 * A model test case using traits from Drupal Test Traits.
 */
class ServerGeneralNodeGroupTest extends ServerGeneralNodeTestBase {

  /**
   * {@inheritdoc}
   */
  public function getEntityBundle(): string {
    return 'group';
  }

  /**
   * {@inheritdoc}
   */
  public function getRequiredFields(): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFields(): array {
    return [];
  }

  /**
   * An example test method; note that Drupal API's and Mink are available.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testGroup() {
    // Creates a user. Will be automatically cleaned up at the end of the test.
    $author = $this->createUser();

    // Create a "Group", Will be automatically cleaned up at end oftest.
    $node = $this->createNode([
      'title' => 'Avengers',
      'type' => 'group',
      'uid' => $author->id(),
      'moderation_state' => 'published',
    ]);
    $this->assertEquals($author->id(), $node->getOwnerId());

    // We can login and browse admin pages.
    $this->drupalLogin($author);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->drupalGet("group/node/{$node->id()}/subscribe");
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);

    // Check group node for anonymous.
    $this->drupalLogout();
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->assertSession()->pageTextNotContains('if you would like to subscribe to this group called');
  }

}
