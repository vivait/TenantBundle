Feature: Untenanted commands
  As a Developer
  I want to be able to run a single command against a default environment when tenanting is disabled

  Scenario: Ignore to the fallback environment when tenanting enabled
    Given I have a tenant "tenant1"
    When I run the tenanted command "cache:clear" with options "-e test"
    Then I should not see "Clearing the cache for the dev environment" in the command output
    And I should see "Clearing the cache for the tenant_tenant1 environment" in the command output

  Scenario: Default to the fallback environment when tenanting disabled
    Given I have a tenant "tenant1"
    When I run the tenanted command "cache:clear" with options "-e dev"
    Then I should see "Clearing the cache for the dev environment" in the command output
    And I should not see "Clearing the cache for the tenant_tenant1 environment" in the command output

  Scenario: Detect the fallback environment from a long option in the command
    Given I have a tenant "tenant1"
    When I run the tenanted command "cache:clear --env=dev"
    Then I should see "Clearing the cache for the dev environment" in the command output
    And I should not see "Clearing the cache for the tenant_tenant1 environment" in the command output

  Scenario: Detect the fallback environment from a short option in the command
    Given I have a tenant "tenant1"
    When I run the tenanted command "cache:clear -e dev"
    Then I should see "Clearing the cache for the dev environment" in the command output
    And I should not see "Clearing the cache for the tenant_tenant1 environment" in the command output

  Scenario: I can cancel endless commands when tenanting disabled
    Given I have a tenant "tenant1"
    When I run the tenanted command "vivait:tenants:wait 10" in the background with options "-P 3 -e dev"
    Then I should be able to cancel the command
