select b.bname, sum(price_sold) as total
from sale s, vehicle v, model m, brand b
where s.VIN = v.VIN
and v.model_id = m.model_id
and m.bname = b.bname
and s.sdate between current_date - interval '1' year and current_date
group by b.bname
order by total desc