//Config
app.config( [ '$compileProvider', function ( $compileProvider )
    {
        // disable debug info
        $compileProvider.debugInfoEnabled( false );
} ] );