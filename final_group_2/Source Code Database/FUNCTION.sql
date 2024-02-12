--------------------------------FUNCTION

--- TÍNH TỔNG DOANH THU trước đó n tháng tại 1 thời điểm cố định
CREATE OR REPLACE FUNCTION calculate_revenue_custom_period(end_date TIMESTAMP, n integer)
RETURNS integer AS $$
DECLARE
    start_date TIMESTAMP;
    total_revenue integer;
BEGIN
    
    start_date := end_date - interval '1 month' * n;

    SELECT COALESCE(SUM(total_price), 0) INTO total_revenue
    FROM orders
    WHERE order_date >= start_date AND order_date <= end_date;

    RETURN total_revenue;
END;
$$ LANGUAGE plpgsql;
--VIDU
---SELECT calculate_revenue_custom_period('2023-06-25 00:00:00'::TIMESTAMP, 3);

--- tính doanh thu từ ngày bao nhiêu đến ngày bao nhiêu
CREATE OR REPLACE FUNCTION calculate_revenue_within_time_range(start_date TIMESTAMP, end_date TIMESTAMP)
RETURNS integer AS $$
DECLARE
    total_revenue integer;
BEGIN
    SELECT COALESCE(SUM(total_price), 0) INTO total_revenue
    FROM orders
    WHERE order_date >= start_date AND order_date <= end_date;

    RETURN total_revenue;
END;
$$ LANGUAGE plpgsql;
SELECT * FROM orders;
--VI DU
-----SELECT calculate_revenue_within_time_range('2023-06-24 00:00:00'::TIMESTAMP, '2023-06-25 00:00:00'::TIMESTAMP);

--- In ra cac thong tin cua 1 khach hang (don hang)
CREATE OR REPLACE FUNCTION get_trading_history_for_person(person_id INTEGER)
RETURNS TABLE (
    order_id INTEGER,
    customer_name VARCHAR,
    item_name VARCHAR(50),
    quantity INTEGER,
	order_totalprice INTEGER,
    waiter_name VARCHAR(100),
    order_date TIMESTAMP
	
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        o.order_id,
        (c.first_name || ' ' || c.last_name)::VARCHAR AS customer_name,
        (SELECT MAX(p.prod_name) FROM product p WHERE p.prod_id = ol.product_id)::VARCHAR AS item_name,
        ol.quantity::INTEGER,
		COALESCE(o.total_price, 0)::INTEGER AS order_totalprice,
		(SELECT MAX(w.first_name || ' ' || w.last_name) FROM staff w WHERE w.staff_id = o.waiter_id)::VARCHAR AS waiter_name,
        o.order_date
		
    FROM
        orders o
    JOIN
        customer c ON o.customer_id = c.customer_id
    LEFT JOIN
        orderline ol ON o.order_id = ol.order_id
    WHERE
        c.customer_id = person_id
	ORDER BY
		o.order_date DESC;

END;
$$ LANGUAGE plpgsql;

--SELECT * FROM get_trading_history_for_person(1);



----- tính lãi 
CREATE OR REPLACE FUNCTION calculate_total_profit()
RETURNS INTEGER AS $$
DECLARE
    total_revenue INTEGER;
    total_product_cost INTEGER;
    total_profit INTEGER;
BEGIN
    -- tính doanh thu
    SELECT SUM(total_price) INTO total_revenue
    FROM orders;

    -- tính tiền bỏ ra
    SELECT SUM(prod_cost * ol.quantity) INTO total_product_cost
    FROM orders o
    JOIN orderline ol ON o.order_id = ol.order_id
    JOIN product p ON ol.product_id = p.prod_id;

    -- tính lợi nhuận
    total_profit := total_revenue - total_product_cost;

    
    RETURN total_profit;
END;
$$ LANGUAGE plpgsql;
--SELECT calculate_total_profit();
----------delete customers
CREATE OR REPLACE FUNCTION delete_customer(customer_id INTEGER)
RETURNS BOOLEAN AS $$
BEGIN
    -- Delete associated orderlines
    DELETE FROM orderline WHERE orderline.order_id IN (SELECT order_id FROM orders WHERE orders.customer_id = delete_customer.customer_id);

    -- Delete associated orders
    DELETE FROM orders WHERE orders.customer_id = delete_customer.customer_id;

    -- Check if a row was affected (customer, orders, and orderlines were deleted)
    IF FOUND THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE plpgsql;

--SELECT delete_customer(1);



--- THÊM khách hàng
CREATE OR REPLACE FUNCTION insert_customer(
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone_number VARCHAR(20),
    expenditure INTEGER DEFAULT 0
)
RETURNS INTEGER AS $$
DECLARE
    new_customer_id INTEGER;
BEGIN
    SELECT COALESCE(MAX(customer_id), 0) + 1 INTO new_customer_id FROM customer;
    INSERT INTO customer (customer_id, first_name, last_name, phone_number, expenditure)
    VALUES (new_customer_id, first_name, last_name, phone_number, expenditure);
    RETURN new_customer_id;
END;
$$ LANGUAGE plpgsql;
SELECT * FROM customer;

--SELECT insert_customer('cuong', 'hoang', '123-321-888', 0);

CREATE OR REPLACE FUNCTION add_product(
    prod_name VARCHAR(50),
    prod_type VARCHAR(20),
    prod_cost INTEGER,
    stock INTEGER,
    price INTEGER
)
RETURNS INTEGER AS $$
DECLARE
    new_product_id INTEGER;
BEGIN
    
    SELECT COALESCE(MAX(prod_id), 0) + 1 INTO new_product_id FROM product;
    
    INSERT INTO product (prod_id, prod_name, prod_type, prod_cost, stock, price)
    VALUES (new_product_id, prod_name, prod_type, prod_cost, stock, price);
    
   
    RETURN new_product_id;
END;
$$ LANGUAGE plpgsql;

--SELECT add_product('nuoc loc', 'DRINK', 2000, 1000, 10000);

---in ra top nhan vien nhan sao cao nhat
CREATE OR REPLACE FUNCTION get_highest_rated_staff()
RETURNS SETOF staff AS
$$
BEGIN
	RETURN QUERY
	SELECT *
	FROM staff
	ORDER BY
        rating DESC
    LIMIT 10;
END;
$$
LANGUAGE plpgsql;
--SELECT * FROM get_highest_rated_staff();


CREATE OR REPLACE FUNCTION find_items_by_name(p_item_name VARCHAR(50))
RETURNS TABLE (
    prod_id INTEGER,
    prod_name VARCHAR(50),
    prod_type VARCHAR(20),
    prod_cost INTEGER,
    stock INTEGER,
    price INTEGER
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        p.prod_id,
        p.prod_name,
        p.prod_type,
        p.prod_cost,
        p.stock,
        p.price
    FROM
        product p
    WHERE
        LOWER(p.prod_name) LIKE LOWER('%' || p_item_name || '%');
END;
$$ LANGUAGE plpgsql;

--SELECT * FROM find_items_by_name('Green Tea');



---- display bill
CREATE OR REPLACE FUNCTION generate_bill(order_id_param INTEGER)
RETURNS TABLE (
    customer_id INTEGER,
    order_id INTEGER,
    waiter_id INTEGER,
    bartender_id INTEGER,
    chef_id INTEGER,
    product_name VARCHAR(50),
    quantity SMALLINT,
    price INTEGER,
    total_price INTEGER
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        o.customer_id,
        o.order_id,
        o.waiter_id,
        o.bartender_id,
        o.chef_id,
        p.prod_name AS product_name,
        ol.quantity,
        p.price,
        ol.quantity * p.price AS total_price
    FROM
        orders o
    JOIN
        orderline ol ON o.order_id = ol.order_id
    JOIN
        product p ON ol.product_id = p.prod_id
    WHERE
        o.order_id = order_id_param;

    RETURN;
END;
$$ LANGUAGE plpgsql;
--select * from	generate_bill(1);