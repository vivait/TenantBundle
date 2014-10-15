TenantBundle
============

Allows separate Symfony environments per tenant

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require vivait/tenant-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Vivait\TenantBundle\VivaitTenantBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Altering your kernel to be tenanted
-------------------------

Because a kernel environment is immutable, we need to decipher the tenant before
booting the kernel. We have created a tenanted kernel for your AppKernel to extend,
which acts as a middleware to Symfony's Kernel. When using this middleware, you
will need to implement a couple of methods.

The first abstract method, ```getAllTenants``` is used to return an array of
Tenant objects. A number of providers are provided, although this could also
implement custom logic, if needed.

The second abstract method, ```getCurrentTenantKey``` is used to locate the current
tenant, so the kernel can alter the environment based on this. A number of locators
are provided, although this could also implement custom logic, if needed.

Below is an example of the implementation of both of these, and if you're unsure
what any of this means, is an advised starting point:

```php
<?php
// app/AppKernel.php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Vivait\TenantBundle\Kernel\TenantKernel;
use Vivait\TenantBundle\Locator\HostnameLocator;
use Vivait\TenantBundle\Provider\ConfigProvider;
use Vivait\TenantBundle\Provider\TenantProvider;

// ...
class AppKernel extends TenantKernel
{
	// ...

    /**
     * Provides an array of all Tenants
     *
     * @return Tenant[]
     */
    protected function getAllTenants()
    {
       $configProvider = new ConfigProvider( __DIR__ . '/config/' );
       return $configProvider->loadTenants();
    }

    /**
     * Provides the current tenant's key
     * @param Request $request
     * @return string The current tenant's key
     */
    protected function getCurrentTenantKey( Request $request )
    {
        return HostnameLocator::getTenantFromRequest( $request );
    }
}
```

Step 4: Creating environments for each tenant
-------------------------
An environment will be automatically created for each tenant by Symfony. However,
if you choose to use the default ```AppKernel::registerContainerConfiguration```
method, as provided by Symfony, then you will need to create a new config file for
each tenant environment. Each tenant environment is prefixed with 'tenant_', so if
you have a tenant called 'mytenant', then you'd need to create
'app/config/config_tenant_mytenant.yml'. You can configure this behaviour by
changing your ```AppKernel::registerContainerConfiguration``` method, but you will
also need to customise the patterns used in ConfigProvider to match the change in
config structure.

You could also use an alternative provider to provide a list of
tenants, for example there is a YamlProvider that retrieves a list of tenants from
a YAML file.
