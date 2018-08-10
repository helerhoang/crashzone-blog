CREATE DATABASE crashzone_2018;

SELECT * from crashzonenew.wp_posts where post_content is not null and post_content <> '';

-- START wp_users to users

SELECT * FROM crashzonenew.wp_users;
SELECT * FROM crashzone_2018.users;
-- BACK FILL
INSERT INTO crashzone_2018.users (name,email,password,created_at)
SELECT display_name, user_email, user_pass ,user_registered
FROM crashzonenew.wp_users;
-- END wp_users to users


-- START wp_posts to posts
SELECT * FROM crashzonenew.wp_posts LIMIT 10000;
SELECT * FROM crashzonenew.wp_posts
WHERE post_type = 'post'
AND post_status = 'publish' LIMIT 5000;
SELECT * FROM crashzone_2018.posts;
-- TRUNCATE
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE crashzone_2018.posts;
SET FOREIGN_KEY_CHECKS = 1;
-- BACKFILL
insert into crashzone_2018.posts (id,title, slug, description,content,user_id, status, old_guid, created_at, updated_at)
select ID, post_title, post_name, post_title, post_content,post_author,post_status, guid, post_date, post_modified
from crashzonenew.wp_posts
where post_type = 'post' 
and post_status = 'publish';
-- END wp_posts to posts

-- START wp_terms to categories depends on wp_term_taxonomy
SELECT * FROM crashzonenew.wp_term_taxonomy;

SELECT * FROM crashzonenew.wp_terms as a
JOIN
(SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy ='category') as b
ON a.term_id = b.term_id;

SELECT * FROM crashzonenew.wp_terms;

SELECT * FROM crashzone_2018.categories;
-- TRUNCATE
TRUNCATE TABLE crashzone_2018.categories;
-- BACK FILL
INSERT INTO crashzone_2018.categories (id,name,slug,created_at)
SELECT a.term_id,a.name, a.slug,CURRENT_TIMESTAMP() FROM crashzonenew.wp_terms as a
JOIN
(SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy ='category') as b
ON a.term_id = b.term_id ;
-- END wp_terms to categories depends on wp_term_taxonomy


-- START wp_terms to tags depends on wp_term_taxonomy
SELECT * FROM crashzone_2018.tags;
SELECT * FROM crashzonenew.wp_terms;
SELECT * FROM crashzonenew.wp_term_taxonomy;
SELECT * FROM crashzonenew.wp_terms as a
JOIN
(SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy ='post_tag') as b
ON a.term_id = b.term_id;
-- BACK FILL
INSERT INTO crashzone_2018.tags (id,name,slug,created_at)
SELECT a.term_id,a.name, a.slug,CURRENT_TIMESTAMP() FROM crashzonenew.wp_terms as a
JOIN
(SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy ='post_tag') as b
ON a.term_id = b.term_id;
-- END wp_terms to tags depends on wp_term_taxonomy

-- START wp_term_relationships to category_post depends on wp_term_taxonomy and posts
SELECT * FROM crashzonenew.wp_term_relationships;
SELECT * FROM crashzone_2018.posts;
SELECT * FROM crashzonenew.wp_term_taxonomy;
SELECT * FROM crashzone_2018.category_post;
-- TRUNCATE 
TRUNCATE TABLE crashzone_2018.category_post;
-- GET data to backfill
SELECT a.object_id, a.term_taxonomy_id FROM crashzonenew.wp_term_relationships as a
JOIN
(SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy = 'category') as b
ON a.term_taxonomy_id = b.term_taxonomy_id
JOIN 
(SELECT * FROM crashzone_2018.posts) as c
ON a.object_id = c.id
order by a.object_id;
-- BACK FILL from wp_term_relationships
INSERT INTO crashzone_2018.category_post (category_id,post_id,created_at)
(SELECT a.term_taxonomy_id,a.object_id,CURRENT_TIMESTAMP() FROM crashzonenew.wp_term_relationships as a
JOIN
(SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy = 'category') as b
ON a.term_taxonomy_id = b.term_taxonomy_id
JOIN
(SELECT * FROM crashzone_2018.posts) as c
ON a.object_id = c.id
order by a.object_id);
-- BACK FILL IF POST NOT HAVE CATEGORY
INSERT INTO crashzone_2018.category_post (category_id,post_id,created_at)
SELECT 1, id, CURRENT_TIMESTAMP() FROM crashzone_2018.posts WHERE id NOT IN (SELECT post_id FROM crashzone_2018.category_post) order by id;
-- END wp_term_relationships to category_post depends on wp_term_taxonomy and posts

-- START wp_term_relationships to post_tag depends on wp_term_taxonomy and posts
SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy = 'post_tag';

SELECT a.object_id, a.term_taxonomy_id FROM crashzonenew.wp_term_relationships as a
JOIN
(SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy = 'post_tag') as b
ON a.term_taxonomy_id = b.term_taxonomy_id
JOIN 
(SELECT * FROM crashzone_2018.posts) as c
ON a.object_id = c.id
order by a.object_id;
-- BACK FILL
INSERT INTO crashzone_2018.post_tag (post_id,tag_id,created_at)
(SELECT a.object_id, a.term_taxonomy_id, CURRENT_TIMESTAMP() FROM crashzonenew.wp_term_relationships as a
JOIN
(SELECT * FROM crashzonenew.wp_term_taxonomy WHERE taxonomy = 'post_tag') as b
ON a.term_taxonomy_id = b.term_taxonomy_id
JOIN 
(SELECT * FROM crashzone_2018.posts) as c
ON a.object_id = c.id
order by a.object_id);
-- CHECK DATA
SELECT * FROM crashzone_2018.post_tag;
-- END wp_term_relationships to post_tag depends on wp_term_taxonomy and posts
