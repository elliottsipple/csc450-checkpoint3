select bname, year, gender, income_range, sum(price_sold) as total_sold
from (
  select bname, extract(year from s.sdate) as year, c.gender as gender, s.price_sold, case
    when c.annual_income < 50000 then '< 50K'
    when c.annual_income >=50000 and c.annual_income < 70000 then '50K-70K'
    when c.annual_income >=70000 and c.annual_income < 100000 then '70K-100K'
    when c.annual_income >=100000 and c.annual_income < 200000 then '100K-200K'
    else '> 200K' end as income_range
  from sale s, customer c, vehicle v, model m
  where s.customer_id = c.customer_id
  and s.VIN = v.VIN
  and v.model_id = m.model_id
  and s.sdate between current_date - interval '2' year and current_date)
group by bname, year, gender, income_range
order by bname, year, gender, income_range