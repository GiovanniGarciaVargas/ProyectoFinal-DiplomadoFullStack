/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 50620
 Source Host           : localhost:3306
 Source Schema         : incidenciasump

 Target Server Type    : MySQL
 Target Server Version : 50620
 File Encoding         : 65001

 Date: 14/09/2024 19:35:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for aireacondicionado
-- ----------------------------
DROP TABLE IF EXISTS `aireacondicionado`;
CREATE TABLE `aireacondicionado`  (
  `eCodAireAcondicionado` int(11) NOT NULL AUTO_INCREMENT,
  `tMarcaAireAcondicionado` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tModeloAireAcondicionado` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `eNumeroSerieAire` int(50) NULL DEFAULT NULL,
  `eNumeroInventarioAire` int(50) NULL DEFAULT NULL,
  `bEstadoAire` tinyint(4) NULL DEFAULT NULL,
  `fhFechaHoraCreacionAire` timestamp NULL DEFAULT NULL,
  `fhFechaHoraActualizacionAire` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`eCodAireAcondicionado`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of aireacondicionado
-- ----------------------------

-- ----------------------------
-- Table structure for asignacionaulas
-- ----------------------------
DROP TABLE IF EXISTS `asignacionaulas`;
CREATE TABLE `asignacionaulas`  (
  `eCodAsignacion` int(11) NOT NULL AUTO_INCREMENT,
  `fk_eCodProyector` int(11) NULL DEFAULT NULL,
  `fk_eCodAireAcondicionado` int(11) NULL DEFAULT NULL,
  `fk_eCodAmplificador` int(11) NULL DEFAULT NULL,
  `fk_eCodPantallaProyeccion` int(11) NULL DEFAULT NULL,
  `fk_eCodAula` int(11) NULL DEFAULT NULL,
  `fk_eCodMicrofono` int(11) NULL DEFAULT NULL,
  `fk_eCodEquipoComputo` int(11) NULL DEFAULT NULL,
  `fk_eCodRegulador` int(11) NULL DEFAULT NULL,
  `fk_eCodCamaraWeb` int(11) NULL DEFAULT NULL,
  `bEstadoAsignacion` tinyint(1) NULL DEFAULT NULL,
  `fhFechaHoraCreacion` datetime NULL DEFAULT NULL,
  `fhFechaHoraActualizacion` datetime NULL DEFAULT NULL,
  `fk_eCodUsuario` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`eCodAsignacion`) USING BTREE,
  INDEX `fk_eCodProyector`(`fk_eCodProyector`) USING BTREE,
  INDEX `fk_eCodAireAcondicionado`(`fk_eCodAireAcondicionado`) USING BTREE,
  INDEX `fk_eCodAmplificador`(`fk_eCodAmplificador`) USING BTREE,
  INDEX `fk_eCodPantallaProyeccion`(`fk_eCodPantallaProyeccion`) USING BTREE,
  INDEX `fk_eCodAula`(`fk_eCodAula`) USING BTREE,
  INDEX `fk_eCodMicrofono`(`fk_eCodMicrofono`) USING BTREE,
  INDEX `fk_eCodEquipoComputo`(`fk_eCodEquipoComputo`) USING BTREE,
  INDEX `fk_eCodRegulador`(`fk_eCodRegulador`) USING BTREE,
  INDEX `fk_eCodCamaraWeb`(`fk_eCodCamaraWeb`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of asignacionaulas
-- ----------------------------

-- ----------------------------
-- Table structure for aula
-- ----------------------------
DROP TABLE IF EXISTS `aula`;
CREATE TABLE `aula`  (
  `eCodAula` int(11) NOT NULL AUTO_INCREMENT,
  `fk_eCodPiso` int(11) NULL DEFAULT NULL,
  `tNombreAula` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bEstadoAula` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fhFechaHoraCreacionAula` timestamp NULL DEFAULT NULL,
  `fhFechaHoraActualizacionAula` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`eCodAula`) USING BTREE,
  INDEX `fk_eCodPlanta`(`fk_eCodPiso`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of aula
-- ----------------------------

-- ----------------------------
-- Table structure for camaraweb
-- ----------------------------
DROP TABLE IF EXISTS `camaraweb`;
CREATE TABLE `camaraweb`  (
  `eCodCamaraWeb` int(11) NOT NULL AUTO_INCREMENT,
  `tMarcaCamaraWeb` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tModeloCamaraWeb` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `eNumeroInventarioCamara` int(50) NULL DEFAULT NULL,
  `eNumeroSerieCamara` int(50) NULL DEFAULT NULL,
  `bEstadoCamara` tinyint(4) NULL DEFAULT NULL,
  `fhFechaHoraCreacionCamara` timestamp NULL DEFAULT NULL,
  `fhFechaHoraActualizacionCamara` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`eCodCamaraWeb`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of camaraweb
-- ----------------------------

-- ----------------------------
-- Table structure for equipoaudio
-- ----------------------------
DROP TABLE IF EXISTS `equipoaudio`;
CREATE TABLE `equipoaudio`  (
  `eCodAmplificador` int(11) NOT NULL AUTO_INCREMENT,
  `tMarcaAmplificador` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tModeloAmplificador` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `eNumeroInventarioAmplificador` int(11) NOT NULL,
  `eNumeroSerieAmplificador` int(11) NOT NULL,
  `bEstadoAmplificador` tinyint(1) NOT NULL,
  `fhFechaHoraCreacionAmplificador` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fhFechaHoraActualizacionAmplificador` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`eCodAmplificador`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of equipoaudio
-- ----------------------------

-- ----------------------------
-- Table structure for equipocomputo
-- ----------------------------
DROP TABLE IF EXISTS `equipocomputo`;
CREATE TABLE `equipocomputo`  (
  `eCodEquipoComputo` int(11) NOT NULL AUTO_INCREMENT,
  `tMarcaEquipoComputo` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tModeloEquipoComputo` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `eNumeroInventarioEquipoComputo` int(11) NOT NULL,
  `eNumeroSerieGabineteEquipoComputo` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `eNumeroSerieMouseEquipoComputo` int(20) NOT NULL,
  `eNumeroSerieTecladoEquipoComputo` int(20) NOT NULL,
  `bEstadoEquipoComputo` tinyint(1) NOT NULL,
  `fhFechaHoraCreacionEquipoComputo` timestamp NOT NULL,
  `fhFechaHoraActualizacionEquipoComputo` timestamp NOT NULL,
  PRIMARY KEY (`eCodEquipoComputo`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of equipocomputo
-- ----------------------------

-- ----------------------------
-- Table structure for incidencias
-- ----------------------------
DROP TABLE IF EXISTS `incidencias`;
CREATE TABLE `incidencias`  (
  `eCodIncidencia` int(11) NOT NULL AUTO_INCREMENT,
  `fk_eCodAsignacion` int(11) NOT NULL,
  `tDescripcionIncidencia` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bEstadoProyector` tinyint(1) NOT NULL,
  `bEstadoPantalla` tinyint(1) NOT NULL,
  `bEstadoAire` tinyint(1) NOT NULL,
  `tModalidadIncidencia` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tEvidenciaIncidenciaEquipoComputo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tEvidenciaIncidenciaProyector` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tEvidenciaIncidenciaAire` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fk_eCodUsuarioRegistraIncidencia` int(11) NOT NULL,
  `fhFechaHoraRegistro` datetime NOT NULL,
  `tEstadoIncidencia` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fk_eCodUsuarioAtiendeIncidencia` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fhFechaHoraCierreIncidencia` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`eCodIncidencia`) USING BTREE,
  INDEX `fk_eCodAsignacion`(`fk_eCodAsignacion`) USING BTREE,
  INDEX `fk_eCodUsuario`(`fk_eCodUsuarioRegistraIncidencia`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of incidencias
-- ----------------------------

-- ----------------------------
-- Table structure for microfono
-- ----------------------------
DROP TABLE IF EXISTS `microfono`;
CREATE TABLE `microfono`  (
  `eCodMicrofono` int(11) NOT NULL AUTO_INCREMENT,
  `tMarcaMicrofono` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tModeloMicrofono` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bTipoMicrofono` tinyint(4) NULL DEFAULT NULL,
  `eNumeroInventarioMicrofono` int(11) NULL DEFAULT NULL,
  `eNumeroSerieMicrofono` int(11) NULL DEFAULT NULL,
  `fhFechaHoraCreacionMicrofono` timestamp NULL DEFAULT NULL,
  `fhFechaHoraActualizacionMicrofono` timestamp NULL DEFAULT NULL,
  `bEstadoMicrofono` tinyint(4) NULL DEFAULT NULL,
  PRIMARY KEY (`eCodMicrofono`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of microfono
-- ----------------------------

-- ----------------------------
-- Table structure for pantallaproyeccion
-- ----------------------------
DROP TABLE IF EXISTS `pantallaproyeccion`;
CREATE TABLE `pantallaproyeccion`  (
  `eCodPantallaProyeccion` int(11) NOT NULL AUTO_INCREMENT,
  `tMarcaPantalla` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tModeloPantalla` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `eNumeroInventarioPantalla` varchar(18) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `eNumeroSeriePantalla` varchar(18) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bTipoPantalla` tinyint(4) NOT NULL,
  `bEstadoPantalla` tinyint(1) NOT NULL,
  `fhFechaHoraCreacionPantalla` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fhFechaHoraActualizacionPantalla` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`eCodPantallaProyeccion`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantallaproyeccion
-- ----------------------------

-- ----------------------------
-- Table structure for plantaedificio
-- ----------------------------
DROP TABLE IF EXISTS `plantaedificio`;
CREATE TABLE `plantaedificio`  (
  `eCodPlanta` int(11) NOT NULL AUTO_INCREMENT,
  `tNombrePlanta` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bEstadoPlanta` tinyint(4) NULL DEFAULT NULL,
  `fhFechaHoraCreacionPlanta` timestamp NULL DEFAULT NULL,
  `fhFechaHoraActualizacionPlanta` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`eCodPlanta`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of plantaedificio
-- ----------------------------

-- ----------------------------
-- Table structure for proyector
-- ----------------------------
DROP TABLE IF EXISTS `proyector`;
CREATE TABLE `proyector`  (
  `eCodProyector` int(11) NOT NULL,
  `tMarcaProyector` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tModeloProyector` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `eNumeroSerieProyector` int(11) NULL DEFAULT NULL,
  `eNumeroInventarioProyector` int(11) NULL DEFAULT NULL,
  `bEstadoProyector` tinyint(4) NULL DEFAULT NULL,
  `fhFechaHoraCreacion` datetime NULL DEFAULT NULL,
  `fhFechaHoraActualizacion` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`eCodProyector`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of proyector
-- ----------------------------

-- ----------------------------
-- Table structure for puesto
-- ----------------------------
DROP TABLE IF EXISTS `puesto`;
CREATE TABLE `puesto`  (
  `eCodPuesto` int(11) NOT NULL AUTO_INCREMENT,
  `tNombrePuesto` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bEstadoPuesto` tinyint(1) NOT NULL,
  `fhFechaHoraCreacionPuesto` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  `fhFechaHoraActualizacionPuesto` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`eCodPuesto`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of puesto
-- ----------------------------
INSERT INTO `puesto` VALUES (1, 'Vicerector', 1, '2024-07-02 15:47:42', '2024-07-02 15:47:42');

-- ----------------------------
-- Table structure for regulador
-- ----------------------------
DROP TABLE IF EXISTS `regulador`;
CREATE TABLE `regulador`  (
  `eCodRegulador` int(11) NOT NULL AUTO_INCREMENT,
  `tMarcaRegulador` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tModeloRegulador` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `eNumeroInventarioRegulador` int(50) NULL DEFAULT NULL,
  `eNumeroSerieInventario` int(50) NULL DEFAULT NULL,
  `bEstadoRegulador` tinyint(4) NULL DEFAULT NULL,
  `fhFechaHoraCreacionRegulador` timestamp NULL DEFAULT NULL,
  `fhFechaHoraActualizacionRegulador` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`eCodRegulador`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of regulador
-- ----------------------------

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `eCodUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `tNombreUsuario` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tApellidoPaterno` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tApellidoMaterno` varchar(2000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tCorreoUsuario` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tContrasena` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bEstadoUsuario` tinyint(1) NOT NULL,
  `fk_eCodPuesto` int(11) NOT NULL,
  `fhFechaHoraCreacionUsuario` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fhFechaHoraActualizacionUsuario` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`eCodUsuario`) USING BTREE,
  INDEX `fk_eCodPuesto`(`fk_eCodPuesto`) USING BTREE,
  INDEX `fk_eCodPuesto_2`(`fk_eCodPuesto`) USING BTREE,
  INDEX `fk_eCodPuesto_3`(`fk_eCodPuesto`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES (8, 'Prueba', 'Prueba', 'Prueba', 'test@ump.edu.mx', '$2y$10$KlnKKhtVzXkA4HLG4DOl0OUmluxiIEabQWX3mkFIZ6hxQAqt6cFwW', 1, 1, '0000-00-00 00:00:00', '2024-09-14 21:29:14');

SET FOREIGN_KEY_CHECKS = 1;
