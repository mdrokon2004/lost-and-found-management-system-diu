USE lost_found_diu;

CREATE OR REPLACE VIEW approved_lost_items AS
SELECT
    l.id,
    l.title,
    l.description,
    c.name AS category,
    loc.name AS location,
    l.lost_date
FROM lost_items AS l
JOIN categories AS c
    ON c.id = l.category_id
JOIN locations AS loc
    ON loc.id = l.location_id
JOIN statuses AS s
    ON s.id = l.status_id
WHERE s.code = 'approved';