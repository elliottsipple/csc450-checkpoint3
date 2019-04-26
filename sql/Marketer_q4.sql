select dname, total
from (
  select d.dname, sum(price_sold) as total
  from sale s, vehicle v, model m, brand b, dealer d
  where s.VIN = v.VIN
  and v.model_id = m.model_id
  and m.bname = b.bname
  and v.dealer_id = d.dealer_id
  and s.sdate between current_date - interval '1' year and current_date
  group by d.dname
  order by total desc)
where rownum = 1