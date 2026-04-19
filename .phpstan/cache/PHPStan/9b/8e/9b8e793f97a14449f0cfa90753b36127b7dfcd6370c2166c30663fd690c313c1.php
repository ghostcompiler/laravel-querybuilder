<?php declare(strict_types = 1);

// osfsl-/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/CreatesApplication.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Orchestra\Testbench\Concerns\CreatesApplication
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b35050a8fe122b2cc23acc17cede3fb32637226217921729b4486fded6c2fbe5-8.4.19-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'filename' => '/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/CreatesApplication.php',
      ),
    ),
    'namespace' => 'Orchestra\\Testbench\\Concerns',
    'name' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
    'shortName' => 'CreatesApplication',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property bool|null $enablesPackageDiscoveries
 * @property bool|null $loadEnvironmentVariables
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 38,
    'endLine' => 651,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Orchestra\\Testbench\\Concerns\\InteractsWithWorkbench',
      1 => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'applicationBasePath' => 
      array (
        'name' => 'applicationBasePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application\'s base path.
 *
 * @api
 *
 * @return string
 */',
        'startLine' => 50,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'ignorePackageDiscoveriesFrom' => 
      array (
        'name' => 'ignorePackageDiscoveriesFrom',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ignore package discovery from.
 *
 * @api
 *
 * @return array<int, string>
 */',
        'startLine' => 62,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'getApplicationTimezone' => 
      array (
        'name' => 'getApplicationTimezone',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 75,
            'endLine' => 75,
            'startColumn' => 47,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application timezone.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return string|null
 */',
        'startLine' => 75,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'overrideApplicationBindings' => 
      array (
        'name' => 'overrideApplicationBindings',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 52,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Override application bindings.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<string|class-string, string|class-string>
 */',
        'startLine' => 88,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationBindings' => 
      array (
        'name' => 'resolveApplicationBindings',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 101,
            'endLine' => 101,
            'startColumn' => 57,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application bindings.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 101,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'getApplicationAliases' => 
      array (
        'name' => 'getApplicationAliases',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 116,
            'endLine' => 116,
            'startColumn' => 46,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get application aliases.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<string, class-string>
 */',
        'startLine' => 116,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'overrideApplicationAliases' => 
      array (
        'name' => 'overrideApplicationAliases',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 51,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Override application aliases.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<string, class-string|false>
 */',
        'startLine' => 129,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationAliases' => 
      array (
        'name' => 'resolveApplicationAliases',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 142,
            'endLine' => 142,
            'startColumn' => 56,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application aliases.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<string, class-string>
 */',
        'startLine' => 142,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'getPackageAliases' => 
      array (
        'name' => 'getPackageAliases',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 168,
            'endLine' => 168,
            'startColumn' => 42,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get package aliases.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<string, class-string>
 */',
        'startLine' => 168,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'getPackageBootstrappers' => 
      array (
        'name' => 'getPackageBootstrappers',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 181,
            'endLine' => 181,
            'startColumn' => 48,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get package bootstrapper.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<int, class-string>
 */',
        'startLine' => 181,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'getApplicationProviders' => 
      array (
        'name' => 'getApplicationProviders',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 48,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get application providers.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<int, class-string>
 */',
        'startLine' => 194,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'overrideApplicationProviders' => 
      array (
        'name' => 'overrideApplicationProviders',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 207,
            'endLine' => 207,
            'startColumn' => 53,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Override application aliases.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<class-string, class-string|false>
 */',
        'startLine' => 207,
        'endLine' => 210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationProviders' => 
      array (
        'name' => 'resolveApplicationProviders',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 220,
            'endLine' => 220,
            'startColumn' => 58,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application aliases.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<int, class-string>
 */',
        'startLine' => 220,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'getPackageProviders' => 
      array (
        'name' => 'getPackageProviders',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 247,
            'endLine' => 247,
            'startColumn' => 44,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get package providers.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return array<int, class-string>
 */',
        'startLine' => 247,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'getApplicationBasePath' => 
      array (
        'name' => 'getApplicationBasePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve the application\'s base path.
 *
 * @internal
 *
 * @return string
 */',
        'startLine' => 259,
        'endLine' => 262,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'createApplication' => 
      array (
        'name' => 'createApplication',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates the application.
 *
 * @internal
 *
 * @return \\Illuminate\\Foundation\\Application
 */',
        'startLine' => 271,
        'endLine' => 291,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveDefaultApplication' => 
      array (
        'name' => 'resolveDefaultApplication',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create the default application implementation.
 *
 * @internal
 *
 * @return \\Illuminate\\Foundation\\Application
 */',
        'startLine' => 300,
        'endLine' => 309,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplication' => 
      array (
        'name' => 'resolveApplication',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application implementation.
 *
 * @api
 *
 * @return \\Illuminate\\Foundation\\Application
 */',
        'startLine' => 318,
        'endLine' => 329,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationResolvingCallback' => 
      array (
        'name' => 'resolveApplicationResolvingCallback',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 337,
            'endLine' => 337,
            'startColumn' => 60,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application resolving callback.
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 337,
        'endLine' => 347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationFacades' => 
      array (
        'name' => 'resolveApplicationFacades',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 355,
            'endLine' => 355,
            'startColumn' => 50,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application facades implementation.
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 355,
        'endLine' => 359,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationEnvironmentVariables' => 
      array (
        'name' => 'resolveApplicationEnvironmentVariables',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 369,
            'endLine' => 369,
            'startColumn' => 63,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application core environment variables implementation.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 369,
        'endLine' => 393,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationConfiguration' => 
      array (
        'name' => 'resolveApplicationConfiguration',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 403,
            'endLine' => 403,
            'startColumn' => 56,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application core configuration implementation.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 403,
        'endLine' => 440,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationCore' => 
      array (
        'name' => 'resolveApplicationCore',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 450,
            'endLine' => 450,
            'startColumn' => 47,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application core implementation.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 450,
        'endLine' => 455,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationConsoleKernel' => 
      array (
        'name' => 'resolveApplicationConsoleKernel',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 465,
            'endLine' => 465,
            'startColumn' => 56,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application Console Kernel implementation.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 465,
        'endLine' => 468,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationHttpKernel' => 
      array (
        'name' => 'resolveApplicationHttpKernel',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 478,
            'endLine' => 478,
            'startColumn' => 53,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application HTTP Kernel implementation.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 478,
        'endLine' => 481,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationHttpMiddlewares' => 
      array (
        'name' => 'resolveApplicationHttpMiddlewares',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 58,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application HTTP default middlewares.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 491,
        'endLine' => 501,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationExceptionHandler' => 
      array (
        'name' => 'resolveApplicationExceptionHandler',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 511,
            'endLine' => 511,
            'startColumn' => 59,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application HTTP exception handler.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 511,
        'endLine' => 514,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationBootstrappers' => 
      array (
        'name' => 'resolveApplicationBootstrappers',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 524,
            'endLine' => 524,
            'startColumn' => 56,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application bootstrapper.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 524,
        'endLine' => 572,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'refreshApplicationRouteNameLookups' => 
      array (
        'name' => 'refreshApplicationRouteNameLookups',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 582,
            'endLine' => 582,
            'startColumn' => 65,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Refresh route name lookup for the application.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 582,
        'endLine' => 592,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resolveApplicationRateLimiting' => 
      array (
        'name' => 'resolveApplicationRateLimiting',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 602,
            'endLine' => 602,
            'startColumn' => 55,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve application rate limiting configuration.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 602,
        'endLine' => 609,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'resetApplicationArtisanCommands' => 
      array (
        'name' => 'resetApplicationArtisanCommands',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 619,
            'endLine' => 619,
            'startColumn' => 62,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Reset artisan commands for the application.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 619,
        'endLine' => 622,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'defineEnvironment' => 
      array (
        'name' => 'defineEnvironment',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 632,
            'endLine' => 632,
            'startColumn' => 42,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define environment setup.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 632,
        'endLine' => 635,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
      'getEnvironmentSetUp' => 
      array (
        'name' => 'getEnvironmentSetUp',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 647,
            'endLine' => 647,
            'startColumn' => 44,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define environment setup.
 *
 * @api
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 * @return void
 *
 * @deprecated 10.0 Use "defineEnvironment()" instead.
 */',
        'startLine' => 647,
        'endLine' => 650,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\CreatesApplication',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));