select *
from vehicle v, model m, dealer d, brand b
where v.VIN not in (select VIN from sale)
and v.model_id = m.model_id
and m.bname = b.bname
and v.dealer_id = :dealer_id
and d.dealer_id = :dealer_id
order by m.bname, m.mname