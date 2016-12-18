create table if not exists `auth_rule`
(
	`name` varchar(64) not null,
	`data` text,
	`created_at` integer,
	`updated_at` integer,
	primary key (`name`)
) engine InnoDB;

create table if not exists `auth_item`
(
	`name` varchar(64) not null,
	`type` integer not null,
	`description` text,
	`rule_name` varchar(64),
	`data` text,
	`created_at` integer,
	`updated_at` integer,
	primary key (`name`),
	foreign key (`rule_name`) references `auth_rule` (`name`) on delete set null on update cascade,
	key `type` (`type`)
) engine InnoDB;

create table if not exists `auth_item_child`
(
	`parent` varchar(64) not null,
	`child` varchar(64) not null,
	primary key (`parent`, `child`),
	foreign key (`parent`) references `auth_item` (`name`) on delete cascade on update cascade,
	foreign key (`child`) references `auth_item` (`name`) on delete cascade on update cascade
) engine InnoDB;

create table if not exists `auth_assignment`
(
	`item_name` varchar(64) not null,
	`user_id` varchar(64) not null,
	`created_at` integer,
	primary key (`item_name`, `user_id`),
	foreign key (`item_name`) references `auth_item` (`name`) on delete cascade on update cascade
) engine InnoDB;

create table if not exists `User`
(
	`id` int(10) not null auto_increment,
	`admin` tinyint(1) default 0,
	`email` varchar(100) not null,
	`passwordHash` varchar(60),
	`active` tinyint(1) default 1,
	`createDate` datetime,
	`loginDate` datetime,
	`loginIP` varchar(15),
	`firstName` varchar(50),
	`lastName` varchar(50),
	`confirmed` tinyint(1) default 0,
	`mailing` tinyint(1) default 0,
	`passwordResetToken` varchar(50),
	`confirmToken` varchar(50),
	`authKey` varchar(50),
	`comment` varchar(200),
	primary key (`id`),
	key `email` (`email`)
) engine InnoDB;
