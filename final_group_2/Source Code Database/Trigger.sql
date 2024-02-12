-----------------------trigger-----------------

CREATE OR REPLACE FUNCTION check_stock()  --kiểm tra xem còn hàng hay không, không còn thì hủy
RETURNS TRIGGER AS
$$
BEGIN
    IF (SELECT stock FROM product WHERE prod_id = NEW.product_id) < NEW.quantity THEN
        RAISE EXCEPTION 'Product is out of stock';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER check_stock_trigger
BEFORE INSERT ON orderline
FOR EACH ROW
EXECUTE FUNCTION check_stock();

--------------------------------------------------
CREATE OR REPLACE FUNCTION update_stock()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE product
    SET stock = stock - NEW.quantity
    WHERE prod_id = NEW.product_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_stock_trigger
AFTER INSERT ON orderline
FOR EACH ROW
EXECUTE FUNCTION update_stock();

-----------------------------------------------------
CREATE OR REPLACE FUNCTION update_order_total_price()
RETURNS TRIGGER AS $$
DECLARE
    customer_expenditure integer;
BEGIN
    --- kiểm tra điều kiện order = done--
    IF (SELECT status_ FROM orders WHERE order_id = NEW.order_id) = 'DONE' THEN
    SELECT c.expenditure INTO customer_expenditure 
FROM customer c
JOIN orders ON c.customer_id = orders.customer_id
WHERE orders.order_id = NEW.order_id;
    
    --cập nhật total_price cho 
    UPDATE orders
    SET total_price = COALESCE ((SELECT SUM(product.price * orderline.quantity)
                      FROM orderline
                      JOIN product ON orderline.product_id = product.prod_id
                      WHERE orderline.order_id = NEW.order_id), 0)
    WHERE order_id = NEW.order_id;

    IF customer_expenditure > 300000 AND customer_expenditure < 1000000 THEN --giam 2%
        UPDATE orders
        SET total_price = total_price * (1 - 0.02)
        WHERE order_id = NEW.order_id;
	ELSIF customer_expenditure >= 1000000 THEN --giam 5%
		UPDATE orders
        SET total_price = total_price * (1 - 0.05)
        WHERE order_id = NEW.order_id;

        END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER calculate_total_price_trigger
AFTER INSERT ON orderline
FOR EACH ROW
EXECUTE FUNCTION update_order_total_price();


------------------------------------------------------
CREATE OR REPLACE FUNCTION update_customer_expenditure()
RETURNS TRIGGER AS $$
DECLARE
    total_item_price integer default 0;
BEGIN
    -- kiểm tra điều kiện order = done
     IF (SELECT status_ FROM orders WHERE order_id = NEW.order_id) = 'DONE' THEN
    --  Tinh gia cua item moi duoc nhap
    SELECT price * NEW.quantity INTO total_item_price
    FROM product
    WHERE prod_id = NEW.product_id;
    -- cap nhat lai so tien bo ra cua 1 vi khach
    UPDATE customer
    SET expenditure = expenditure + total_item_price
    FROM orders
    WHERE customer.customer_id = orders.customer_id AND orders.order_id = NEW.order_id;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER update_expenditure_after_orderline_insert
AFTER INSERT ON orderline
FOR EACH ROW
EXECUTE FUNCTION update_customer_expenditure();

--update rating star for staff
CREATE OR REPLACE FUNCTION update_rating()
RETURNS TRIGGER AS $$
BEGIN
    NEW.rating := NEW.total_star / NEW.rating_quantity ::FLOAT;
    UPDATE staff
    SET rating = NEW.rating
    WHERE staff_id = NEW.staff_id;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_bartender_rating_trigger
AFTER INSERT ON bartender
FOR EACH ROW
EXECUTE FUNCTION update_rating();

CREATE TRIGGER update_chef_rating_trigger
AFTER INSERT ON chef
FOR EACH ROW
EXECUTE FUNCTION update_rating();

CREATE TRIGGER update_waiter_rating_trigger
AFTER INSERT ON waiter
FOR EACH ROW
EXECUTE FUNCTION update_rating();

--- check trung staff_id
CREATE OR REPLACE FUNCTION check_unique_staff_id()
RETURNS TRIGGER AS $$
BEGIN
    IF (
        EXISTS (SELECT 1 FROM bartender WHERE staff_id = NEW.staff_id) OR
        EXISTS (SELECT 1 FROM chef WHERE staff_id = NEW.staff_id) OR
        EXISTS (SELECT 1 FROM waiter WHERE staff_id = NEW.staff_id)
    ) THEN
         SELECT COALESCE(MAX(staff_id) + 1, 1) INTO NEW.staff_id FROM staff;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_check_unique_staff_id
BEFORE INSERT ON staff
FOR EACH ROW EXECUTE FUNCTION check_unique_staff_id();

CREATE TRIGGER trigger_check_unique_staff_id_chef
BEFORE INSERT ON chef
FOR EACH ROW EXECUTE FUNCTION check_unique_staff_id();

CREATE TRIGGER trigger_check_unique_staff_id_waiter
BEFORE INSERT ON waiter
FOR EACH ROW EXECUTE FUNCTION check_unique_staff_id();

CREATE TRIGGER trigger_check_unique_staff_id_bartender
BEFORE INSERT ON bartender
FOR EACH ROW EXECUTE FUNCTION check_unique_staff_id();