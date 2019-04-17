select *
from vehicle v, model m, dealer d, brand b
where v.VIN not in (select VIN from sale)
and v.model_id = m.model_id
and m.bname = b.bname
and v.dealer_id = d.dealer_id
order by m.bname, m.mname, v.VIN