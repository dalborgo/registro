-- 
-- Editor SQL for DB table registro
-- Created by http://editor.datatables.net/generator
-- 

CREATE TABLE IF NOT EXISTS `registro` (
	`id` int(10) NOT NULL auto_increment,
	`data` date,
	`descrizione` varchar(255),
	`entrata` numeric(9,2),
	`uscita` numeric(9,2),
	`totale` numeric(9,2),
	PRIMARY KEY( `id` )
);