<?php

\Haijin\Specs\Specs_Runner::configure( function($specs) {

});

function inspect($object)
{
	\Haijin\Debugger::inspect( $object );
}