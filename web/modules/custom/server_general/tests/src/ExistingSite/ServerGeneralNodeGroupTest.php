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

    // Create a "Group", Will be automatically cleaned up at end of the test.
    $node = $this->createNode([
      'title' => 'Avengers',
      'type' => 'group',
      'uid' => $author->id(),
      'moderation_state' => 'published',
    ]);
    $this->assertEquals($author->id(), $node->getOwnerId());
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);

    // Test for authenticated users.
    $authenticated_user = $this->createUser();
    $this->drupalLogin($authenticated_user);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(200);

    $subscribe_url = "/group/node/{$node->id()}/subscribe";
    $greeting_message = "Hi {$authenticated_user->getDisplayName()}, click here if you would like to subscribe to this group called {$node->getTitle()}";
    $this->assertSession()->pageTextContainsOnce($greeting_message);
    $this->drupalGet($subscribe_url);
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);

    // Check group subscribe for anonymous.
    $this->drupalLogout();
    $this->drupalGet($subscribe_url);
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    // Assert the page is redirected to /user/login.
    $this->assertSession()->addressEquals('/user/login');
  }

}
