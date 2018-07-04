create table `admin`
(
  id            int auto_increment
    primary key,
  admin_id      varchar(7)             not null,
  admin_name    varchar(255)           not null,
  auth_key      varchar(32)            not null,
  password_hash varchar(255)           not null,
  email         varchar(255)           not null,
  created_at    int                    not null,
  updated_at    int                    not null,
  super_admin   tinyint(1) default '0' null,
  web_admin     tinyint(1) default '0' null,
  student_admin tinyint(1) default '0' null,
  room_admin    tinyint(1) default '0' null,
  constraint admin_id
  unique (admin_id),
  constraint email
  unique (email)
);

create table auth_rule
(
  name       varchar(64) not null
    primary key,
  data       blob        null,
  created_at int         null,
  updated_at int         null
)
  collate = utf8_unicode_ci;

create table auth_item
(
  name        varchar(64) not null
    primary key,
  type        smallint(6) not null,
  description text        null,
  rule_name   varchar(64) null,
  data        blob        null,
  created_at  int         null,
  updated_at  int         null,
  constraint auth_item_ibfk_1
  foreign key (rule_name) references auth_rule (name)
    on update cascade
    on delete set null
)
  collate = utf8_unicode_ci;

create table auth_assignment
(
  item_name  varchar(64) not null,
  user_id    varchar(64) not null,
  created_at int         null,
  primary key (item_name, user_id),
  constraint auth_assignment_ibfk_1
  foreign key (item_name) references auth_item (name)
    on update cascade
    on delete cascade
)
  collate = utf8_unicode_ci;

create index auth_assignment_user_id_idx
  on auth_assignment (user_id);

create index `idx-auth_item-type`
  on auth_item (type);

create index rule_name
  on auth_item (rule_name);

create table auth_item_child
(
  parent varchar(64) not null,
  child  varchar(64) not null,
  primary key (parent, child),
  constraint auth_item_child_ibfk_1
  foreign key (parent) references auth_item (name)
    on update cascade
    on delete cascade,
  constraint auth_item_child_ibfk_2
  foreign key (child) references auth_item (name)
    on update cascade
    on delete cascade
)
  collate = utf8_unicode_ci;

create index child
  on auth_item_child (child);

create table campus
(
  id          int auto_increment
    primary key,
  campus_name varchar(64) not null,
  constraint campus_campus_name_uindex
  unique (campus_name)
);

create table migration
(
  version    varchar(180) not null
    primary key,
  apply_time int          null
);

create table room_type
(
  id        int auto_increment
    primary key,
  type_name varchar(64) not null,
  constraint room_type_type_name_uindex
  unique (type_name)
);

create table room
(
  id          int auto_increment
    primary key,
  room_number varchar(10)            not null,
  type        int                    not null,
  campus      int                    not null,
  available   tinyint(1) default '1' not null,
  constraint room_room_type_id_fk
  foreign key (type) references room_type (id),
  constraint room_campus_id_fk
  foreign key (campus) references campus (id)
);

create index room_campus_id_fk
  on room (campus);

create index room_room_type_id_fk
  on room (type);

create table user
(
  id                   int auto_increment
    primary key,
  student_id           varchar(7)               not null,
  username             varchar(255)             not null,
  auth_key             varchar(32)              not null,
  password_hash        varchar(255)             not null,
  password_reset_token varchar(255)             null,
  email                varchar(255)             not null,
  status               smallint(6) default '10' not null,
  created_at           int                      not null,
  updated_at           int                      not null,
  constraint student_id
  unique (student_id),
  constraint password_reset_token
  unique (password_reset_token),
  constraint email
  unique (email)
);

create table application
(
  id                int auto_increment
    primary key,
  applicant_id      int         not null,
  organization_name varchar(64) null,
  room_id           int         not null,
  start_time        int         not null,
  end_time          int         not null,
  event             text        not null,
  status            tinyint(1)  not null,
  created_at        int         not null,
  updated_at        int         not null,
  constraint application_ibfk_1
  foreign key (applicant_id) references user (id),
  constraint application_ibfk_2
  foreign key (room_id) references room (id)
);

create index application_ibfk_1
  on application (applicant_id);

create index application_ibfk_2
  on application (room_id);

delimiter $$

create trigger avoid_collision
  before UPDATE
  on application
  for each row
  begin
    if new.status = 1 and exists(select *
                                 from application
                                 where
                                   start_time < new.end_time and end_time > new.start_time and room_id = new.room_id and
                                   status = 1 and new.id != id)
    then
      signal sqlstate '45001'
      set message_text = '不允许批准冲突的申请';
    end if;
  end $$

delimiter ;


INSERT INTO `admin` (admin_id, admin_name, auth_key, password_hash, email, created_at, updated_at, super_admin, web_admin, student_admin, room_admin)
VALUES ('0000000', 'admin', 'hj8GOGo8sW_paLcBy18BBKh7XCXAX9nz',
  '$2y$13$jAbTuqXYhM.ij1bky2yYVePUbeL8gwlUhjBVTf3I46shMcNH0HMzq', 'admin@example.com', 1530690128, 1530690224, 1, 0, 0,
  0);

INSERT INTO user (student_id, username, auth_key, password_hash, password_reset_token, email, status, created_at, updated_at)
VALUES ('0000000', 'user', 'CyI7z2hEeCFg7cADwzj9obFG2Nef1wPQ',
        '$2y$13$xvN6oqIvSR0pq4As9DUz9eUGrkurmy8g1czq9V3Yg6cnf8MSQy3RW', null, 'user@example.com', 10, 1530692412,
        1530692412);

INSERT INTO `auth_item` VALUES ('manageAdmin', 2, '修改管理员', NULL, NULL, 1528614184, 1528614184),
  ('managePermission', 2, '分配权限', NULL, NULL, 1528614185, 1528614185),
  ('manageRoom', 2, '管理房间', NULL, NULL, 1528614185, 1528614185),
  ('manageStudent', 2, '修改学生', NULL, NULL, 1528614185, 1528614185),
  ('roomAdmin', 1, '预约管理员', NULL, NULL, 1528614186, 1528614186),
  ('studentAdmin', 1, '学生管理员', NULL, NULL, 1528614186, 1528614186),
  ('superAdmin', 1, '超级管理员', NULL, NULL, 1528614185, 1528614185),
  ('viewAdminList', 2, '浏览管理员列表', NULL, NULL, 1528614185, 1528614185),
  ('viewStudentList', 2, '浏览学生列表', NULL, NULL, 1528614185, 1528614185),
  ('webAdmin', 1, '网站管理员', NULL, NULL, 1528614186, 1528614186);

INSERT INTO `auth_item_child`
VALUES ('superAdmin', 'manageAdmin'), ('webAdmin', 'manageAdmin'), ('superAdmin', 'managePermission'),
  ('roomAdmin', 'manageRoom'), ('superAdmin', 'manageRoom'), ('studentAdmin', 'manageStudent'),
  ('superAdmin', 'manageStudent'), ('superAdmin', 'viewAdminList'), ('webAdmin', 'viewAdminList'),
  ('roomAdmin', 'viewStudentList'), ('studentAdmin', 'viewStudentList'), ('superAdmin', 'viewStudentList');

INSERT INTO `auth_assignment`
VALUES ('superAdmin', '1', 1530690224);