Feature: Specific tenants commands
  As a Developer
  I want to be able to run a command against specified tenants

  Scenario: Cache clear is ran for specified tenants
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "cache:clear" with options "-t tenant1,tenant2"
    Then I should see "Clearing the cache for the tenant_tenant1 environment" in the command output
    And I should see "Clearing the cache for the tenant_tenant2 environment" in the command output
    And I should not see "Clearing the cache for the tenant_tenant3 environment" in the command output
