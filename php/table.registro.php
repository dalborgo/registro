<?php

/*
 * Editor server script for DB table registro
 * Created by http://editor.datatables.net/generator
 */
// DataTables PHP library and database connection
include( "lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate;

// Build our Editor instance and process the data coming from _POST

// ->validator( 'Validate::dateFormat', array( 'format'=>'D, j M Y H:i:s' ) )

Editor::inst( $db, 'registro', 'id' )
	->fields(
        Field::inst( 'data' )
            ->getFormatter( 'Format::datetime', array( 'from'=>'Y-m-d H:i:s', 'to'  =>'D, j M Y H:i:s' ) )
            ->setFormatter( 'Format::datetime', array( 'to'  =>'Y-m-d H:i:s', 'from'=>'D, j M Y H:i:s' ) )
            ->validator( 'Validate::notEmpty' ),
		Field::inst( 'descrizione' ),
		Field::inst( 'entrata' )->setFormatter( function($val, $data, $field) {
		    if ($val == '' || $val == null)
		        return null;
            else
                return str_replace ( ',' , '.' , $val );
        })->validator( 'Validate::numeric' ),
		Field::inst( 'uscita' )->setFormatter( function($val, $data, $field) {
            if ($val == '' || $val == null)
                return null;
            else
                return str_replace ( ',' , '.' , $val );
        })->validator( 'Validate::numeric' ),
		Field::inst( 'totale' )->set(false)
	)
	->process( $_POST )
	->json();
