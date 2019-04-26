select month, count(*) as convertibles_sold
from (
  select extract(month from s.sdate) as month
  from vehicle v, sale s, model m, has_style h, style st
  where s.VIN = v.VIN
  and m.model_id = v.model_id
  and h.model_id = m.model_id
  and st.style_id = h.style_id
  and st.sname = 'convertible')
group by month
order by convertibles_sold, month