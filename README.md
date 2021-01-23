# fix-topic-counts
PHP script to correct topic_posts_approved values after mass deletion of posts
***ALPHA CODE - USE AT YOUR OWN RISK***
Intended only for phpBB forum management. 
Recently had to delete a forum user account which had tens of thousands of posts. At one point the system threw a 500 error, resulting in conflicts between the number of posts listed for topics in the phpbb_posts table in the forum database and the value for "topic_posts_approved" in the phpbb_topics table records for a multitude of rows in the phpbb_topics table.
That resulted in forum-generated links to posts not landing at the specified anchor point in the link, for example: "https://www.sampleforum.com//viewtopic.php?f=10&t=47315&p=1053654#p1053654 or "viewtopic.php?p=1052841#p1052841". 
This PHP script is intended to fix that problem. Currently testing on a copy of the active forum database.
***DO NOT RUN THIS SCRIPT ON AN ACTIVE PRODUCTION DATABASE!***
This script is ALPHA code and I do not recommend use at this time. However, someone out there might be able to do more with it, so here it is.
Did I mention, ***DO NOT RUN THIS SCRIPT ON AN ACTIVE PRODUCTION DATABASE!***
