create table if not exists courthouses (
  crths_id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  crths_courtname varchar(50),
  crths_address1 varchar(50),
  crths_address2 varchar(50),
  crths_city varchar(50),
  crths_state varchar(20),
  crths_stt_abbrev varchar(2),
  crths_zip varchar(10)
);
