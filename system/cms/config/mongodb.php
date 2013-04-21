<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* -------------------------------------------------------------------
 * EXPLANATION OF VARIABLES
 * -------------------------------------------------------------------
 *
 * ['mongo_hostbase'] The hostname (and port number) of your mongod or mongos instances. Comma delimited list if connecting to a replica set.
 * ['mongo_database'] The name of the database you want to connect to
 * ['mongo_username'] The username used to connect to the database (if auth mode is enabled)
 * ['mongo_password'] The password used to connect to the database (if auth mode is enabled)
 * ['mongo_persist']  Persist the connection. Highly recommend you don't set to FALSE
 * ['mongo_persist_key'] The persistant connection key
 * ['mongo_replica_set'] If connecting to a replica set, the name of the set. FALSE if not.
 * ['mongo_query_safety'] Safety level of write queries. "safe" = committed in memory, "fsync" = committed to harddisk
 * ['mongo_suppress_connect_error'] If the driver can't connect by default it will throw an error which dislays the username and password used to connect. Set to TRUE to hide these details.
 * ['mongo_host_db_flag']   If running in auth mode and the user does not have global read/write then set this to true
 */
/*
$config['mongo_host'] = '192.168.1.35';
$config['mongo_port'] = 27017;
$config['mongo_db'] = 'micanal_pre';
*/

// El Comercio
$config['mongo_host'] = '10.203.31.139';
$config['mongo_db'] = 'micanaldevmdb';
$config['mongo_port'] = 27017;


/*
$config['mongo_user'] = '';
$config['mongo_pass'] = '';
$config['mongo_persist'] = TRUE;
$config['mongo_persist_key'] = 'ci_mongo_persist';
*/