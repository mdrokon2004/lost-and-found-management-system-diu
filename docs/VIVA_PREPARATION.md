# Viva Preparation

## Why PHP + MySQL?
PHP is beginner-friendly and integrates directly with MySQL, making CRUD, joins, constraints, views, procedures and triggers easy to demonstrate.

## Why separate lost_items and found_items?
The two workflows have different date meanings and business semantics while sharing common reference data such as categories, locations and statuses.

## Why foreign keys?
They preserve referential integrity between users, reports, categories, locations and statuses.

## Why normalization?
Reference data is separated to reduce duplication and update anomalies and to keep the schema close to 3NF.

## Authentication
Passwords are stored using `password_hash()` and checked using `password_verify()`. Sessions keep track of the logged-in user.

## SQL injection protection
Database operations use PDO prepared statements instead of concatenating user input into SQL.

## Workflow
A user submits a report -> the report begins as Pending Review -> an administrator can review it -> approved reports become visible -> another user can submit a claim -> the claim can be reviewed -> the case can become resolved.

## DBMS features
The project includes primary/foreign keys, unique constraints, indexes, joins, filtering, aggregation-ready schema, a view, a stored procedure and a trigger.

## Team explanation
Rokon: architecture, database, authentication and integration.
Kafi: public UI and navigation.
Iftekhar: lost/found reports and uploads.
Meheraj: claims, notifications and user dashboard.
Fayaz: admin dashboard, management and testing/documentation.
