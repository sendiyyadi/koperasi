
CREATE TABLE `sec_users` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `userid` varchar(30) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `disabled` int(4) DEFAULT NULL,
  `passwd` varchar(100) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL COMMENT 'NIP = NIK (NO KTP)',
  `jabatan` varchar(50) DEFAULT NULL,
  `handphone` varchar(20) DEFAULT NULL,
  `level_id` int(4) DEFAULT NULL COMMENT 'level id 1=sa, 2=staff, 3=wp',
  `created_by` varchar(30) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` date DEFAULT NULL,
  `versi` int(10) DEFAULT 1,
  `user_tp` int(11) DEFAULT NULL,
  `user_group` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sec_users_uk` (`userid`),
  KEY `sec_users_idx03` (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `sec_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `app_path` varchar(100) DEFAULT NULL,
  `disabled` int(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sec_apps_pk` (`id`),
  UNIQUE KEY `sec_apps_uk1` (`nama`),
  UNIQUE KEY `sec_apps_uk2` (`app_path`),
  KEY `nama` (`nama`),
  KEY `app_path` (`app_path`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `sec_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `locked` int(4) DEFAULT 0,
  `kode` varchar(50) DEFAULT NULL,
  `app_id` int(14) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sec_groups_pk` (`id`),
  UNIQUE KEY `sec_groups_uk` (`nama`),
  UNIQUE KEY `sec_groups_uk1` (`kode`),
  UNIQUE KEY `sec_groups_uk2` (`nama`),
  UNIQUE KEY `sec_groups_uk3` (`kode`),
  KEY `sec_groups_idx03` (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `sec_modules` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `app_id` int(14) DEFAULT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `tp_modul` varchar(1) DEFAULT NULL COMMENT 'm=menu utama s=sub menu  t=transaski/proses',
  `doch_id` int(14) DEFAULT NULL COMMENT 'ref. header menu/sub menu',
  `parent_kd` varchar(50) DEFAULT '0' COMMENT 'kode parent menu',
  `no_urut` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sec_modules_uk` (`nama`,`app_id`),
  KEY `sec_modules_idx03` (`app_id`,`tp_modul`),
  KEY `sec_modules_idx04` (`app_id`,`doch_id`),
  KEY `sec_modules_idx05` (`app_id`,`parent_kd`),
  KEY `sec_modules_idx06` (`parent_kd`),
  CONSTRAINT `sec_modules_fk` FOREIGN KEY (`app_id`) REFERENCES `sec_apps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `sec_user_groups` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `user_id` int(14) DEFAULT NULL,
  `group_id` int(14) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sec_user_groups_uk` (`user_id`,`group_id`),
  KEY `sec_user_groups_fk1` (`group_id`),
  CONSTRAINT `sec_user_groups_fk1` FOREIGN KEY (`group_id`) REFERENCES `sec_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sec_user_groups_fk2` FOREIGN KEY (`user_id`) REFERENCES `sec_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `sec_group_modules` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `group_id` int(14) NOT NULL,
  `module_id` int(14) NOT NULL,
  `reads` int(4) NOT NULL DEFAULT 0,
  `writes` int(4) NOT NULL DEFAULT 0,
  `deletes` int(4) NOT NULL DEFAULT 0,
  `inserts` int(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sec_group_modules_uk` (`group_id`,`module_id`),
  KEY `sec_group_modules_idx01` (`group_id`),
  KEY `sec_group_modules_idx02` (`module_id`),
  CONSTRAINT `sec_group_modules_fk1` FOREIGN KEY (`module_id`) REFERENCES `sec_modules` (`id`),
  CONSTRAINT `sec_group_modules_fk2` FOREIGN KEY (`group_id`) REFERENCES `sec_groups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `sec_modules_btn` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `nama_btn` varchar(50) DEFAULT NULL,
  `module_id` int(14) DEFAULT NULL,
  `kode_btn` varchar(20) DEFAULT NULL,
  `btn_no` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sec_modules_btn_uk` (`nama_btn`,`module_id`),
  UNIQUE KEY `sec_modules_btn_uk1` (`module_id`,`btn_no`),
  UNIQUE KEY `sec_modules_btn_uk2` (`module_id`,`kode_btn`),
  KEY `sec_modules_btn_idx01` (`module_id`),
  CONSTRAINT `sec_modules_btn_fk` FOREIGN KEY (`module_id`) REFERENCES `sec_modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `sec_group_roles_btn` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `group_id` int(14) DEFAULT NULL,
  `modules_id` int(14) DEFAULT NULL,
  `modules_btn_id` int(14) DEFAULT NULL,
  `flg_button` int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sec_group_roles_btn_idx01` (`group_id`),
  KEY `sec_group_roles_btn_idx02` (`modules_id`),
  KEY `sec_group_roles_btn_idx03` (`modules_btn_id`),
  CONSTRAINT `sec_group_roles_btn_fk1` FOREIGN KEY (`group_id`) REFERENCES `sec_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sec_group_roles_btn_fk2` FOREIGN KEY (`modules_id`) REFERENCES `sec_modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sec_group_roles_btn_fk3` FOREIGN KEY (`modules_btn_id`) REFERENCES `sec_modules_btn` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


----------------------------------------------------------------------------------------------------
---- PROCEDURE 
CREATE PROCEDURE `sikoperasi`.`getlevel_v2`(
	IN `cat_id` INT,
	OUT `level` TEXT
)
begin
	DECLARE catname VARCHAR(50);
    DECLARE templevel INT;
    DECLARE tempparent INT;
   	SET templevel = 1;
    SET max_sp_recursion_depth = 255;
    SELECT kode, doch_id FROM sec_modules WHERE id=cat_id INTO catname, tempparent;
    IF tempparent IS NULL
    THEN
        SET level = templevel;
    ELSE
        CALL getlevel_v2(tempparent, templevel);
        SET level = templevel+1;
    END IF;
END

----------------

CREATE PROCEDURE `sikoperasi`.`getpath_v2`(
	IN `cat_id` INT,
	OUT `path` TEXT
)
begin
	DECLARE catname VARCHAR(50);
    DECLARE temppath TEXT;
    DECLARE tempparent INT;
    SET max_sp_recursion_depth = 255;
    SELECT kode, doch_id FROM sec_modules WHERE id=cat_id INTO catname, tempparent;
    IF tempparent IS NULL
    THEN
        SET path = catname;
    ELSE
        CALL getpath_v2(tempparent, temppath);
        SET path = CONCAT(temppath, '->', catname);
    END IF;
END

----------------

CREATE FUNCTION `sikoperasi`.`fgetpath2`(
	`cat_id` INT
) RETURNS text CHARSET latin1
    DETERMINISTIC
BEGIN
    DECLARE res TEXT;
    CALL getpath_v2(cat_id, res);
    RETURN res;
END

CREATE FUNCTION `sikoperasi`.`fgetlevel2`(
	`cat_id` INT
) RETURNS text CHARSET latin1
    DETERMINISTIC
BEGIN
    DECLARE res TEXT;
    CALL getlevel_v2(cat_id, res);
    RETURN res;
end

----------------
---- view 
CREATE VIEW v_sec_modules_path AS
select
    `sec_modules`.`id` as `id`,
    `sec_modules`.`nama` as `nama`,
    `sec_modules`.`app_id` as `app_id`,
    `sec_modules`.`kode` as `kode`,
    `sec_modules`.`tp_modul` as `tp_modul`,
    `sec_modules`.`doch_id` as `doch_id`,
    `sec_modules`.`parent_kd` as `parent_kd`,
    `sec_modules`.`no_urut` as `no_urut`,
    `fgetpath2`(`sec_modules`.`id`) as `path_menu`,
    `fgetlevel2`(`sec_modules`.`id`) as `path_level`
from
    `sec_modules`
order by
    `fgetlevel2`(`sec_modules`.`id`) 



----------------------------------------------------------------------------------------------------
---- table referensi 
CREATE TABLE `ref_instansi` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `kode` varchar(10) DEFAULT NULL,
  `alamat` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `ref_bidang` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `id_instansi` int(14) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `kode` varchar(10) DEFAULT NULL,
  `alamat` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `ref_jabatan` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) DEFAULT NULL,
  `nama_jabatan` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `ref_simpanan_wapok` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `id_jabatan` int(14) NOT NULL,
  `simpanan_wajib` int(14) NOT NULL,
  `simpanan_pokok` int(14) NOT NULL,
  `tgl_mulai` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

