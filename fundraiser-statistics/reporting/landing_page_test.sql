

select

lp.utm_campaign, 
lp.landing_page,
views as views,
total_clicks as clicks,
donations as donations,
amount as amount,
amount50 as amount50,
donations / total_clicks as completion_rate,
donations / views as don_per_view,
amount / views as amt_per_view,
amount50 / views as amt50_per_view,
max_amt,
pp_don,
cc_don,
pp_don / pp_clicks  as paypal_click_thru,
cc_don / cc_clicks as credit_card_click_thru


from

(select 
landing_page,
utm_campaign,
count(*) as views

from landing_page

where request_time >=  '%s' and request_time < '%s'
and (utm_campaign REGEXP '%s')
group by 1,2) as lp

left join

(select 
SUBSTRING_index(substring_index(utm_source, '.', 2),'.',-1) as landing_page,
utm_campaign,
count(*) as total_clicks,
sum(not isnull(contribution_tracking.contribution_id)) as donations,
sum(converted_amount) AS amount,
sum(if(converted_amount > 50, 50, converted_amount)) as amount50,
max(converted_amount) AS max_amt,
sum(if(right(utm_source,2)='cc',1,0))  as cc_clicks,
sum(if(right(utm_source,2)='cc' and contribution_tracking.contribution_id,1,0)) as cc_don,
sum(if(right(utm_source,2)='pp',1,0))  as pp_clicks,
sum(if(right(utm_source,2)='pp' and contribution_tracking.contribution_id,1,0)) as pp_don


from
drupal.contribution_tracking LEFT JOIN civicrm.public_reporting 
ON (contribution_tracking.contribution_id = civicrm.public_reporting.contribution_id)
where ts >=  '%s' and ts < '%s' 
and (utm_campaign REGEXP '%s')
group by 1,2) as ecomm

on ecomm.landing_page = lp.landing_page and ecomm.utm_campaign = lp.utm_campaign

where views > 1000

group by 1,2,3 order by 8 desc;





