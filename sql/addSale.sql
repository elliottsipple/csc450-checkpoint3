insert into sale values( :vin, 
		:customer_id, 
		TO_DATE(:sale_date, 'yyyy-mm-dd'),
		:price) 
