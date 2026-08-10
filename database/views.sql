USE lost_found_diu;
CREATE OR REPLACE VIEW approved_lost_items AS
SELECT l.id,l.title,c.name category,loc.name location,l.lost_date
FROM lost_items l
JOIN categories c ON c.id=l.category_id
JOIN locations loc ON loc.id=l.location_id
JOIN statuses s ON s.id=l.status_id
WHERE s.code='approved';
