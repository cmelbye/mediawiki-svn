
select

ecomm.utm_campaign as campaign,
imp.utm_source as banner,
ecomm.landing_page as landing_page,

sum(impressions) as impressions,
sum(views) as views,

sum(donations) as donations,
sum(amount) as amount,
sum(views) / sum(impressions) * 100 as click_rate,

sum(donations) / sum(impressions) * 100000 as don_per_imp,
sum(amount) / sum(impressions) * 100000 as amt_per_imp,

sum(donations) / sum(views) * 100000 as don_per_view,
sum(amount) / sum(views) * 100000 as amt_per_view

from

(select 
utm_source, 
sum(counts) as impressions
from impression
where on_minute >= '%s' 
group by 1) as imp
,
(select 
utm_source, 
landing_page,
landing_page.utm_campaign,
count(*) as views

from landing_page, faulkner.campaigns

where request_time >= '%s'
and landing_page.utm_campaign = faulkner.campaigns.utm_campaign
group by 1,2,3) as lp
,
(select 
SUBSTRING_index(substring_index(utm_source, '.', 2),'.',1) as banner,
SUBSTRING_index(substring_index(utm_source, '.', 2),'.',-1) as landing_page,
contribution_tracking.utm_campaign,
count(*) as total_clicks,
sum(not isnull(contribution_tracking.contribution_id)) as donations,
sum(converted_amount) AS amount

from
drupal.contribution_tracking LEFT JOIN civicrm.public_reporting 
ON (contribution_tracking.contribution_id = civicrm.public_reporting.contribution_id),
faulkner.campaigns

where ts >= '%s'
and contribution_tracking.utm_campaign = faulkner.campaigns.utm_campaign
group by 1,2,3) as ecomm


where lp.utm_source = imp.utm_source 
and ecomm.banner = lp.utm_source 
and ecomm.landing_page = lp.landing_page 
and ecomm.utm_campaign = lp.utm_campaign

group by 1,2 order by 1
