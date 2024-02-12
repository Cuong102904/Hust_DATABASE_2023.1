CREATE TABLE customer (
	customer_id BIGSERIAL PRIMARY KEY,
	first_name VARCHAR(50),
	last_name VARCHAR(50),
	phone_number VARCHAR(20),
	expenditure integer default 0
);

CREATE TABLE product (
	prod_id SERIAL PRIMARY KEY,
	prod_name varchar(50),
	prod_type varchar(20),
	prod_cost integer NOT NULL,
	stock integer,  
	price integer NOT NULL
);

	CREATE TABLE sitting_area (
		table_id SERIAL PRIMARY KEY,
		floor integer NOT NULL,
		status_ varchar(20) CHECK (status_ IN ('FULL','EMPTY'))
	);

CREATE TABLE staff (
	staff_id integer PRIMARY KEY UNIQUE NOT NULL,
	first_name VARCHAR(50),
	last_name VARCHAR(50),
	address_staff VARCHAR(50) NOT NULL,
	phone_number VARCHAR(20) NOT NULL UNIQUE,
	Total_star INTEGER default 0,
	Rating_quantity INTEGER default 0,
	rating FLOAT default 0
);

CREATE TABLE bartender (
	year_experiment INTEGER,
	award VARCHAR(100)
)INHERITS (staff);

CREATE TABLE certificate_bartender (
    bartender_id INTEGER,
    certificate_name VARCHAR(100)
);

CREATE TABLE chef (
	year_experiment INTEGER,
	award VARCHAR(100)
) INHERITS (staff);


CREATE TABLE certificate_chef (
    chef_id INTEGER,
    certificate_name VARCHAR(100)
);

CREATE TABLE waiter (
	
)INHERITS(staff);

CREATE TABLE orders (
	order_id SERIAL PRIMARY KEY,
	customer_id integer,
	table_id integer ,
	bartender_id integer ,
	chef_id integer ,
	waiter_id integer ,
	status_ VARCHAR(20) CHECK (status_ IN ('DONE', 'IN PROGRESS', 'CANCEL')),
	total_price integer default 0,
	order_date TIMESTAMP
);

CREATE TABLE orderline (
	orderline_id integer,
	order_id integer,
	product_id integer,
	quantity smallint NOT NULL
	
);

CREATE TABLE order_position (
	order_id SERIAL,
	table_id SERIAL
)


