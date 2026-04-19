<?php declare(strict_types = 1);

// osfsl-/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/WithLaravelBootstrapFile.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Orchestra\Testbench\Concerns\WithLaravelBootstrapFile
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-617fe90b117036b3536acfcff35048633b55f3c555d4008e8cdc533e8e63a9c7-8.4.19-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'filename' => '/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/WithLaravelBootstrapFile.php',
      ),
    ),
    'namespace' => 'Orchestra\\Testbench\\Concerns',
    'name' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
    'shortName' => 'WithLaravelBootstrapFile',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 69,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Orchestra\\Testbench\\Concerns\\InteractsWithTestCase',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'getApplicationBootstrapFile' => 
      array (
        'name' => 'getApplicationBootstrapFile',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 52,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get application bootstrap file path (if exists).
 *
 * @internal
 *
 * @param  string  $filename
 * @return string|false
 */',
        'startLine' => 23,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'aliasName' => NULL,
      ),
      'hasCustomApplicationKernels' => 
      array (
        'name' => 'hasCustomApplicationKernels',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if application is using a custom application kernels.
 *
 * @internal
 *
 * @return bool
 */',
        'startLine' => 45,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'aliasName' => NULL,
      ),
      'usesTestbenchDefaultSkeleton' => 
      array (
        'name' => 'usesTestbenchDefaultSkeleton',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if application is bootstrapped using Testbench\'s default skeleton.
 *
 * @return bool
 */',
        'startLine' => 56,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
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
 * Get the application\'s base path.
 *
 * @api
 *
 * @return string
 */',
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 66,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\WithLaravelBootstrapFile',
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