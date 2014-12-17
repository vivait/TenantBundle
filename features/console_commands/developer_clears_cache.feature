Feature: Developer clears cache
  As a Developer
  I want to be able to clear the cache for a specific tenant
  In order to recompile the environment

  Scenario: Cache clear command is ran
    Given I have a tenant "tenant1"
      And I have a tenant "tenant2"
     When I run the command "cache:clear --tenant"
    Then I should see "Clearing the cache for the tenant_tenant1 environment" in the command output
     And I should see "Clearing the cache for the tenant_tenant2 environment" in the command output
