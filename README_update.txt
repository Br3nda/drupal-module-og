// $Id: README_update.txt,v 1.1.4.7 2006/02/20 06:22:31 weitzman Exp $

Reminder: please backup our node_access table before running this update.

This script is safe to run from any 4.6 og. if you ran an update script before, you might see errors on the ALTER TABLE statements. 
Those can be safely skipped.

Note:
- this script outputs all its queries to the screen just for debugging. these are not errors. this is a safe update script and you can even run it many times if you were concerned that it did not run properly.
- the feb 19 update reenables feature where public nodes appear on group home page for non subscribers
