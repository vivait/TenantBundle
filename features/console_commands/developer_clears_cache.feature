Feature: Developer clears cache
  As a Developer
  I want to be able to clear the cache for a specific tenant
  In order to recompile the environment

  Scenario: Cache clear command is ran
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "cache:clear"
    Then I should see "Clearing the cache for the tenant_tenant1 environment" in the command output
    And I should see "Clearing the cache for the tenant_tenant2 environment" in the command output
    And I should see "Clearing the cache for the tenant_tenant3 environment" in the command output

  Scenario: Long running command is ran in parallel
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "vivait:tenants:wait" with options "-P 3"
    Then I should see "111222" in the command output

  Scenario: Cache clear is ran for specified tenants
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "cache:clear" with options "-t tenant1,tenant2"
    Then I should see "Clearing the cache for the tenant_tenant1 environment" in the command output
    And I should see "Clearing the cache for the tenant_tenant2 environment" in the command output
    And I should not see "Clearing the cache for the tenant_tenant3 environment" in the command output
