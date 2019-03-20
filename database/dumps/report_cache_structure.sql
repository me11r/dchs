create table report_caches
(
	id int unsigned auto_increment
		primary key,
	name varchar(255) not null,
	data longtext not null,
	created_at timestamp null,
	updated_at timestamp null
)
collate=utf8mb4_unicode_ci;

create index report_caches_name_index
	on report_caches (name);
