/*
 Navicat Premium Data Transfer

 Source Server         : ali
 Source Server Type    : PostgreSQL
 Source Server Version : 130003
 Source Host           : localhost:5432
 Source Catalog        : tms_app
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 130003
 File Encoding         : 65001

 Date: 20/07/2023 22:40:03
*/


-- ----------------------------
-- Sequence structure for failed_jobs_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."failed_jobs_id_seq";
CREATE SEQUENCE "public"."failed_jobs_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for jobs_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."jobs_id_seq";
CREATE SEQUENCE "public"."jobs_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for migrations_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."migrations_id_seq";
CREATE SEQUENCE "public"."migrations_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Table structure for exportProcesses
-- ----------------------------
DROP TABLE IF EXISTS "public"."exportProcesses";
CREATE TABLE "public"."exportProcesses" (
  "id" uuid NOT NULL,
  "export" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "fileName" varchar(250) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Records of exportProcesses
-- ----------------------------
INSERT INTO "public"."exportProcesses" VALUES ('e8b9217a-b3f8-4887-b028-e09e41a51e4f', 'export', 'export-924770f26b4686596fd98fbc39e7a3a6.xlsx', 'ON PROGRESS', '2023-07-20 18:20:16', '2023-07-20 18:20:16');
INSERT INTO "public"."exportProcesses" VALUES ('07282f2a-a7e4-4d03-8f40-669a0b26e489', 'export', 'export-e51f78f971cdf04edf113d00caae187a.xlsx', 'ON PROGRESS', '2023-07-20 18:22:10', '2023-07-20 18:22:10');
INSERT INTO "public"."exportProcesses" VALUES ('12d41e11-a1c1-46f5-b74f-290a0df1f9d5', 'export', 'export-8389dc6d2ebbdd711211072e000916d6.xlsx', 'ON PROGRESS', '2023-07-20 18:25:02', '2023-07-20 18:25:02');
INSERT INTO "public"."exportProcesses" VALUES ('53a9a5a1-9a32-462b-8b7a-e07781d9ea7b', 'export', 'export-11f49a3ac7974b211622cf32e9b9ddb2.xlsx', 'ON PROGRESS', '2023-07-20 18:28:38', '2023-07-20 18:28:38');
INSERT INTO "public"."exportProcesses" VALUES ('005ede81-4ca5-4663-80f1-1a9d12e4ad37', 'export', 'export-abf96582c2d6faa4e41f73c60bfd1e04.xlsx', 'ON PROGRESS', '2023-07-20 18:32:10', '2023-07-20 18:32:10');
INSERT INTO "public"."exportProcesses" VALUES ('a9a3fe34-11f3-445c-ae91-44ca3a417238', 'export', 'export-36bc233933ad1a1e928a7b9c8c1484a0.xlsx', 'ON PROGRESS', '2023-07-20 18:34:17', '2023-07-20 18:34:17');
INSERT INTO "public"."exportProcesses" VALUES ('96e327cf-e3c1-43e6-a10b-472d30271893', 'export', 'export-184f88738bc8655d8106fda5c92c662b.xlsx', 'ON PROGRESS', '2023-07-20 19:13:34', '2023-07-20 19:13:34');
INSERT INTO "public"."exportProcesses" VALUES ('ddeb0c17-5817-4f22-b3ce-a0ba8733407b', 'export', 'export-c274576d34514a2d557aff67ce900d61.xlsx', 'ON PROGRESS', '2023-07-20 19:16:47', '2023-07-20 19:16:47');
INSERT INTO "public"."exportProcesses" VALUES ('975258c4-9689-4638-9702-7a5303d90d53', 'export', 'export-a80706f6ce995d8584d6fe3b206eb05d.xlsx', 'ON PROGRESS', '2023-07-20 19:18:36', '2023-07-20 19:18:36');
INSERT INTO "public"."exportProcesses" VALUES ('25b5e58c-3df1-4d76-bbbb-313f9561e425', 'export', 'export-b5800d510556a70201060254a41f6a43.xlsx', 'ON PROGRESS', '2023-07-20 19:19:49', '2023-07-20 19:19:49');
INSERT INTO "public"."exportProcesses" VALUES ('22c615a1-9371-4c57-bba6-7d34e225a339', 'export', 'export-0abc76ea12cf4854fe497e830d58e1d7.xlsx', 'ON PROGRESS', '2023-07-20 19:21:04', '2023-07-20 19:21:04');
INSERT INTO "public"."exportProcesses" VALUES ('eb68f719-ef4d-4a7e-abbd-a54986f069c3', 'export', 'export-8db8d20deff7f8c1c1235ec9db43ef3f.xlsx', 'ON PROGRESS', '2023-07-20 19:21:41', '2023-07-20 19:21:41');
INSERT INTO "public"."exportProcesses" VALUES ('c16ce0ae-c749-4f6e-9fcd-592de355743a', 'export', 'export-b653d43922ee77ffc635b6e837866ae8.xlsx', 'ON PROGRESS', '2023-07-20 19:21:59', '2023-07-20 19:21:59');
INSERT INTO "public"."exportProcesses" VALUES ('3136233f-bdea-4b42-93ef-4dbac224fb38', 'export', 'export-5997cfd2000c7857a439e4650157b4f6.xlsx', 'ON PROGRESS', '2023-07-20 19:26:58', '2023-07-20 19:26:58');
INSERT INTO "public"."exportProcesses" VALUES ('2c4c49e2-466e-43ca-9f66-b99d12909fba', 'export', 'export-1f2d215efe3b3c5ac333a565165184ae.xlsx', 'ON PROGRESS', '2023-07-20 19:27:21', '2023-07-20 19:27:21');
INSERT INTO "public"."exportProcesses" VALUES ('4f3d1a6b-9a73-4ab2-8c1a-b6c87e535820', 'export', 'export-24a12e899733a26a3f4677caa3d37a81.xlsx', 'DONE', '2023-07-20 19:30:26', '2023-07-20 19:30:28');
INSERT INTO "public"."exportProcesses" VALUES ('b1265a68-45a4-473f-81ea-ad9e1540be2a', 'export', 'export-3c25b7d21dd692002273a0a9b8f643d6.xlsx', 'DONE', '2023-07-20 19:30:41', '2023-07-20 19:30:44');
INSERT INTO "public"."exportProcesses" VALUES ('70c90669-fc55-40fb-ad6e-b938cdb6972a', 'export', 'export-ca0be39dc0eaa98da1561a59fa050114.xlsx', 'DONE', '2023-07-20 19:32:35', '2023-07-20 19:32:38');
INSERT INTO "public"."exportProcesses" VALUES ('34258f59-ece4-465e-ac0f-ef9023c8ac0f', 'export', 'export-25079d67cd5672e79bb184d7318aa4fb.xlsx', 'DONE', '2023-07-20 19:33:32', '2023-07-20 19:33:35');
INSERT INTO "public"."exportProcesses" VALUES ('9cfa0ed0-6b52-4b4e-aace-ef5f052c580a', 'export', 'export-d7a39506d045782701474a144298fc02.xlsx', 'DONE', '2023-07-20 19:45:33', '2023-07-20 19:45:35');
INSERT INTO "public"."exportProcesses" VALUES ('78a30522-c25f-4efa-ab27-398a5dd792d5', 'export', 'export-caa12c6374e0b0faea16c77d8566b732.xlsx', 'DONE', '2023-07-20 19:45:52', '2023-07-20 19:45:53');
INSERT INTO "public"."exportProcesses" VALUES ('a7b8382f-9096-49c1-b220-6f7e5f99b178', 'export', 'export-c60a431dcc8d8e575a4ea198d5f228a5.xlsx', 'ON PROGRESS', '2023-07-20 19:48:02', '2023-07-20 19:48:02');
INSERT INTO "public"."exportProcesses" VALUES ('bd851939-f568-4f52-bbf2-e85066db823b', 'export', 'export-fb8e08f8dfe2f458a32f4ccd3f22219a.xlsx', 'ON PROGRESS', '2023-07-20 19:48:24', '2023-07-20 19:48:24');
INSERT INTO "public"."exportProcesses" VALUES ('3e8d69c8-7bfc-4fe9-b602-b5cc52a5c63d', 'export', 'export-867861aa9e7f4451bf2c0638a16de1b7.xlsx', 'ON PROGRESS', '2023-07-20 19:49:01', '2023-07-20 19:49:01');
INSERT INTO "public"."exportProcesses" VALUES ('b56ce2f0-6eaf-4173-96ca-f8301196c8a3', 'export', 'export-20ec4a63449bdd99b10813ecc9b9d4e3.xlsx', 'ON PROGRESS', '2023-07-20 19:49:20', '2023-07-20 19:49:20');
INSERT INTO "public"."exportProcesses" VALUES ('be3c7402-1ef0-47e4-a48b-8c7742e77bb3', 'export', 'export-2f2cd523e229974b62702b6947934055.xlsx', 'ON PROGRESS', '2023-07-20 19:50:06', '2023-07-20 19:50:06');
INSERT INTO "public"."exportProcesses" VALUES ('dc761a7b-7c20-4159-82a7-dc02acc1c1d8', 'export', 'export-8249d04eefc4014e1c556ffe71109e09.xlsx', 'ON PROGRESS', '2023-07-20 19:54:41', '2023-07-20 19:54:41');
INSERT INTO "public"."exportProcesses" VALUES ('5519e767-7c40-4b41-8614-5fdd77d0eb88', 'export', 'export-f3449d3100be49ee2ffeb336e2658926.xlsx', 'ON PROGRESS', '2023-07-20 20:19:50', '2023-07-20 20:19:50');
INSERT INTO "public"."exportProcesses" VALUES ('d8f15e25-b1f8-4385-8ebe-cf33791c77c5', 'export', 'export-ca920250bb83ab50cb49f958279a89ad.xlsx', 'ON PROGRESS', '2023-07-20 14:31:31', '2023-07-20 14:31:31');
INSERT INTO "public"."exportProcesses" VALUES ('c2465dae-0d7a-44b6-bb2a-7764d15ae415', 'export', 'export-edb756b377579a6472daa6b22b7e3aef.xlsx', 'ON PROGRESS', '2023-07-20 14:32:17', '2023-07-20 14:32:17');
INSERT INTO "public"."exportProcesses" VALUES ('e6fb1737-0665-47f5-892e-52d858c93e49', 'export', 'export-6d1e201f6c95bea23ed03d89072015a2.xlsx', 'DONE', '2023-07-20 14:37:09', '2023-07-20 14:37:09');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS "public"."failed_jobs";
CREATE TABLE "public"."failed_jobs" (
  "id" int8 NOT NULL DEFAULT nextval('failed_jobs_id_seq'::regclass),
  "uuid" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "connection" text COLLATE "pg_catalog"."default" NOT NULL,
  "queue" text COLLATE "pg_catalog"."default" NOT NULL,
  "payload" text COLLATE "pg_catalog"."default" NOT NULL,
  "exception" text COLLATE "pg_catalog"."default" NOT NULL,
  "failed_at" timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS "public"."jobs";
CREATE TABLE "public"."jobs" (
  "id" int8 NOT NULL DEFAULT nextval('jobs_id_seq'::regclass),
  "queue" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "payload" text COLLATE "pg_catalog"."default" NOT NULL,
  "attempts" int2 NOT NULL,
  "reserved_at" int4,
  "available_at" int4 NOT NULL,
  "created_at" int4 NOT NULL
)
;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS "public"."migrations";
CREATE TABLE "public"."migrations" (
  "id" int4 NOT NULL DEFAULT nextval('migrations_id_seq'::regclass),
  "migration" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "batch" int4 NOT NULL
)
;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO "public"."migrations" VALUES (1, '2023_07_20_144950_create_jobs_table', 1);
INSERT INTO "public"."migrations" VALUES (2, '2023_07_20_145550_create_failed_jobs_table', 2);
INSERT INTO "public"."migrations" VALUES (3, '2023_07_20_171508_create_exportprocesses_table', 3);

-- ----------------------------
-- Table structure for tes
-- ----------------------------
DROP TABLE IF EXISTS "public"."tes";
CREATE TABLE "public"."tes" (
  "id" uuid NOT NULL,
  "url" text COLLATE "pg_catalog"."default",
  "path" text COLLATE "pg_catalog"."default",
  "create_ts" timestamp(6),
  "update_ts" timestamp(6),
  "created_by" varchar(255) COLLATE "pg_catalog"."default",
  "updated_by" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Records of tes
-- ----------------------------
INSERT INTO "public"."tes" VALUES ('61a82119-c930-40ac-b798-fd6897a36796', 'http://127.0.0.1:9000/my.bucket/files/UmthqEDmfVTQQQgFokgaUVtuUshpHL4EC3RsyPzV.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=WK2gApTGRQDSmLWrZpKy%2F20230716%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20230716T224037Z&X-Amz-SignedHeaders=host&X-Amz-Expires=60&X-Amz-Signature=c482fea1634c3ca134ba51baeb93d110e11f5164f54d58af029ecf5a957d3691', 'files/UmthqEDmfVTQQQgFokgaUVtuUshpHL4EC3RsyPzV.png', '2023-07-17 05:40:38', '2023-07-17 05:40:38', '1', '');
INSERT INTO "public"."tes" VALUES ('029935bd-a861-4a4e-b21b-63c1492e7fd7', 'http://127.0.0.1:9000/my.bucket/files/aYdkkVYq08Hw9KfhAYEAyK8e4lNC1cMGjr7Jd6ij.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=WK2gApTGRQDSmLWrZpKy%2F20230716%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20230716T230033Z&X-Amz-SignedHeaders=host&X-Amz-Expires=60&X-Amz-Signature=3ffeaec88dfc0bc08f4771e425128740e27ef3bbd717a58671882fdf0467e210', 'files/aYdkkVYq08Hw9KfhAYEAyK8e4lNC1cMGjr7Jd6ij.png', '2023-07-17 06:00:33', '2023-07-17 06:00:33', '1', '');
INSERT INTO "public"."tes" VALUES ('55d9d877-7287-4a37-ac3d-99200ad8012d', 'http://127.0.0.1:9000/my.bucket/files/e8jSjHCUS1UsmrG1gSdGkygARl6J2my0t22HHaeH.txt?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=WK2gApTGRQDSmLWrZpKy%2F20230716%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20230716T230301Z&X-Amz-SignedHeaders=host&X-Amz-Expires=60&X-Amz-Signature=055570bf21bf27e346d20a876615aba75c2f6d707c212473a20cbfc049da9123', 'files/e8jSjHCUS1UsmrG1gSdGkygARl6J2my0t22HHaeH.txt', '2023-07-17 06:03:01', '2023-07-17 06:03:01', '1', '');

-- ----------------------------
-- Table structure for tms_application
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_application";
CREATE TABLE "public"."tms_application" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "package_name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "description" varchar(255) COLLATE "pg_catalog"."default",
  "app_version" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "uninstallable" bool,
  "company_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "checksum" varchar(32) COLLATE "pg_catalog"."default" NOT NULL,
  "unique_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "unique_icon_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "tenant_id" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "icon_url" varchar(255) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_application
-- ----------------------------
INSERT INTO "public"."tms_application" VALUES ('f0e0ab21-a856-4908-95a5-b437f9b40fa8', 2, '2023-07-18 19:23:19', '1', '2023-07-18 19:49:59', '1', '2023-07-18 19:49:59', 'admin', 'tes2', 'tes2', 'Kaprok', 'kapr', 'f', 'adfa', '0', 'er', 'kap', 'qualita', 'files/IfjtITwP5JvRH3NDLAxE9NBCDNY47F6imaFSWXev.png');
INSERT INTO "public"."tms_application" VALUES ('21334823-683f-4c9a-8693-8717137ed123', 1, '2023-07-18 20:02:57', '1', '2023-07-18 20:02:57', '', NULL, NULL, 'tes', 'tes', 'tes', 'tes', 't', 'tes', 'tes', 'tes', 'tes', 'tripmitra', 'files/RoRLzgTBR8FGq97eAAurD9lWhfclmxt6LykS1E4r.png');

-- ----------------------------
-- Table structure for tms_city
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_city";
CREATE TABLE "public"."tms_city" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "states_id" uuid NOT NULL,
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_city
-- ----------------------------
INSERT INTO "public"."tms_city" VALUES ('79f0fe85-600f-4de7-abfa-732196595764', 1, '2023-07-11 08:32:21', '1', '2023-07-11 08:32:21', '', NULL, NULL, '5e2a235c-c91c-44fb-bddf-0ed04f93e1e5', 'Kota Serang');
INSERT INTO "public"."tms_city" VALUES ('90c56c9d-acb0-41a2-a055-a82905416598', 3, '2023-07-11 08:26:31', '1', '2023-07-11 08:33:52', '1', NULL, NULL, '5e2a235c-c91c-44fb-bddf-0ed04f93e1e5', 'Kab. Tangerang');
INSERT INTO "public"."tms_city" VALUES ('5ef0b3b5-7adc-43d7-bd6f-11d2dcadd6b7', 1, '2023-07-11 09:16:30', '1', '2023-07-11 09:16:30', '', NULL, NULL, '5e2a235c-c91c-44fb-bddf-0ed04f93e1e5', 'Pandeglang');
INSERT INTO "public"."tms_city" VALUES ('862d8d5f-d277-40a1-bfdf-38531d01b829', 1, '2023-07-11 08:29:29', '1', '2023-07-11 09:20:13', '1', '2023-07-11 09:20:13', 'admin', '5e2a235c-c91c-44fb-bddf-0ed04f93e1e5', 'Tangerang Selatan');
INSERT INTO "public"."tms_city" VALUES ('3fbb2895-9973-4328-bdfc-9984431ab640', 1, '2023-07-11 09:31:51', '1', '2023-07-11 09:31:51', '', NULL, NULL, '5e2a235c-c91c-44fb-bddf-0ed04f93e1e5', 'Kab. Serang');
INSERT INTO "public"."tms_city" VALUES ('9a893d72-6b53-4519-9ede-a303c0fd9775', 2, '2023-07-14 17:19:03', '1', '2023-07-14 17:24:19', '1', '2023-07-14 17:24:19', 'admin', '5e2a235c-c91c-44fb-bddf-0ed04f93e1e5', 'Kota Tanger');

-- ----------------------------
-- Table structure for tms_country
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_country";
CREATE TABLE "public"."tms_country" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "code" varchar(2) COLLATE "pg_catalog"."default" NOT NULL,
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_country
-- ----------------------------
INSERT INTO "public"."tms_country" VALUES ('71dc0b72-7c75-47ad-909c-2ae9ef0c9cc6', 1, '2023-07-09 20:42:23', '1', '2023-07-09 20:42:23', '', NULL, NULL, 'AB', 'tes2');
INSERT INTO "public"."tms_country" VALUES ('aff72532-3063-4044-9c8a-a13622d97d66', 1, '2023-07-09 21:36:46', '1', '2023-07-09 21:36:46', '', NULL, NULL, 'AK', 'tes');
INSERT INTO "public"."tms_country" VALUES ('98d7a972-e4da-400f-8890-5f7f454e70ba', 2, '2023-07-09 21:36:59', '1', '2023-07-09 21:41:41', '1', NULL, NULL, 'AU', 't2');
INSERT INTO "public"."tms_country" VALUES ('022c1aca-96d6-4843-8ee5-2537097022f2', 2, '2023-07-09 21:42:23', '1', '2023-07-09 21:42:23', '', NULL, NULL, 'AT', 'tyy');
INSERT INTO "public"."tms_country" VALUES ('0278b50d-ffd1-43ca-8046-d551a621d658', 8, '2023-07-09 21:45:13', '1', '2023-07-09 21:55:47', '1', '2023-07-09 21:55:47', '1', 'PO', 't9syy');
INSERT INTO "public"."tms_country" VALUES ('89585139-6512-4e1e-bd72-2cd2b46d75c0', 1, '2023-07-10 08:48:51', '1', '2023-07-10 08:48:51', '', NULL, NULL, 'AC', 'UK');
INSERT INTO "public"."tms_country" VALUES ('fd58e10f-6026-4c73-a711-ce04da99ced9', 3, '2023-07-10 14:35:12', '1', '2023-07-10 15:07:21', '1', '2023-07-10 15:07:21', 'admin', 'ID', 'Indonesia');
INSERT INTO "public"."tms_country" VALUES ('b663d9d6-11a9-4118-bd55-bfcf0735e47a', 1, '2023-07-14 16:36:01', '1', '2023-07-14 16:36:01', '', NULL, NULL, 'IA', 'India');
INSERT INTO "public"."tms_country" VALUES ('4302b734-c328-49ae-bf5a-7d6bf1cdfa9f', 2, '2023-07-14 16:37:05', '1', '2023-07-14 16:43:03', '1', '2023-07-14 16:43:03', 'admin', 'Ph', 'Pilipina');
INSERT INTO "public"."tms_country" VALUES ('7186d1eb-569c-41d7-af1a-15f35df73a03', 1, '2023-07-16 14:56:26', '1', '2023-07-16 14:56:26', '', NULL, NULL, 'JP', 'Jepang');
INSERT INTO "public"."tms_country" VALUES ('d4d4b154-2078-4b31-b5d6-7770cb75ece1', 1, '2023-07-16 15:08:33', '1', '2023-07-16 15:08:33', '', NULL, NULL, 'CN', 'CINA');
INSERT INTO "public"."tms_country" VALUES ('1d6b2eb3-6c6d-4679-9d4a-7c3c109f7f7f', 1, '2023-07-16 15:22:13', '1', '2023-07-16 15:22:13', '', NULL, NULL, 'KR', 'COREA');
INSERT INTO "public"."tms_country" VALUES ('b8792654-ebf1-4bea-947d-d0a5cfe10e55', 1, '2023-07-16 15:23:24', '1', '2023-07-16 15:23:24', '', NULL, NULL, 'UT', 'KORUT');
INSERT INTO "public"."tms_country" VALUES ('892ae8ec-1d05-40f7-aff2-e0859227abbd', 1, '2023-07-16 15:25:50', '1', '2023-07-16 15:25:50', '', NULL, NULL, 'UB', 'UZBEKSITAN');
INSERT INTO "public"."tms_country" VALUES ('e18e0ad9-4ec6-4b65-ad3e-cd79874d33a4', 1, '2023-07-17 22:57:03', '1', '2023-07-17 22:57:03', '', NULL, NULL, 'TN', 'KURDISTAN');

-- ----------------------------
-- Table structure for tms_delete_task
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_delete_task";
CREATE TABLE "public"."tms_delete_task" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "status" int4 NOT NULL,
  "delete_time" timestamp(6),
  "old_status" int4,
  "tenant_id" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_delete_task
-- ----------------------------

-- ----------------------------
-- Table structure for tms_delete_task_app
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_delete_task_app";
CREATE TABLE "public"."tms_delete_task_app" (
  "id" uuid NOT NULL,
  "app_name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "package_name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "app_version" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "task_id" uuid NOT NULL
)
;

-- ----------------------------
-- Records of tms_delete_task_app
-- ----------------------------

-- ----------------------------
-- Table structure for tms_delete_task_log
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_delete_task_log";
CREATE TABLE "public"."tms_delete_task_log" (
  "id" uuid NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "version" int4 NOT NULL,
  "task_id" uuid NOT NULL,
  "app_id" uuid NOT NULL,
  "terminal_id" uuid NOT NULL,
  "activity" int4 NOT NULL,
  "last_broadcast_ts" timestamp(6),
  "old_activity" int4,
  "message" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Records of tms_delete_task_log
-- ----------------------------

-- ----------------------------
-- Table structure for tms_delete_task_terminal_group_link
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_delete_task_terminal_group_link";
CREATE TABLE "public"."tms_delete_task_terminal_group_link" (
  "delete_task_id" uuid NOT NULL,
  "group_id" uuid NOT NULL
)
;

-- ----------------------------
-- Records of tms_delete_task_terminal_group_link
-- ----------------------------

-- ----------------------------
-- Table structure for tms_delete_task_terminal_link
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_delete_task_terminal_link";
CREATE TABLE "public"."tms_delete_task_terminal_link" (
  "terminal_id" uuid NOT NULL,
  "delete_task_id" uuid NOT NULL
)
;

-- ----------------------------
-- Records of tms_delete_task_terminal_link
-- ----------------------------

-- ----------------------------
-- Table structure for tms_device_model
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_device_model";
CREATE TABLE "public"."tms_device_model" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "model" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "model_information" text COLLATE "pg_catalog"."default",
  "vendor_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "vendor_country" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_device_model
-- ----------------------------
INSERT INTO "public"."tms_device_model" VALUES ('790789b0-f6a9-45de-a04a-47e58a0ddc92', 1, '2023-07-11 18:23:14', '1', '2023-07-11 18:23:14', '', NULL, NULL, 'tes', 'mi', 'aya', 'ts');
INSERT INTO "public"."tms_device_model" VALUES ('70095ae7-c09f-4712-bb54-9a1f80ef0e25', 2, '2023-07-11 18:26:15', '1', '2023-07-11 18:38:01', '1', '2023-07-11 18:38:01', 'admin', 'tes2 sd', 'mi2 erere', 'aya2asd', 'ts2sda');

-- ----------------------------
-- Table structure for tms_device_profile
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_device_profile";
CREATE TABLE "public"."tms_device_profile" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "heartbeat_interval" int4 NOT NULL,
  "diagnostic_interval" int4 NOT NULL,
  "mask_home_button" bool NOT NULL,
  "mask_status_bar" bool NOT NULL,
  "schedule_reboot" bool NOT NULL,
  "schedule_reboot_time" time(6),
  "is_default" bool,
  "relocation_alert" bool,
  "moving_threshold" int4,
  "admin_password" varchar(8) COLLATE "pg_catalog"."default",
  "front_app" varchar(255) COLLATE "pg_catalog"."default",
  "tenant_id" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_device_profile
-- ----------------------------
INSERT INTO "public"."tms_device_profile" VALUES ('a3ed13f2-2065-4fb7-b3a8-f2e35f10b29c', 1, '2023-07-11 20:58:15', '1', '2023-07-11 20:58:15', '', NULL, NULL, 'tes', 1, 2, 't', 't', 't', '03:30:20', 't', 't', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('751e09d0-07b0-43e1-b24d-e2a5ef4adf01', 1, '2023-07-11 20:59:28', '1', '2023-07-11 20:59:28', '', NULL, NULL, 'tes2', 1, 2, 't', 't', 't', '03:30:20', 't', 'f', NULL, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('bbbfc1f7-5201-4a92-9f56-37c3efd663cf', 1, '2023-07-11 20:59:44', '1', '2023-07-11 20:59:44', '', NULL, NULL, 'tes3', 1, 2, 't', 't', 't', '03:30:20', 't', NULL, NULL, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('5a7a5881-c042-4531-a1da-d227d0ee69da', 1, '2023-07-11 21:01:53', '1', '2023-07-11 21:01:53', '', NULL, NULL, 'tes4', 1, 2, 't', 't', 't', '03:30:20', 't', NULL, NULL, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('1bb4d411-6812-4d6b-87bd-0ab03dc9eea8', 1, '2023-07-11 21:11:13', '1', '2023-07-11 21:11:13', '', NULL, NULL, 'tes521', 1, 2, 't', 't', 't', '03:30:20', 't', NULL, NULL, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('940de612-c364-4ae5-8c39-c45d697bdf29', 1, '2023-07-11 21:11:48', '1', '2023-07-11 21:11:48', '', NULL, NULL, 'test8521', 1, 2, 't', 't', 't', '03:30:20', 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('f40d78cd-4876-42f2-b92d-948e86f3ea0f', 1, '2023-07-11 21:12:22', '1', '2023-07-11 21:12:22', '', NULL, NULL, 'test85021', 1, 2, 't', 't', 't', '03:30:20', 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('c216bb39-3607-469c-a06f-e60286e95df8', 1, '2023-07-11 21:16:38', '1', '2023-07-11 21:16:38', '', NULL, NULL, 'tss', 1, 2, 't', 't', 't', '03:30:20', 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('de359d42-f6eb-440e-9300-e7098d6088b1', 1, '2023-07-11 21:16:52', '1', '2023-07-11 21:16:52', '', NULL, NULL, 'tsss', 1, 2, 't', 't', 't', '03:30:20', 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('1c2482ac-6b25-4ad6-a9c0-fc7e80655628', 1, '2023-07-11 21:22:49', '1', '2023-07-11 21:22:49', '', NULL, NULL, 'tsssss', 1, 2, 't', 't', 't', '03:30:20', 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('88aec8d4-a960-4abe-a465-810b2b128203', 1, '2023-07-11 21:23:32', '1', '2023-07-11 21:23:32', '', NULL, NULL, 'tsssssds', 1, 2, 't', 't', 'f', '03:30:20', 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('8db944c4-4187-4a31-8fd8-1e6438bf38b0', 1, '2023-07-11 21:36:55', '1', '2023-07-11 21:36:55', '', NULL, NULL, 'tsssssdsss', 1, 2, 't', 't', 'f', '03:09:09', 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('a5c6adb5-bcc9-4aa4-a770-882443bbda34', 1, '2023-07-11 21:37:47', '1', '2023-07-11 21:37:47', '', NULL, NULL, 'tsssssdssss', 1, 2, 't', 't', 'f', '03:09:09', 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('6f5656c1-35a3-4474-841c-c292efd9e098', 1, '2023-07-11 21:38:34', '1', '2023-07-11 21:38:34', '', NULL, NULL, 'tsssssdsss0s', 1, 2, 't', 't', 'f', NULL, 't', 'f', 2, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('3c011918-0c84-4aa2-8a27-3e9236f9dcc0', 1, '2023-07-11 21:42:48', '1', '2023-07-11 21:42:48', '', NULL, NULL, 'tsssssdssdsss0s', 1, 2, 't', 't', 't', '03:30:20', 't', 't', NULL, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('f20a7042-e0fe-4434-ad1f-b11d2ff0d65e', 1, '2023-07-11 21:43:14', '1', '2023-07-11 21:43:14', '', NULL, NULL, 'tsssssdssdssss0s', 1, 2, 't', 't', 't', '03:30:20', 't', 't', NULL, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('6bfe7a2c-b839-427e-b6bb-adde4b7f4e84', 1, '2023-07-11 21:43:56', '1', '2023-07-11 21:43:56', '', NULL, NULL, 'tsssssdsssdsdsssss0s', 1, 2, 't', 't', 't', '03:30:20', 't', 't', 1, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('29df5a1e-d4a7-4fc1-8c0b-2e21582c1060', 3, '2023-07-11 21:50:53', '1', '2023-07-11 23:12:58', '1', NULL, NULL, 'm ali romli', 1, 2, 't', 't', 'f', NULL, 't', 'f', NULL, 'password', 'front_app', 'tripmitra');
INSERT INTO "public"."tms_device_profile" VALUES ('59a95bfd-4b47-47ad-a43d-fc76cf0de1eb', 1, '2023-07-11 21:45:26', '1', '2023-07-11 23:15:45', '1', '2023-07-11 23:15:45', 'admin', 'taaa0', 1, 2, 't', 't', 't', '03:30:20', 't', 't', NULL, 'password', 'front_app', 'tripmitra');

-- ----------------------------
-- Table structure for tms_diagnostic_info
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_diagnostic_info";
CREATE TABLE "public"."tms_diagnostic_info" (
  "id" uuid NOT NULL,
  "sn" varchar(30) COLLATE "pg_catalog"."default" NOT NULL,
  "battery_temp" float8 NOT NULL,
  "battery_percentage" int4 NOT NULL,
  "latitude" float8,
  "longitude" float8,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "meid" varchar(20) COLLATE "pg_catalog"."default",
  "switching_times" int4,
  "swiping_card_times" int4,
  "dip_inserting_times" int4,
  "nfc_card_reading_times" int4,
  "front_camera_open_times" int4,
  "rear_camera_open_times" int4,
  "charge_times" int4,
  "version" int4 NOT NULL,
  "total_memory" int8,
  "available_memory" int8,
  "total_flash_memory" int8,
  "available_flash_memory" int8,
  "total_mobile_data" int8,
  "current_boot_time" int4,
  "total_boot_time" int4,
  "installed_apps_string" text COLLATE "pg_catalog"."default",
  "total_length_printed" float8,
  "cell_name" varchar(50) COLLATE "pg_catalog"."default",
  "cell_type" varchar(10) COLLATE "pg_catalog"."default",
  "cell_strength" int4
)
;

-- ----------------------------
-- Records of tms_diagnostic_info
-- ----------------------------

-- ----------------------------
-- Table structure for tms_district
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_district";
CREATE TABLE "public"."tms_district" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "city_id" uuid NOT NULL,
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_district
-- ----------------------------
INSERT INTO "public"."tms_district" VALUES ('451c84a0-8f83-4d6c-bb92-5a5ac6644e94', 1, '2023-07-11 09:24:12', '1', '2023-07-11 09:24:12', '', NULL, NULL, '79f0fe85-600f-4de7-abfa-732196595764', 'Kec. Taktakan');
INSERT INTO "public"."tms_district" VALUES ('0134fe2a-a96f-4d8d-841f-bf25bcc35abe', 1, '2023-07-11 09:24:36', '1', '2023-07-11 09:24:36', '', NULL, NULL, '79f0fe85-600f-4de7-abfa-732196595764', 'Kec. Serang');
INSERT INTO "public"."tms_district" VALUES ('88ed83ad-ca65-41fb-8125-d1f33a0ff441', 1, '2023-07-11 09:24:43', '1', '2023-07-11 09:24:43', '', NULL, NULL, '79f0fe85-600f-4de7-abfa-732196595764', 'Kec. Kasemen');
INSERT INTO "public"."tms_district" VALUES ('bd298959-6ce6-4d42-9181-fb629da00539', 1, '2023-07-11 09:24:53', '1', '2023-07-11 09:24:53', '', NULL, NULL, '79f0fe85-600f-4de7-abfa-732196595764', 'Kec. Cipocok Jaya');
INSERT INTO "public"."tms_district" VALUES ('14ec5400-42a5-406d-a4c6-da8b6afb9f29', 1, '2023-07-11 09:25:05', '1', '2023-07-11 09:25:05', '', NULL, NULL, '79f0fe85-600f-4de7-abfa-732196595764', 'Kec. Sawah Luhur');
INSERT INTO "public"."tms_district" VALUES ('6d244586-6dfd-423d-8bc0-4f0abae9abee', 1, '2023-07-11 09:25:46', '1', '2023-07-11 09:25:46', '', NULL, NULL, '79f0fe85-600f-4de7-abfa-732196595764', 'Kec. Curug');
INSERT INTO "public"."tms_district" VALUES ('035ae558-ac28-45bd-a6f0-c2f5998585be', 2, '2023-07-11 09:25:55', '1', '2023-07-11 09:55:05', '1', '2023-07-11 09:55:05', 'admin', '79f0fe85-600f-4de7-abfa-732196595764', 'Kecamatan Walantaka');

-- ----------------------------
-- Table structure for tms_download_task
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_download_task";
CREATE TABLE "public"."tms_download_task" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "publish_time_type" int4 NOT NULL,
  "publish_time" timestamp(6),
  "download_time_type" int4 NOT NULL,
  "download_time" timestamp(6),
  "installation_time_type" int4 NOT NULL,
  "installation_time" timestamp(6),
  "installation_notification" int4 NOT NULL,
  "status" int4 NOT NULL,
  "old_status" int4,
  "tenant_id" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "download_url" varchar(255) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_download_task
-- ----------------------------

-- ----------------------------
-- Table structure for tms_download_task_application_link
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_download_task_application_link";
CREATE TABLE "public"."tms_download_task_application_link" (
  "download_task_id" uuid NOT NULL,
  "application_id" uuid NOT NULL
)
;

-- ----------------------------
-- Records of tms_download_task_application_link
-- ----------------------------

-- ----------------------------
-- Table structure for tms_download_task_log
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_download_task_log";
CREATE TABLE "public"."tms_download_task_log" (
  "id" uuid NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "version" int4 NOT NULL,
  "task_id" uuid NOT NULL,
  "application_id" uuid NOT NULL,
  "activity" int4 NOT NULL,
  "terminal_id" uuid NOT NULL,
  "last_broadcast_ts" timestamp(6),
  "old_activity" int4,
  "message" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Records of tms_download_task_log
-- ----------------------------

-- ----------------------------
-- Table structure for tms_download_task_terminal_group_link
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_download_task_terminal_group_link";
CREATE TABLE "public"."tms_download_task_terminal_group_link" (
  "download_task_id" uuid NOT NULL,
  "group_id" uuid NOT NULL
)
;

-- ----------------------------
-- Records of tms_download_task_terminal_group_link
-- ----------------------------

-- ----------------------------
-- Table structure for tms_download_task_terminal_link
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_download_task_terminal_link";
CREATE TABLE "public"."tms_download_task_terminal_link" (
  "terminal_id" uuid NOT NULL,
  "download_task_id" uuid NOT NULL
)
;

-- ----------------------------
-- Records of tms_download_task_terminal_link
-- ----------------------------

-- ----------------------------
-- Table structure for tms_heart_beat
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_heart_beat";
CREATE TABLE "public"."tms_heart_beat" (
  "id" uuid NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "sn" varchar(30) COLLATE "pg_catalog"."default" NOT NULL,
  "battery_temp" float8 NOT NULL,
  "battery_percentage" int4 NOT NULL,
  "latitude" float8,
  "longitude" float8,
  "version" int4 NOT NULL,
  "cell_name" varchar(50) COLLATE "pg_catalog"."default",
  "cell_type" varchar(10) COLLATE "pg_catalog"."default",
  "cell_strength" int4
)
;

-- ----------------------------
-- Records of tms_heart_beat
-- ----------------------------

-- ----------------------------
-- Table structure for tms_merchant
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_merchant";
CREATE TABLE "public"."tms_merchant" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "company_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "district_id" uuid NOT NULL,
  "address" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "zipcode" varchar(5) COLLATE "pg_catalog"."default" NOT NULL,
  "type_id" uuid NOT NULL,
  "tenant_id" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_merchant
-- ----------------------------
INSERT INTO "public"."tms_merchant" VALUES ('2b54a885-e9b3-49d3-985e-24a97898d818', 3, '2023-07-11 12:23:07', '1', '2023-07-11 14:54:20', '1', '2023-07-11 14:54:20', 'admin', 'biasa aja', 'te22s', '035ae558-ac28-45bd-a6f0-c2f5998585be', 'tes', '22', 'cf94bb84-ee49-43e9-854d-1022494edb3f', 'tripmitra');

-- ----------------------------
-- Table structure for tms_merchant_type
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_merchant_type";
CREATE TABLE "public"."tms_merchant_type" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "description" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Records of tms_merchant_type
-- ----------------------------
INSERT INTO "public"."tms_merchant_type" VALUES ('27aba154-d52c-490b-bf79-0591a52b1194', 2, '2023-07-11 10:59:34', '1', '2023-07-11 11:37:28', '1', '2023-07-11 11:37:28', 'admin', 'TesType', 'TesType');
INSERT INTO "public"."tms_merchant_type" VALUES ('cf94bb84-ee49-43e9-854d-1022494edb3f', 1, '2023-07-11 12:11:42', '1', '2023-07-11 12:11:42', '', NULL, NULL, 'tes', NULL);

-- ----------------------------
-- Table structure for tms_states
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_states";
CREATE TABLE "public"."tms_states" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "country_id" uuid NOT NULL,
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_states
-- ----------------------------
INSERT INTO "public"."tms_states" VALUES ('f55019ef-21d4-40fa-a5dd-aaa6fa226117', 1, '2023-07-10 18:27:21', '1', '2023-07-10 19:01:23', '1', '2023-07-10 19:01:23', 'admin', 'fd58e10f-6026-4c73-a711-ce04da99ced9', 'jakarta');
INSERT INTO "public"."tms_states" VALUES ('96873611-c3a5-4dc7-860a-a13ac4c74513', 1, '2023-07-10 08:50:29', '1', '2023-07-10 09:41:46', '1', NULL, NULL, '89585139-6512-4e1e-bd72-2cd2b46d75c0', 'MU');
INSERT INTO "public"."tms_states" VALUES ('d9776c62-5782-4c90-97c0-91b9458ddc97', 1, '2023-07-10 09:35:28', '1', '2023-07-14 16:53:43', '1', '2023-07-14 16:53:43', 'admin', '89585139-6512-4e1e-bd72-2cd2b46d75c0', 'Liverpool');
INSERT INTO "public"."tms_states" VALUES ('5e2a235c-c91c-44fb-bddf-0ed04f93e1e5', 3, '2023-07-10 18:26:55', '1', '2023-07-10 18:32:43', '1', NULL, NULL, 'fd58e10f-6026-4c73-a711-ce04da99ced9', 'Banten2');
INSERT INTO "public"."tms_states" VALUES ('059c2036-337e-4156-88b1-37e5eab4ff96', 2, '2023-07-14 17:08:07', '1', '2023-07-14 17:10:48', '1', NULL, NULL, 'fd58e10f-6026-4c73-a711-ce04da99ced9', 'Bali United');

-- ----------------------------
-- Table structure for tms_tenant
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_tenant";
CREATE TABLE "public"."tms_tenant" (
  "id" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "super_tenant_id" varchar(50) COLLATE "pg_catalog"."default",
  "is_super" bool NOT NULL
)
;

-- ----------------------------
-- Records of tms_tenant
-- ----------------------------
INSERT INTO "public"."tms_tenant" VALUES ('tripmitra', 1, '2023-07-07 00:10:00', 'admin', NULL, NULL, NULL, NULL, 'Tripmitra', NULL, 't');
INSERT INTO "public"."tms_tenant" VALUES ('qualita', 1, '2023-07-07 00:10:00', 'admin', NULL, NULL, NULL, NULL, 'Qualita', 'tripmitra', 'f');

-- ----------------------------
-- Table structure for tms_terminal
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_terminal";
CREATE TABLE "public"."tms_terminal" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "imei" varchar(25) COLLATE "pg_catalog"."default" NOT NULL,
  "model_id" uuid NOT NULL,
  "merchant_id" uuid NOT NULL,
  "sn" varchar(30) COLLATE "pg_catalog"."default" NOT NULL,
  "profile_id" uuid,
  "tenant_id" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "is_locked" int4,
  "locked_reason" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Records of tms_terminal
-- ----------------------------

-- ----------------------------
-- Table structure for tms_terminal_group
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_terminal_group";
CREATE TABLE "public"."tms_terminal_group" (
  "id" uuid NOT NULL,
  "version" int4 NOT NULL,
  "create_ts" timestamp(6),
  "created_by" varchar(50) COLLATE "pg_catalog"."default",
  "update_ts" timestamp(6),
  "updated_by" varchar(50) COLLATE "pg_catalog"."default",
  "delete_ts" timestamp(6),
  "deleted_by" varchar(50) COLLATE "pg_catalog"."default",
  "name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "description" varchar(255) COLLATE "pg_catalog"."default",
  "tenant_id" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of tms_terminal_group
-- ----------------------------

-- ----------------------------
-- Table structure for tms_terminal_group_link
-- ----------------------------
DROP TABLE IF EXISTS "public"."tms_terminal_group_link";
CREATE TABLE "public"."tms_terminal_group_link" (
  "terminal_id" uuid NOT NULL,
  "terminal_group_id" uuid NOT NULL
)
;

-- ----------------------------
-- Records of tms_terminal_group_link
-- ----------------------------

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."failed_jobs_id_seq"
OWNED BY "public"."failed_jobs"."id";
SELECT setval('"public"."failed_jobs_id_seq"', 2, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."jobs_id_seq"
OWNED BY "public"."jobs"."id";
SELECT setval('"public"."jobs_id_seq"', 14, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."migrations_id_seq"
OWNED BY "public"."migrations"."id";
SELECT setval('"public"."migrations_id_seq"', 4, true);

-- ----------------------------
-- Checks structure for table exportProcesses
-- ----------------------------
ALTER TABLE "public"."exportProcesses" ADD CONSTRAINT "exportProcesses_status_check" CHECK (status::text = ANY (ARRAY['DONE'::character varying, 'ON PROGRESS'::character varying, 'FAILED'::character varying]::text[]));

-- ----------------------------
-- Primary Key structure for table exportProcesses
-- ----------------------------
ALTER TABLE "public"."exportProcesses" ADD CONSTRAINT "exportProcesses_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table failed_jobs
-- ----------------------------
ALTER TABLE "public"."failed_jobs" ADD CONSTRAINT "failed_jobs_uuid_unique" UNIQUE ("uuid");

-- ----------------------------
-- Primary Key structure for table failed_jobs
-- ----------------------------
ALTER TABLE "public"."failed_jobs" ADD CONSTRAINT "failed_jobs_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table jobs
-- ----------------------------
CREATE INDEX "jobs_queue_index" ON "public"."jobs" USING btree (
  "queue" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table jobs
-- ----------------------------
ALTER TABLE "public"."jobs" ADD CONSTRAINT "jobs_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table migrations
-- ----------------------------
ALTER TABLE "public"."migrations" ADD CONSTRAINT "migrations_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tes
-- ----------------------------
ALTER TABLE "public"."tes" ADD CONSTRAINT "tes_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_application
-- ----------------------------
CREATE UNIQUE INDEX "idx_tms_application_unq" ON "public"."tms_application" USING btree (
  "package_name" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST,
  "version" "pg_catalog"."int4_ops" ASC NULLS LAST
) WHERE delete_ts IS NULL;

-- ----------------------------
-- Uniques structure for table tms_application
-- ----------------------------
ALTER TABLE "public"."tms_application" ADD CONSTRAINT "name" UNIQUE ("name");
ALTER TABLE "public"."tms_application" ADD CONSTRAINT "unique_name" UNIQUE ("unique_name");
ALTER TABLE "public"."tms_application" ADD CONSTRAINT "unique_icon_name" UNIQUE ("unique_icon_name");

-- ----------------------------
-- Primary Key structure for table tms_application
-- ----------------------------
ALTER TABLE "public"."tms_application" ADD CONSTRAINT "tms_application_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_city
-- ----------------------------
CREATE INDEX "idx_tms_city_on_states" ON "public"."tms_city" USING btree (
  "states_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table tms_city
-- ----------------------------
ALTER TABLE "public"."tms_city" ADD CONSTRAINT "tms_city_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_country
-- ----------------------------
CREATE UNIQUE INDEX "idx_tms_country_uk_code" ON "public"."tms_country" USING btree (
  "code" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
) WHERE delete_ts IS NULL;

-- ----------------------------
-- Uniques structure for table tms_country
-- ----------------------------
ALTER TABLE "public"."tms_country" ADD CONSTRAINT "1" UNIQUE ("code");

-- ----------------------------
-- Primary Key structure for table tms_country
-- ----------------------------
ALTER TABLE "public"."tms_country" ADD CONSTRAINT "tms_country_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_delete_task
-- ----------------------------
ALTER TABLE "public"."tms_delete_task" ADD CONSTRAINT "tms_delete_task_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_delete_task_app
-- ----------------------------
ALTER TABLE "public"."tms_delete_task_app" ADD CONSTRAINT "tms_delete_task_app_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_delete_task_log
-- ----------------------------
CREATE INDEX "idx_tms_delete_task_log_on_app" ON "public"."tms_delete_task_log" USING btree (
  "app_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tms_delete_task_log_on_task" ON "public"."tms_delete_task_log" USING btree (
  "task_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tms_delete_task_log_on_terminal" ON "public"."tms_delete_task_log" USING btree (
  "terminal_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table tms_delete_task_log
-- ----------------------------
ALTER TABLE "public"."tms_delete_task_log" ADD CONSTRAINT "tms_delete_task_log_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_delete_task_terminal_group_link
-- ----------------------------
ALTER TABLE "public"."tms_delete_task_terminal_group_link" ADD CONSTRAINT "tms_delete_task_terminal_group_link_pkey" PRIMARY KEY ("delete_task_id", "group_id");

-- ----------------------------
-- Primary Key structure for table tms_delete_task_terminal_link
-- ----------------------------
ALTER TABLE "public"."tms_delete_task_terminal_link" ADD CONSTRAINT "tms_delete_task_terminal_link_pkey" PRIMARY KEY ("terminal_id", "delete_task_id");

-- ----------------------------
-- Primary Key structure for table tms_device_model
-- ----------------------------
ALTER TABLE "public"."tms_device_model" ADD CONSTRAINT "tms_device_model_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_device_profile
-- ----------------------------
ALTER TABLE "public"."tms_device_profile" ADD CONSTRAINT "tms_device_profile_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_diagnostic_info
-- ----------------------------
CREATE INDEX "tms_diagnostic_info_create_ts_idx" ON "public"."tms_diagnostic_info" USING btree (
  "create_ts" "pg_catalog"."timestamp_ops" ASC NULLS LAST
);
CREATE INDEX "tms_diagnostic_info_sn_idx" ON "public"."tms_diagnostic_info" USING btree (
  "sn" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table tms_diagnostic_info
-- ----------------------------
ALTER TABLE "public"."tms_diagnostic_info" ADD CONSTRAINT "tms_diagnostic_info_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_district
-- ----------------------------
CREATE INDEX "idx_tms_district_on_city" ON "public"."tms_district" USING btree (
  "city_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table tms_district
-- ----------------------------
ALTER TABLE "public"."tms_district" ADD CONSTRAINT "tms_district_name_key" UNIQUE ("name");

-- ----------------------------
-- Primary Key structure for table tms_district
-- ----------------------------
ALTER TABLE "public"."tms_district" ADD CONSTRAINT "tms_district_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_download_task
-- ----------------------------
ALTER TABLE "public"."tms_download_task" ADD CONSTRAINT "tms_download_task_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_download_task_application_link
-- ----------------------------
ALTER TABLE "public"."tms_download_task_application_link" ADD CONSTRAINT "tms_download_task_application_link_pkey" PRIMARY KEY ("download_task_id", "application_id");

-- ----------------------------
-- Indexes structure for table tms_download_task_log
-- ----------------------------
CREATE INDEX "idx_tms_download_task_log_on_application" ON "public"."tms_download_task_log" USING btree (
  "application_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tms_download_task_log_on_task" ON "public"."tms_download_task_log" USING btree (
  "task_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tms_download_task_log_on_terminal" ON "public"."tms_download_task_log" USING btree (
  "terminal_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table tms_download_task_log
-- ----------------------------
ALTER TABLE "public"."tms_download_task_log" ADD CONSTRAINT "tms_download_task_log_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_download_task_terminal_group_link
-- ----------------------------
ALTER TABLE "public"."tms_download_task_terminal_group_link" ADD CONSTRAINT "tms_download_task_terminal_group_link_pkey" PRIMARY KEY ("download_task_id", "group_id");

-- ----------------------------
-- Primary Key structure for table tms_download_task_terminal_link
-- ----------------------------
ALTER TABLE "public"."tms_download_task_terminal_link" ADD CONSTRAINT "tms_download_task_terminal_link_pkey" PRIMARY KEY ("terminal_id", "download_task_id");

-- ----------------------------
-- Indexes structure for table tms_heart_beat
-- ----------------------------
CREATE INDEX "tms_heart_beat_create_ts_idx" ON "public"."tms_heart_beat" USING btree (
  "create_ts" "pg_catalog"."timestamp_ops" ASC NULLS LAST
);
CREATE INDEX "tms_heart_beat_sn_idx" ON "public"."tms_heart_beat" USING btree (
  "sn" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table tms_heart_beat
-- ----------------------------
ALTER TABLE "public"."tms_heart_beat" ADD CONSTRAINT "tms_heart_beat_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_merchant
-- ----------------------------
CREATE INDEX "idx_tms_merchant_on_district" ON "public"."tms_merchant" USING btree (
  "district_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tms_merchant_on_type" ON "public"."tms_merchant" USING btree (
  "type_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table tms_merchant
-- ----------------------------
ALTER TABLE "public"."tms_merchant" ADD CONSTRAINT "tms_merchant_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_merchant_type
-- ----------------------------
ALTER TABLE "public"."tms_merchant_type" ADD CONSTRAINT "tms_merchant_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_states
-- ----------------------------
CREATE INDEX "idx_tms_states_on_country" ON "public"."tms_states" USING btree (
  "country_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table tms_states
-- ----------------------------
ALTER TABLE "public"."tms_states" ADD CONSTRAINT "tms_states_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_tenant
-- ----------------------------
ALTER TABLE "public"."tms_tenant" ADD CONSTRAINT "tms_tenant_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tms_terminal
-- ----------------------------
CREATE INDEX "idx_tms_terminal_on_merchant" ON "public"."tms_terminal" USING btree (
  "merchant_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tms_terminal_on_model" ON "public"."tms_terminal" USING btree (
  "model_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tms_terminal_on_profile" ON "public"."tms_terminal" USING btree (
  "profile_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE UNIQUE INDEX "idx_tms_terminal_uk_sn" ON "public"."tms_terminal" USING btree (
  "sn" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
) WHERE delete_ts IS NULL;

-- ----------------------------
-- Primary Key structure for table tms_terminal
-- ----------------------------
ALTER TABLE "public"."tms_terminal" ADD CONSTRAINT "tms_terminal_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_terminal_group
-- ----------------------------
ALTER TABLE "public"."tms_terminal_group" ADD CONSTRAINT "tms_terminal_group_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tms_terminal_group_link
-- ----------------------------
ALTER TABLE "public"."tms_terminal_group_link" ADD CONSTRAINT "tms_terminal_group_link_pkey" PRIMARY KEY ("terminal_id", "terminal_group_id");

-- ----------------------------
-- Foreign Keys structure for table tms_city
-- ----------------------------
ALTER TABLE "public"."tms_city" ADD CONSTRAINT "fk_tms_city_on_states" FOREIGN KEY ("states_id") REFERENCES "public"."tms_states" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_delete_task_app
-- ----------------------------
ALTER TABLE "public"."tms_delete_task_app" ADD CONSTRAINT "tms_delete_task_app_fk" FOREIGN KEY ("task_id") REFERENCES "public"."tms_delete_task" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_delete_task_log
-- ----------------------------
ALTER TABLE "public"."tms_delete_task_log" ADD CONSTRAINT "fk_tms_delete_task_log_on_app" FOREIGN KEY ("app_id") REFERENCES "public"."tms_delete_task_app" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_delete_task_log" ADD CONSTRAINT "fk_tms_delete_task_log_on_task" FOREIGN KEY ("task_id") REFERENCES "public"."tms_delete_task" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_delete_task_log" ADD CONSTRAINT "fk_tms_delete_task_log_on_terminal" FOREIGN KEY ("terminal_id") REFERENCES "public"."tms_terminal" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_delete_task_log" ADD CONSTRAINT "tms_delete_task_log_fk" FOREIGN KEY ("app_id") REFERENCES "public"."tms_delete_task_app" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_delete_task_terminal_group_link
-- ----------------------------
ALTER TABLE "public"."tms_delete_task_terminal_group_link" ADD CONSTRAINT "fk_deltastergro_on_delete_task" FOREIGN KEY ("delete_task_id") REFERENCES "public"."tms_delete_task" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_delete_task_terminal_group_link" ADD CONSTRAINT "fk_deltastergro_on_terminal_group" FOREIGN KEY ("group_id") REFERENCES "public"."tms_terminal_group" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_delete_task_terminal_link
-- ----------------------------
ALTER TABLE "public"."tms_delete_task_terminal_link" ADD CONSTRAINT "fk_dowtaster_on_delete_task" FOREIGN KEY ("delete_task_id") REFERENCES "public"."tms_delete_task" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_delete_task_terminal_link" ADD CONSTRAINT "fk_dowtaster_on_terminal" FOREIGN KEY ("terminal_id") REFERENCES "public"."tms_terminal" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_device_profile
-- ----------------------------
ALTER TABLE "public"."tms_device_profile" ADD CONSTRAINT "tms_device_profile_fk" FOREIGN KEY ("tenant_id") REFERENCES "public"."tms_tenant" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table tms_district
-- ----------------------------
ALTER TABLE "public"."tms_district" ADD CONSTRAINT "fk_tms_district_on_city" FOREIGN KEY ("city_id") REFERENCES "public"."tms_city" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_download_task_application_link
-- ----------------------------
ALTER TABLE "public"."tms_download_task_application_link" ADD CONSTRAINT "fk_dowtasapp_on_application" FOREIGN KEY ("application_id") REFERENCES "public"."tms_application" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_download_task_application_link" ADD CONSTRAINT "fk_dowtasapp_on_download_task" FOREIGN KEY ("download_task_id") REFERENCES "public"."tms_download_task" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_download_task_log
-- ----------------------------
ALTER TABLE "public"."tms_download_task_log" ADD CONSTRAINT "fk_tms_download_task_log_on_application" FOREIGN KEY ("application_id") REFERENCES "public"."tms_application" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_download_task_log" ADD CONSTRAINT "fk_tms_download_task_log_on_task" FOREIGN KEY ("task_id") REFERENCES "public"."tms_download_task" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_download_task_log" ADD CONSTRAINT "fk_tms_download_task_log_on_terminal" FOREIGN KEY ("terminal_id") REFERENCES "public"."tms_terminal" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_download_task_terminal_group_link
-- ----------------------------
ALTER TABLE "public"."tms_download_task_terminal_group_link" ADD CONSTRAINT "fk_dowtastergro_on_download_task" FOREIGN KEY ("download_task_id") REFERENCES "public"."tms_download_task" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_download_task_terminal_group_link" ADD CONSTRAINT "fk_dowtastergro_on_terminal_group" FOREIGN KEY ("group_id") REFERENCES "public"."tms_terminal_group" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_download_task_terminal_link
-- ----------------------------
ALTER TABLE "public"."tms_download_task_terminal_link" ADD CONSTRAINT "fk_dowtaster_on_download_task" FOREIGN KEY ("download_task_id") REFERENCES "public"."tms_download_task" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_download_task_terminal_link" ADD CONSTRAINT "fk_dowtaster_on_terminal" FOREIGN KEY ("terminal_id") REFERENCES "public"."tms_terminal" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_merchant
-- ----------------------------
ALTER TABLE "public"."tms_merchant" ADD CONSTRAINT "fk_tms_merchant_on_district" FOREIGN KEY ("district_id") REFERENCES "public"."tms_district" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_merchant" ADD CONSTRAINT "fk_tms_merchant_on_type" FOREIGN KEY ("type_id") REFERENCES "public"."tms_merchant_type" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_merchant" ADD CONSTRAINT "tms_merchant_fk" FOREIGN KEY ("tenant_id") REFERENCES "public"."tms_tenant" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table tms_states
-- ----------------------------
ALTER TABLE "public"."tms_states" ADD CONSTRAINT "fk_tms_states_on_country" FOREIGN KEY ("country_id") REFERENCES "public"."tms_country" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tms_tenant
-- ----------------------------
ALTER TABLE "public"."tms_tenant" ADD CONSTRAINT "tms_tenant_fk" FOREIGN KEY ("super_tenant_id") REFERENCES "public"."tms_tenant" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table tms_terminal
-- ----------------------------
ALTER TABLE "public"."tms_terminal" ADD CONSTRAINT "fk_tms_terminal_on_merchant" FOREIGN KEY ("merchant_id") REFERENCES "public"."tms_merchant" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_terminal" ADD CONSTRAINT "fk_tms_terminal_on_model" FOREIGN KEY ("model_id") REFERENCES "public"."tms_device_model" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_terminal" ADD CONSTRAINT "fk_tms_terminal_on_profile" FOREIGN KEY ("profile_id") REFERENCES "public"."tms_device_profile" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_terminal" ADD CONSTRAINT "tms_terminal_fk" FOREIGN KEY ("tenant_id") REFERENCES "public"."tms_tenant" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table tms_terminal_group_link
-- ----------------------------
ALTER TABLE "public"."tms_terminal_group_link" ADD CONSTRAINT "fk_tergro_on_terminal" FOREIGN KEY ("terminal_id") REFERENCES "public"."tms_terminal" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tms_terminal_group_link" ADD CONSTRAINT "fk_tergro_on_terminal_group" FOREIGN KEY ("terminal_group_id") REFERENCES "public"."tms_terminal_group" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
