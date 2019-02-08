<?php

\Haijin\Specs\Specs_Runner::configure( function($specs) {

    $this->def( "remove_folder", function($folder) {

        $files = glob( $folder . "/*" );

        foreach( $files as $file ) {
            is_dir( $file ) ? $this->remove_folder( $file ) : unlink( $file );
        }

        if( is_dir( $folder ) ) {
            rmdir( $folder );
        }

    });

});