Feature: Tenanted commands
  As a Developer
  I want to be able to run a single command against all tenants

  Scenario: Cache clear command is ran
    Given I have a tenant "tenant1"
    And I have a tenant "tenant2"
    And I have a tenant "tenant3"
    When I run the tenanted command "cache:clear"
    Then I should see "Clearing the cache for the tenant_tenant1 environment" in the command output
    And I should see "Clearing the cache for the tenant_tenant2 environment" in the command output
    And I should see "Clearing the cache for the tenant_tenant3 environment" in the command output
