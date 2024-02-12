CREATE OR REPLACE VIEW view_best_seller AS
SELECT
    p.prod_id,
    p.prod_name,
    p.prod_type,
    p.prod_cost,
    p.price,
    SUM(ol.quantity) AS total_quantity_sold
FROM
    product p
JOIN
    orderline ol ON p.prod_id = ol.product_id
JOIN
    orders o ON ol.order_id = o.order_id
WHERE
    o.status_ = 'DONE'
GROUP BY
    p.prod_id, p.prod_name, p.prod_type, p.prod_cost, p.price
ORDER BY
    total_quantity_sold DESC
LIMIT 10;
---SELECT * FROM view_best_seller;


--- VIEW 10 people spending most money
CREATE OR REPLACE VIEW view_top_spending_customers AS
SELECT
    c.customer_id,
    CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
    c.phone_number,
    c.expenditure AS total_expenditure
FROM
    customer c
ORDER BY
    c.expenditure DESC
    LIMIT 10;
--SELECT * FROM view_top_spending_customers;

