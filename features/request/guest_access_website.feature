Feature: Guest accesses website
  As a gust
  I want to see a specific tenant's website
  In order to perform actions specific to a tenant

  Scenario: Tenant 1's subdomain is visited
    Given I have a tenant "tenant1"
      And I have a tenant "tenant2"
     When I make a request to "http://tenant1.example.org"
     Then I should see "Hello Tenant 1"

  Scenario: Tenant 2's subdomain is visited
    Given I have a tenant "tenant1"
      And I have a tenant "tenant2"
     When I make a request to "http://tenant2.example.org"
     Then I should see "Hello Tenant 2"