<?php declare(strict_types = 1);

// osfsl-/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/HandlesDatabases.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Orchestra\Testbench\Concerns\HandlesDatabases
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a314b96d7fc50449b46e61813abcc0c7789ff64375a097540a3eebec43dfc3c5-8.4.19-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'filename' => '/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/HandlesDatabases.php',
      ),
    ),
    'namespace' => 'Orchestra\\Testbench\\Concerns',
    'name' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
    'shortName' => 'HandlesDatabases',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @internal
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 149,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'setUpDatabaseRequirements' => 
      array (
        'name' => 'setUpDatabaseRequirements',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 50,
            'endColumn' => 66,
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
 * Setup database requirements.
 *
 * @internal
 *
 * @param  \\Closure():void  $callback
 */',
        'startLine' => 26,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'aliasName' => NULL,
      ),
      'usesSqliteInMemoryDatabaseConnection' => 
      array (
        'name' => 'usesSqliteInMemoryDatabaseConnection',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 80,
                'endLine' => 80,
                'startTokenPos' => 419,
                'startFilePos' => 2563,
                'endTokenPos' => 419,
                'endFilePos' => 2566,
              ),
            ),
            'type' => 
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
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 61,
            'endColumn' => 86,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
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
 * Determine if using in-memory SQLite database connection
 *
 * @api
 *
 * @param  string|null  $connection
 * @return bool
 */',
        'startLine' => 80,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'aliasName' => NULL,
      ),
      'defineDatabaseMigrations' => 
      array (
        'name' => 'defineDatabaseMigrations',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define database migrations.
 *
 * @api
 *
 * @return void
 */',
        'startLine' => 109,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'aliasName' => NULL,
      ),
      'defineDatabaseMigrationsAfterDatabaseRefreshed' => 
      array (
        'name' => 'defineDatabaseMigrationsAfterDatabaseRefreshed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define database migrations after database refreshed.
 *
 * @api
 *
 * @return void
 */',
        'startLine' => 121,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'aliasName' => NULL,
      ),
      'destroyDatabaseMigrations' => 
      array (
        'name' => 'destroyDatabaseMigrations',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Destroy database migrations.
 *
 * @api
 *
 * @return void
 */',
        'startLine' => 133,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'aliasName' => NULL,
      ),
      'defineDatabaseSeeders' => 
      array (
        'name' => 'defineDatabaseSeeders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define database seeders.
 *
 * @api
 *
 * @return void
 */',
        'startLine' => 145,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesDatabases',
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