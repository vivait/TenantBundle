Feature: Guest accesses website
  As a gust
  I want to see a specific tenant's website
  In order to perform actions specific to a tenant

  Scenario: A tenant's subdomain is visited
    Given I have a tenant "tenant1"
     When I make a request to "http://tenant1.example.org"
     Then I should see "Hello Tenant 1"