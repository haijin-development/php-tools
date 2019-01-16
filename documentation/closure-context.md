# Closure_Context

A class to keep and later evaluate a closure in the context of an object.

## Table of contents

1. [Examples](#c-1)
    1. [Creating Closure_Contexts](#c-1-1)
    2. [Evaluating Closure_Contexts](#c-1-2)

<a name="c-1"></a>
## Examples

[Code examples](./closure-context-examples.php).


<a name="c-1-1"></a>
### Creating Closure_Contexts

Create a Closure_Context from any object and a closure:

```php
use Haijin\Closure_Context;

// Create a sample object

class SampleClass
{
    public function sum($a, $b)
    {
        return $a + $b;
    }
}

$object = new SampleClass();


// Create a Closure_Context from a closure and bind it to $object.

$closure = new Closure_Context( $object, function($a, $b) {
    var_dump( $this );

    return $this->sum( $a, $b );
});
```

<a name="c-1-2"></a>
### Evaluating Closure_Contexts

Evaluate the Closure_Context.

Within its held closure `$this` will point to its held object.

```php
$result = $closure->evaluate( 3, 4 );

print $result . "\n";
```