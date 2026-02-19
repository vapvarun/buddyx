/* eslint-env es6 */
'use strict';

import path from 'node:path';
import fs from 'node:fs';
import fse from 'fs-extra';
import archiver from 'archiver';

import { prodThemePath } from '../lib/constants.js';
import { getThemeConfig } from '../lib/utils.js';

/**
 * Gulp-free production compression task: create a zip from prodThemePath
 * @param {Function} done callback when finished
 */
export default function prodCompress( done ) {
	try {
		const config = getThemeConfig();

		if ( ! config.export.compress ) {
			return done();
		}

		// Use dist_slug for zip/folder names (allows hyphens), slug for PHP identifiers
		const distSlug = config.theme.dist_slug || config.theme.slug;
		const version = config.theme.version;
		const zipName = version ? `${ distSlug }-${ version }.zip` : `${ distSlug }.zip`;
		const destDir = path.normalize( path.join( prodThemePath, '..' ) );
		const destZip = path.join( destDir, zipName );

		fse.ensureDirSync( destDir );

		const output = fs.createWriteStream( destZip );
		const archive = archiver( 'zip', { zlib: { level: 9 } } );

		output.on( 'close', () => {
			// Clean up temp folder after zip is created
			try {
				fse.removeSync( prodThemePath );
			} catch ( cleanupErr ) {
				console.warn( `Warning: Could not clean up temp folder: ${ cleanupErr.message }` );
			}
			done();
		} );
		output.on( 'error', ( err ) => done( err ) );
		archive.on( 'error', ( err ) => done( err ) );

		archive.pipe( output );
		// Add contents of prodThemePath with dist_slug as root folder in zip
		archive.directory( prodThemePath, distSlug );
		archive.finalize();
	} catch ( e ) {
		done( e );
	}
}
