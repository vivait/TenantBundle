Feature: Parallel commands
  As a Developer
  I want to be able to run a command in parallel

  Scenario: Long running command is ran in parallel
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "vivait:tenants:wait" with options "-P 3"
    Then I should see "111222" in the command output

  Scenario: I can cancel endless commands
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "vivait:tenants:wait 10" in the background with options "-P 3"
    Then I should be able to cancel the command

  Scenario: All processes exit when a child crashes
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "vivait:tenants:crash tenant1 5" with options "-P 3"
    Then I should not see "3" in the command output
    And all process should have exited

  Scenario: Number of processes can be specified as 0
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "vivait:tenants:wait" with options "-P 0"
    Then I should see "111222" in the command output
