use database_name;
create table session (
	id int(11) auto_increment not null,
	valid tinyint(1) default null,
	session_key varchar(32) default null, 
	user_id int(11) default null,
	expiry datetime default null,
	ip_address varchar(255) default null,
	date_created datetime default null,
	date_updated datetime default null,
	primary key(id)
);