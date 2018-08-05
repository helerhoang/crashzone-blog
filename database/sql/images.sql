SELECT * FROM crash_zone_blog.images order by id desc;

SELECT * FROM crash_zone_blog.images;

TRUNCATE TABLE crash_zone_blog.images;

SET FOREIGN_KEY_CHECKS=0;
TRUNCATE crash_zone_blog.images;

DELETE FROM crash_zone_blog.images
WHERE id <> 1;

SET FOREIGN_KEY_CHECKS=1;