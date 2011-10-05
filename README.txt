UPDATE waste SET ORDER = ORDER + 1;

//Defination Component : component can be action , controller , module

a)sync ACL allow() , addRole() , removeRole() with database at runtime
b)create tree
c)add a way by doc meta data to not to include action for Acl loader . Some good actions such as login , logout are good candidates for it
d)add extending support for roles only // postpone

---------------------
a) admin/acl/
   1)permission //a combo box with roles and a tree of selected role for changing permission
   2)roles       //a jquery Grid for CRUD roles
   3)users   //a jquery Grid for CRUD users
   4)sortRoles // a combo box with users and jquery ui sortable for roles for the selected user .
               //With a text field below (auto complete roles in it) with a add button
			   //click add button adds the 
 
 
 
 ---------------------------
 
 //To-do 
 a) if action
 
 
 ---------  Communication of Tree to the server
 All ids such as privilege_id , resource_id etc are sensitive ids hence for security purposes they shd not be part of tree
  
 a)When clicked on module or controller (isFolder = tree)
  then all its actions (leafs) would be added into an paramter
  
  {'command'=>'allow','data'=>[{node,node}]}
  where node object has properties  'module','controller','action'
  
  On Server based on coommand and role (stored in session) we call
  $acl->allow(...) or $acl->dney('...') based upon the command
  
  