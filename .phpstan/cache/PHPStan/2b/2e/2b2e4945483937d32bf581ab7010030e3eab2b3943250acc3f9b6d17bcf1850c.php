<?php declare(strict_types = 1);

// osfsl-/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/HandlesRoutes.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Orchestra\Testbench\Concerns\HandlesRoutes
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-827248bb2596dd80a52f5949b280d40c505a314b1b02fb2f0557980121cb54b3-8.4.19-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'filename' => '/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/HandlesRoutes.php',
      ),
    ),
    'namespace' => 'Orchestra\\Testbench\\Concerns',
    'name' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
    'shortName' => 'HandlesRoutes',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 200,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Orchestra\\Testbench\\Concerns\\InteractsWithPHPUnit',
      1 => 'Orchestra\\Testbench\\Concerns\\InteractsWithTestCase',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'requireApplicationCachedRoutesHasRun' => 
      array (
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'name' => 'requireApplicationCachedRoutesHasRun',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 103,
            'startFilePos' => 871,
            'endTokenPos' => 103,
            'endFilePos' => 875,
          ),
        ),
        'docComment' => '/**
 * Indicates if we have made it through the requireApplicationCachedRoutes function.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 65,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'setUpApplicationRoutes' => 
      array (
        'name' => 'setUpApplicationRoutes',
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
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 47,
            'endColumn' => 50,
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
 * Setup routes requirements.
 *
 * @internal
 *
 * @param  \\Illuminate\\Foundation\\Application  $app
 */',
        'startLine' => 38,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'aliasName' => NULL,
      ),
      'defineRoutes' => 
      array (
        'name' => 'defineRoutes',
        'parameters' => 
        array (
          'router' => 
          array (
            'name' => 'router',
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
            'startColumn' => 37,
            'endColumn' => 43,
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
 * Define routes setup.
 *
 * @api
 *
 * @param  \\Illuminate\\Routing\\Router  $router
 * @return void
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
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'aliasName' => NULL,
      ),
      'defineWebRoutes' => 
      array (
        'name' => 'defineWebRoutes',
        'parameters' => 
        array (
          'router' => 
          array (
            'name' => 'router',
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
            'startColumn' => 40,
            'endColumn' => 46,
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
 * Define web routes setup.
 *
 * @api
 *
 * @param  \\Illuminate\\Routing\\Router  $router
 * @return void
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
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'aliasName' => NULL,
      ),
      'defineStashRoutes' => 
      array (
        'name' => 'defineStashRoutes',
        'parameters' => 
        array (
          'route' => 
          array (
            'name' => 'route',
            'default' => NULL,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 101,
            'endLine' => 101,
            'startColumn' => 42,
            'endColumn' => 62,
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
 * Define stash routes setup.
 *
 * @api
 *
 * @param  \\Closure|string  $route
 * @return void
 */',
        'startLine' => 101,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'aliasName' => NULL,
      ),
      'defineCacheRoutes' => 
      array (
        'name' => 'defineCacheRoutes',
        'parameters' => 
        array (
          'route' => 
          array (
            'name' => 'route',
            'default' => NULL,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 115,
            'endLine' => 115,
            'startColumn' => 42,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cached' => 
          array (
            'name' => 'cached',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 115,
                'endLine' => 115,
                'startTokenPos' => 395,
                'startFilePos' => 2969,
                'endTokenPos' => 395,
                'endFilePos' => 2972,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 115,
            'endLine' => 115,
            'startColumn' => 65,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => true,
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
 * Define cache routes setup.
 *
 * @api
 *
 * @param  \\Closure|string  $route
 * @param  bool  $cached
 * @return void
 */',
        'startLine' => 115,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'aliasName' => NULL,
      ),
      'requireApplicationCachedRoutes' => 
      array (
        'name' => 'requireApplicationCachedRoutes',
        'parameters' => 
        array (
          'files' => 
          array (
            'name' => 'files',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Filesystem\\Filesystem',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 55,
            'endColumn' => 71,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cached' => 
          array (
            'name' => 'cached',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 74,
            'endColumn' => 85,
            'parameterIndex' => 1,
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
 * Require application cached routes.
 *
 * @internal
 *
 * @param  \\Illuminate\\Filesystem\\Filesystem  $files
 * @return void
 */',
        'startLine' => 169,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesRoutes',
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