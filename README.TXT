// Plugin CakeClient //

This plugin is made to provide simple CRUD access to all model tables, 
based on a customizable scaffolding pattern. 

Also included are a combined menu & ACL, UserManagement and several UtilityClasses.


MODELS, CONTROLLERS

... don't need to be neccessarily existant. Cake generates them dynamically for us 
and CakeClient CrudComponent provides the most basic functionality for them. 

But have an eye on naming and how CakeClient & Cake figure out controllernames, modelnames and tablenames again 
from the table name!
table_names -> controller_names -> model_name -> table_names => ok
table_name -> controller_name -> model_name -> table_names => !ok
If a table name for what reason ever is singular, the model classname MUST reflect the table name camelCased 
and explicitly define the table name for everything to work. 
This is also in effect for model relations. 

as long the corresponding table exists (if a table name is singular, eg. "config", this is a problem). 
Cake generates them dynamically for us. 
CakeClient CrudComponent provides the most basic functionality for them. 