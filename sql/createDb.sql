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
	tr_folio VARCHAR(20),
	tr_pr_id INTEGER,
	tr_pr_code VARCHAR(20),
	tr_date VARCHAR(40),
	tr_center VARCHAR(40),
	tr_time VARCHAR(40),
	tr_lot_num INTEGER,
	tr_sack_num INTEGER,
	tr_total_weight DECIMAL(10,4),
	tr_tare DECIMAL(10,4),
	tr_net_weight DECIMAL(10,4),
	tr_quality VARCHAR(40),
	tr_quality_sf DECIMAL,
	tr_quality_mordido DECIMAL,
	tr_quality_pelado DECIMAL,
	tr_quality_verde DECIMAL,
	tr_quality_broca DECIMAL,
	tr_quality_moho DECIMAL,
	tr_quality_gqmd DECIMAL,
	tr_quality_total DECIMAL,
	tr_observations VARCHAR(40),
	tr_receiver VARCHAR(40),
	tr_certification VARCHAR(40)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;  

LOAD DATA LOCAL INFILE 'producerlist.csv' 
INTO TABLE producer
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n' 
(pr_code, pr_name, pr_community, pr_certification);
