

INSERT INTO customer(customer_id,first_name,last_name,phone_number) VALUES (101,'hoangmanh', 'cuong','83-565-2146',0 );

INSERT INTO product(prod_name,prod_type,prod_cost,stock,price) VALUES ('nuoc loc', 'DRINK',2000,1000,10000);

INSERT INTO sitting_area VALUES (1,1,'EMPTY');
-- Insert sample data into the 'orders' table

-- Insert sample data into the 'staff' table
INSERT INTO bartender (staff_id, first_name, last_name, address_staff, phone_number, Total_star, Rating_quantity, rating,year_experiment, award)
VALUES
    (1, 'John', 'Doe', 'Sai dong', '555-1234', 0, 0, 0.0,5, 'Best Cocktail Maker'),
    (2, 'Jane', 'Smith', 'Long BIen', '555-5678', 0, 0, 0.0,5, 'Best Cocktail Maker'),
    (3, 'Bob', 'Johnson', 'Ha noi', '555-9012', 0, 0, 0.0,3, 'Mixologist of the Year');

-- Insert sample data into the 'certificate_bartender' table
INSERT INTO certificate_bartender (bartender_id, certificate_name)
VALUES
    (1, 'Cocktail Mastery Certificate'),
    (2, 'Advanced Mixology Diploma');

-- Insert sample data into the 'chef' table
INSERT INTO bartender (staff_id, first_name, last_name, address_staff, phone_number, Total_star, Rating_quantity, rating,year_experiment, award)
VALUES
    (1, 'John', 'Doe', 'Sai dong', '555-1234', 0, 0, 0.0, 8, 'Master Chef');

-- Insert sample data into the 'certificate_chef' table
INSERT INTO certificate_chef (chef_id, certificate_name)
VALUES
    (3, 'Culinary Excellence Award');

-- Insert sample data into the 'waiter' table
INSERT INTO waiter (staff_id, first_name, last_name, address_staff, phone_number, Total_star, Rating_quantity, rating)
VALUES
    (4, 'Alice', 'Johnson', '111 Oak St', '555-3456', 0, 0, 0.0),
    (5, 'Charlie', 'Brown', '222 Pine St', '555-7890', 0, 0, 0.0);

INSERT INTO orders (customer_id, table_id, bartender_id, chef_id, waiter_id, status_, order_date)
VALUES
    (101, 101, 201, 301, 401, 'IN PROGRESS', '2024-01-12 10:00:00'),
    (101, 102, 202, 302, 402, 'DONE', '2024-01-12 11:30:00'),
    (102, 103, 203, 303, 403, 'CANCEL', '2024-01-12 12:45:00');

-- Insert sample data into the 'orderline' table
INSERT INTO orderline (order_id, product_id, quantity)
VALUES
    (401, 1, 2),
    (401, 3, 1),
    (402, 2, 3),
    (403, 1, 1),
    (403, 4, 2);

-- Insert sample data into the 'order_position' table
INSERT INTO order_position (order_id, table_id)
VALUES
    (1, 1),
    (2, 2),
    (3, 13);

SELECT * FROM customer;
SELECT * FROM  orders;
SELECT * FROM staff;

--- check function
SELECT calculate_revenue_custom_period('2024-4-13 00:00:00'::TIMESTAMP, 3);
SELECT calculate_revenue_within_time_range('2023-06-24 00:00:00'::TIMESTAMP, '2023-06-25 00:00:00'::TIMESTAMP);
SELECT * FROM get_trading_history_for_person(1);
SELECT calculate_total_profit();
SELECT delete_customer(1);
SELECT insert_customer('cuong', 'hoang', '123-321-888', 0);
SELECT add_product('nuoc loc', 'DRINK', 2000, 1000, 10000);
SELECT * FROM get_highest_rated_staff();
SELECT * FROM find_items_by_name('Green Tea');
SELECT * FROM	generate_bill(1);
---view
SELECT * FROM view_best_seller;
SELECT * FROM view_top_spending_customers;


--- demo for index
SELECT * FROM view_best_seller;

SELECT * FROM view_top_spending_customers;

SELECT * FROM get_trading_history_for_person(2);

SELECT *
FROM orders
WHERE order_date >= CURRENT_DATE - INTERVAL '10 months'
  AND order_date <= CURRENT_DATE;
  
  
 SELECT DISTINCT c.*
FROM customer c
JOIN orders o ON c.customer_id = o.customer_id
WHERE o.order_date >= CURRENT_DATE - INTERVAL '5 months';
