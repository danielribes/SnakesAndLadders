Feature: US 3 - Moves Are Determined By Dice Rolls
  As a player
  I want to move my token based on the roll of a die
  So that there is an element of chance in the game

  Scenario: UAT1 Dice result should be between 1-6 inclusive
    Given the game is started
    When the player rolls a die
    Then the result should be between 1-6 inclusive

  Scenario: UAT2 Player rolls a 4
    Given the player rolls a 4
    When they move their token
    Then the token should move 4 spaces