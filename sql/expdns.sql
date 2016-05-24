SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE perm_items (
  id int(4) NOT NULL,
  "name" varchar(64) NOT NULL,
  descr varchar(1024) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO perm_items (id, `name`, descr) VALUES(41, 'zone_master_add', 'User is allowed to add new master zones.');
INSERT INTO perm_items (id, `name`, descr) VALUES(42, 'zone_slave_add', 'User is allowed to add new slave zones.');
INSERT INTO perm_items (id, `name`, descr) VALUES(43, 'zone_content_view_own', 'User is allowed to see the content and meta data of zones he owns.');
INSERT INTO perm_items (id, `name`, descr) VALUES(44, 'zone_content_edit_own', 'User is allowed to edit the content of zones he owns.');
INSERT INTO perm_items (id, `name`, descr) VALUES(45, 'zone_meta_edit_own', 'User is allowed to edit the meta data of zones he owns.');
INSERT INTO perm_items (id, `name`, descr) VALUES(46, 'zone_content_view_others', 'User is allowed to see the content and meta data of zones he does not own.');
INSERT INTO perm_items (id, `name`, descr) VALUES(47, 'zone_content_edit_others', 'User is allowed to edit the content of zones he does not own.');
INSERT INTO perm_items (id, `name`, descr) VALUES(48, 'zone_meta_edit_others', 'User is allowed to edit the meta data of zones he does not own.');
INSERT INTO perm_items (id, `name`, descr) VALUES(49, 'search', 'User is allowed to perform searches.');
INSERT INTO perm_items (id, `name`, descr) VALUES(50, 'supermaster_view', 'User is allowed to view supermasters.');
INSERT INTO perm_items (id, `name`, descr) VALUES(51, 'supermaster_add', 'User is allowed to add new supermasters.');
INSERT INTO perm_items (id, `name`, descr) VALUES(52, 'supermaster_edit', 'User is allowed to edit supermasters.');
INSERT INTO perm_items (id, `name`, descr) VALUES(53, 'user_is_ueberuser', 'User has full access. God-like. Redeemer.');
INSERT INTO perm_items (id, `name`, descr) VALUES(54, 'user_view_others', 'User is allowed to see other users and their details.');
INSERT INTO perm_items (id, `name`, descr) VALUES(55, 'user_add_new', 'User is allowed to add new users.');
INSERT INTO perm_items (id, `name`, descr) VALUES(56, 'user_edit_own', 'User is allowed to edit their own details.');
INSERT INTO perm_items (id, `name`, descr) VALUES(57, 'user_edit_others', 'User is allowed to edit other users.');
INSERT INTO perm_items (id, `name`, descr) VALUES(58, 'user_passwd_edit_others', 'User is allowed to edit the password of other users.');
INSERT INTO perm_items (id, `name`, descr) VALUES(59, 'user_edit_templ_perm', 'User is allowed to change the permission template that is assigned to a user.');
INSERT INTO perm_items (id, `name`, descr) VALUES(60, 'templ_perm_add', 'User is allowed to add new permission templates.');
INSERT INTO perm_items (id, `name`, descr) VALUES(61, 'templ_perm_edit', 'User is allowed to edit existing permission templates.');

CREATE TABLE perm_templ (
  id int(4) NOT NULL,
  "name" varchar(128) NOT NULL,
  descr varchar(1024) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO perm_templ (id, `name`, descr) VALUES(1, 'Administrator', 'Administrator template with full rights.');

CREATE TABLE perm_templ_items (
  id int(4) NOT NULL,
  templ_id int(4) NOT NULL,
  perm_id int(4) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO perm_templ_items (id, templ_id, perm_id) VALUES(1, 1, 53);

CREATE TABLE users (
  id int(4) NOT NULL,
  username varchar(64) NOT NULL,
  "password" varchar(128) NOT NULL,
  fullname varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  description varchar(1024) NOT NULL,
  perm_templ int(1) NOT NULL,
  active int(1) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO users (id, username, `password`, fullname, email, description, perm_templ, active) VALUES(1, 'admin', 'd352ad16bfb279285c76952cf59c98e6', 'Administrator', 'admin@example.net', 'Administrator with full rights.', 1, 1);

CREATE TABLE zones (
  id int(4) NOT NULL,
  domain_id int(4) NOT NULL,
  "owner" int(4) NOT NULL,
  "comment" varchar(1024) DEFAULT NULL,
  zone_templ_id int(4) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO zones (id, domain_id, owner, `comment`, zone_templ_id) VALUES(1, 1, 1, '', 0);

CREATE TABLE zone_templ (
  id int(11) NOT NULL,
  "name" varchar(128) NOT NULL,
  descr varchar(1024) NOT NULL,
  "owner" int(11) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE zone_templ_records (
  id int(11) NOT NULL,
  zone_templ_id int(11) NOT NULL,
  "name" varchar(255) NOT NULL,
  "type" varchar(6) NOT NULL,
  content varchar(255) NOT NULL,
  ttl int(11) NOT NULL,
  prio int(11) NOT NULL,
  PRIMARY KEY (id)
);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
