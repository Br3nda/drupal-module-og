// $ID$

PLEASE BACKUP YOUR NODE_ACCESS TABLE BEFORE PROCEEDING

If upgrading from before 2005-10-19
- run update.og.php script
- run og-update-20060206.php 

If upgrading from after 2005-10-19
- run og-update-20060206.php

Note:
- the 20060206 script outputs all its queries to the screen just for debugging. these are not errors. this is a safe update script and you can even run it many times if you were concerned that it did not run properly.
- if you happenned to run the 20060116 update (which is deprecated and removed from CVS), you should still run the 20060206 update. 20060206 fixes node edit/delete for group admins
- the feb 19 update reenables feature where public nodes appear on grop home page 
