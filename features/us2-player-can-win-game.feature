Feature: US 2 - Player Can Win the Game
  As a player
  I want to be able to win the game
  So that I can gloat to everyone around

  Scenario: UAT1 Won the game
    Given the token is on square 97
    When the token is moved 3 spaces
    Then the token is on square 100
    And the player has won the game

  Scenario: UAT2 Not won the game
    Given the token is on square 97
    When the token is moved 4 spaces
    Then the token is on square 97
    And the player has not won the game
