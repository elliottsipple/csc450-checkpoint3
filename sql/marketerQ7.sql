select dealer, avg(time_in_inventory) as avg_time_in_inventory
from (
  select v.VIN, current_date - production_date as time_in_inventory, d.dname as dealer
    from dealer d, vehicle v
    where v.dealer_id = d.dealer_id
    and VIN not in (select VIN from sale)
  union
  select v.VIN, s.sdate - v.production_date as time_in_inventory, d.dname as dealer
    from dealer d, vehicle v, sale s
    where v.dealer_id = d.dealer_id
    and v.VIN = s.VIN)
group by dealer
order by avg_time_in_inventory desc