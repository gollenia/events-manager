const testFolder = './languages/';
const fs = require( 'fs' );
const { exec } = require( 'child_process' );

fs.readdir( testFolder, ( err, files ) => {
	console.log( files );
	files.forEach( ( file ) => {
		if ( file.slice( -2 ) === 'po' ) {
			const basename = file.slice( 0, -2 );
			exec( 'msgfmt ./languages/' + file + ' -o ./languages/' + basename + 'mo', ( error, stdout, stderr ) => {
				if ( error ) {
					console.log( '❌ ' + error );
					return;
				}
				console.log( '✅ compiled ' + file );
			} );
		}
	} );
} );
