ALTER TABLE chef ADD PRIMARY KEY (staff_id);
ALTER TABLE waiter ADD PRIMARY KEY (staff_id);
ALTER TABLE bartender ADD PRIMARY KEY (staff_id);

ALTER TABLE order_position 
ADD CONSTRAINT fk_order_id_position FOREIGN KEY (order_id) REFERENCES order(order_id),
ADD CONSTRAINT fk_table_id_position FOREIGN KEY (table_id) REFERENCES sitting_area(table_id);

ALTER TABLE orderline
ADD CONSTRAINT fk_order_id FOREIGN KEY (order_id) REFERENCES orders(order_id),
ADD CONSTRAINT fk_product_id FOREIGN KEY (product_id) REFERENCES product(prod_id);

ALTER TABLE certificate_bartender
ADD CONSTRAINT fk_bartender_id FOREIGN KEY (bartender_id) REFERENCES bartender(staff_id);

ALTER TABLE certificate_chef
ADD CONSTRAINT fk_chef_id FOREIGN KEY (chef_id) REFERENCES chef(staff_id);


ALTER TABLE orders
ADD CONSTRAINT fk_customer_ID FOREIGN KEY(customer_id) REFERENCES customer(customer_id) ON DELETE CASCADE,
ADD CONSTRAINT fk_bartender_id FOREIGN KEY(bartender_id) REFERENCES
bartender(staff_id),
ADD CONSTRAINT fk_chef_id FOREIGN KEY(chef_id) REFERENCES
chef(staff_id),
ADD CONSTRAINT fk_waiter_id FOREIGN KEY(waiter_id) REFERENCES
waiter(staff_id);



CREATE INDEX idx_btree_order_id ON orders USING BTREE (order_id);
CREATE INDEX idx_btree_customer_id ON customer USING BTREE (customer_id);
CREATE INDEX idx_btree_product_id ON product USING BTREE (prod_id);
CREATE INDEX idx_btree_staff_id ON staff USING BTREE (staff_id);

