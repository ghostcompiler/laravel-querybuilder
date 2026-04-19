<?php declare(strict_types = 1);

// osfsl-/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/HandlesAssertions.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Orchestra\Testbench\Concerns\HandlesAssertions
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8370fc5cc748c6536f5548578aba03677dd5cc80b745c583febf646d0ce7858d-8.4.19-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Orchestra\\Testbench\\Concerns\\HandlesAssertions',
        'filename' => '/Users/ghostcompiler/Desktop/GhostCompiler/laravel-querybuilder/vendor/composer/../orchestra/testbench-core/src/Concerns/HandlesAssertions.php',
      ),
    ),
    'namespace' => 'Orchestra\\Testbench\\Concerns',
    'name' => 'Orchestra\\Testbench\\Concerns\\HandlesAssertions',
    'shortName' => 'HandlesAssertions',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 39,
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
      'markTestSkippedUnless' => 
      array (
        'name' => 'markTestSkippedUnless',
        'parameters' => 
        array (
          'condition' => 
          array (
            'name' => 'condition',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 46,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'message' => 
          array (
            'name' => 'message',
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
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 58,
            'endColumn' => 72,
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
 * Mark the test as skipped when condition is not equivalent to true.
 *
 * @param  (\\Closure(): bool)|bool  $condition
 * @param  string  $message
 * @return void
 *
 * @codeCoverageIgnore
 */',
        'startLine' => 16,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesAssertions',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesAssertions',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesAssertions',
        'aliasName' => NULL,
      ),
      'markTestSkippedWhen' => 
      array (
        'name' => 'markTestSkippedWhen',
        'parameters' => 
        array (
          'condition' => 
          array (
            'name' => 'condition',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 44,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'message' => 
          array (
            'name' => 'message',
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 56,
            'endColumn' => 70,
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
 * Mark the test as skipped when condition is equivalent to true.
 *
 * @param  (\\Closure(): bool)|bool  $condition
 * @param  string  $message
 * @return void
 *
 * @codeCoverageIgnore
 */',
        'startLine' => 32,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Orchestra\\Testbench\\Concerns',
        'declaringClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesAssertions',
        'implementingClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesAssertions',
        'currentClassName' => 'Orchestra\\Testbench\\Concerns\\HandlesAssertions',
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