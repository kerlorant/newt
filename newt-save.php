<?PHP
# newt-save.php: This PHP file round-trips save data through the server to allow the user to save it on their Computer as a download.

/* Newt-Web: A Newtonian Telescope CAD Program

Copyright (C) 2011 Kenneth H. Slater

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
Version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA */

// Put uploaded save data into a temporary file so it can be downloaded
$tfilename = tempnam( 'temp_for_saves', 'tmp' );// Get the name of a temporary file
$tfile = fopen( $tfilename, 'w+' ); 			// Open and get file handle
fwrite( $tfile, $_POST['saveData'] );			// Write the data to be save via download
rewind( $tfile );  								// Position to start of file

if( !$tfile ) {
	die( 'newt-save.php: Temporary file not found.' );// File doesn't exist, output error
} else {
	$filename = $_POST["fileName"].'.newt';		// User specified file name from save form
	// Set http headers to cause a download and don't use cached copy
	header( "Pragma: public" );
	header( "Expires: 0" );
	header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
	header( "Cache-Control: private", false );
	header( "Content-Type: text/newt" );
	header( "Content-Description: File Transfer" );
	header( "Content-Disposition: attachment; filename=\"" . $filename . "\"" );
	header( "Content-Transfer-Encoding: binary" );

	// Read the file from disk, causing download to browser
	readfile( $tfilename );
}
fclose( $tfile ); // Closing Deletes the temporary file
?>