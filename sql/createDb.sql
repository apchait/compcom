DROP TABLE IF EXISTS producer;
CREATE TABLE producer(
	pr_id INTEGER AUTO_INCREMENT PRIMARY KEY,
	pr_code VARCHAR(40),
	pr_name VARCHAR(80),
	pr_certification VARCHAR(40),
	pr_community VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS transaction;
CREATE TABLE transaction(
	tr_id INTEGER AUTO_INCREMENT PRIMARY KEY,
	tr_pr_name VARCHAR(40),
	tr_folio VARCHAR(20),
	tr_pr_id INTEGER,
	tr_pr_code VARCHAR(20),
	tr_date VARCHAR(40),
	tr_center VARCHAR(40),
	tr_time VARCHAR(40),
	tr_lot_num SMALLINT,
	tr_sack_num SMALLINT,
	tr_total_weight DECIMAL(10,2),
	tr_tare DECIMAL(10,2),
	tr_net_weight DECIMAL(10,2),
	tr_quality VARCHAR(40),
	tr_quality_sf TINYINT,
	tr_quality_mordido TINYINT,
	tr_quality_pelado TINYINT,
	tr_quality_verde TINYINT,
	tr_quality_broca TINYINT,
	tr_quality_moho TINYINT,
	tr_quality_gqmd TINYINT,
	tr_quality_total TINYINT,
	tr_observations VARCHAR(40),
	tr_receiver VARCHAR(40),
	tr_certification VARCHAR(40),
	tr_type VARCHAR(40),
	tr_price_reference DECIMAL(10,2),
	tr_price_fixed DECIMAL(10,2)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;  

LOAD DATA LOCAL INFILE 'producerlist.csv' 
INTO TABLE producer
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n' 
(pr_code, pr_name, pr_community, pr_certification);
