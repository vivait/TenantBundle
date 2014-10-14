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
$ composer require vivait\tenant-bundle
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

Step 3: Make environment specific configs optional
-------------------------

To avoid needing a config per tenant, change the following in your AppKernel -
this will attempt to load a tenant specific config but fallback on to a global config called config_tenant_default.yml

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
	// ...

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        try {
            $loader->load(__DIR__.'/config/config_'. $this->getEnvironment() .'.yml');
        }
        catch (InvalidArgumentException $e) {
            $loader->load(__DIR__.'/config/config_tenant_default.yml');
        }
    }

}
```

You will also need to create ```app/config/config_tenant_default.yml``` which can be as simple as the following:

```
imports:
    - { resource: config.yml }
```