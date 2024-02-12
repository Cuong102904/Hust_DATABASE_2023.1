SELECT * 
FROM orders
JOIN orderlines ON orderlines.orderid = orders.orderid;


SELECT *
FROM orderlines
WHERE orderid =2;

UPDATE orders o
SET orderdate = '2004-01-02'
FROM orderlines ol
WHERE o.orderid = 2 AND ol.orderid = 2;


UPDATE orders
SET orderdate = '2004-01-02' 
WHERE orderid = 2;

UPDATE orderlines
SET orderdate = '2004-01-02' 
WHERE orderid = 2;

SELECT 
    orders.orderid,
    orders.orderdate,
    orders.customerid,
    orders.netamount,
    orders.tax,
    orders.totalamount,
    orderlines.prod_id,
	orderlines.orderlineid,
    orderlines.quantity,
    orderlines.orderdate AS orderlines_orderdate  
FROM orders
JOIN orderlines ON orderlines.orderid = orders.orderid;



 SELECT * 
FROM orders
JOIN orderlines ON orderlines.orderid = orders.orderid;


SELECT 
    customers.customerid,
    customers.firstname,
    customers.lastname,
    customers.email,
    customers.phone,
    orders.orderid,
    orders.orderdate,
    orders.netamount,
    orders.tax,
    orders.totalamount
FROM customers
LEFT JOIN orders ON customers.customerid = orders.customerid;




SELECT * 
FROM orders
WHERE orderid = 2;


SELECT
    products.prod_id,
    products.title,
    products.actor,
    products.price,
	products.special,
	products.common_prod_id
FROM
    products
JOIN
    orderlines ON products.prod_id = orderlines.prod_id
JOIN
    orders ON orderlines.orderid = orders.orderid
WHERE
    orders.orderdate = '2004-01-01';


DELETE FROM orderlines
WHERE orderid IN (SELECT orderid FROM orders WHERE orderdate = '2004-01-01');

DELETE FROM orders
WHERE orderdate = '2004-01-01';

SELECT * 
FROM customers
WHERE EXTRACT(MONTH FROM TO_DATE(creditcardexpiration, 'MM/YYYY')) = 5
    AND EXTRACT(YEAR FROM TO_DATE(creditcardexpiration, 'MM/YYYY')) = 2012;

SELECT *
FROM customers
WHERE creditcardexpiration LIKE '05/2012%';

SELECT *
FROM customers;


SELECT
    products.prod_id,
    products.title,
    COUNT(orderlines.orderid) AS total_orders,
    EXTRACT(MONTH FROM orders.orderdate) AS order_month
FROM
    products
LEFT JOIN
    orderlines ON products.prod_id = orderlines.prod_id
LEFT JOIN
    orders ON orderlines.orderid = orders.orderid
GROUP BY
    products.prod_id, products.title, EXTRACT(MONTH FROM orders.orderdate)
ORDER BY
    order_month, products.prod_id;

SELECT
    products.prod_id,
    products.title
FROM
    products
LEFT JOIN
    orderlines ON products.prod_id = orderlines.prod_id
WHERE
    orderlines.prod_id IS NULL;









SELECT
    customers.customerid,
    customers.firstname,
    customers.lastname,
    SUM(orders.totalamount)
FROM
    customers
LEFT JOIN
    orders ON customers.customerid = orders.customerid
GROUP BY
    customers.customerid, customers.firstname, customers.lastname
ORDER BY
    customers.customerid;




SELECT
    customers.state,
    COUNT(customers.customerid)
FROM
    customers
GROUP BY
    customers.state
HAVING
    COUNT(customers.customerid) > 200
ORDER BY
    customers.state;
