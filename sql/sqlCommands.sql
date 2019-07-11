create database real_state;
use real_state;

create table typeHousing (
  id_type int(10) primary key not null auto_increment,
  description varchar(50) not null
)ENGINE=INNODB;

create table housing (
 id_housing int(10) primary key not null auto_increment,
 title varchar(50) not null,
 address varchar(100) not null,
 city varchar(50) not null,
 pc varchar(4) not null,
 area float(6,2) not null,
 price float(10,2) not null,
 photo varchar(200),
 id_type int(10) not null,
 description varchar(500),
 CONSTRAINT fk_id_type Foreign Key(id_type) REFERENCES typeHousing(id_type)
)ENGINE=INNODB;

INSERT INTO typeHousing (description) VALUES ('sale');
INSERT INTO typeHousing (description) VALUES ('letting');