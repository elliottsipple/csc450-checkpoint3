select month, monthNum, count(*) as convertibles_sold
from (
  select to_char(s.sdate, 'mon') as month, extract(month from s.sdate) as monthNum
  from vehicle v, sale s, model m, has_style h, style st
  where s.VIN = v.VIN
  and m.model_id = v.model_id
  and h.model_id = m.model_id
  and st.style_id = h.style_id
  and st.sname = 'convertible'
  order by monthNum)
group by month, monthNum
order by convertibles_sold, monthNum