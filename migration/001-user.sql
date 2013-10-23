use database_name;
create table user (
	id int(11) auto_increment not null,
	email varchar(255) default null,
	password varchar(32) default null,
	first_name varchar(255) default null,
	last_name varchar(255) default null,
	date_created datetime default null,
	date_updated datetime default null,
	primary key(id)
);
