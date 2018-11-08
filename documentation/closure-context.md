# ClosureContext

A class to keep and later evaluate a closure in the context of an object.

## Table of contents

1. [Examples](#c-1)
    1. [Creating ClosureContexts](#c-1-1)
    2. [Evaluating ClosureContexts](#c-1-2)

<a name="c-1"></a>
## Examples

[Code examples](./closure-context-examples.php).


<a name="c-1-1"></a>
### Creating ClosureContexts

Create a ClosureContext from any object and a closure:

```php
use Haijin\Tools\ClosureContext;

// Create a sample object

class SampleClass
{
    public function sum($a, $b)
    {
        return $a + $b;
    }
}

$object = new SampleClass();


// Create a ClosureContext from a closure and bind it to $object.

$closure = new ClosureContext( $object, function($a, $b) {
    var_dump( $this );

    return $this->sum( $a, $b );
});
```

<a name="c-1-2"></a>
### Evaluating ClosureContexts

Evaluate the ClosureContext.

Within its held closure `$this` will point to its held object.

```php
$result = $closure->evaluate( 3, 4 );

print $result . "\n";
```