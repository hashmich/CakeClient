# CakeClient


Database client plugin, providing generic CRUD-functionality 
for all database tables in CakePhp-based applications

Install as a submodule using: 

git submodule add https://github.com/hashmich/CakeClient.git app/Plugin/Cakeclient


## Models & Controllers

... don't need to be neccessarily existant. Cake generates them dynamically 
and CakeClient CrudComponent provides all required functionality for them. 


## Database Design Conventions

To figure out controller and model names, primary keys and entity relations, CakePhp heavily relies on conventions. 
Please read their documentation concerning that.

Basically, table names have to be in plural. 
The Inflector class is being used to derive the class names according to the schema below:

table_names -> TableNamesController -> TableName -> table_names

blog_posts -> BlogPostsController -> BlogPost -> blog_posts

Primary key name should be "id".

Foreign keys should be "<singular_foreign_table_name>_id", eg. "blog_post_id". 