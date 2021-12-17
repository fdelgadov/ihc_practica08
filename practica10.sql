#
# Estructura de tabla para la tabla `ln_diccionario`
#

CREATE TABLE ln_diccionario (
  palabra varchar(30) NOT NULL default '',
  normalizada varchar(30) NOT NULL default ''
) TYPE=MyISAM COMMENT='Diccionario para el reconocedor de lenguaje natural';

#
# Volcar la base de datos para la tabla `ln_diccionario`
#

INSERT INTO ln_diccionario VALUES ('busco', 'busco');
INSERT INTO ln_diccionario VALUES ('deseo', 'busco');
INSERT INTO ln_diccionario VALUES ('quiero', 'busco');
INSERT INTO ln_diccionario VALUES ('solicito', 'busco');
INSERT INTO ln_diccionario VALUES ('piso', 'Piso');
INSERT INTO ln_diccionario VALUES ('casa', 'Casa');
INSERT INTO ln_diccionario VALUES ('chalet', 'Chalet');
INSERT INTO ln_diccionario VALUES ('adosado', 'Adosado');
INSERT INTO ln_diccionario VALUES ('centro', 'Centro');
INSERT INTO ln_diccionario VALUES ('nervión', 'Nervión');
INSERT INTO ln_diccionario VALUES ('triana', 'Triana');
INSERT INTO ln_diccionario VALUES ('macarena', 'Macarena');
INSERT INTO ln_diccionario VALUES ('aljarafe', 'Aljarafe');
INSERT INTO ln_diccionario VALUES ('precio', 'precio');
INSERT INTO ln_diccionario VALUES ('dormitorio', 'dormitorios');
INSERT INTO ln_diccionario VALUES ('dormitorios', 'dormitorios');
INSERT INTO ln_diccionario VALUES ('habitación', 'dormitorios');
INSERT INTO ln_diccionario VALUES ('habitaciones', 'dormitorios');
INSERT INTO ln_diccionario VALUES ('euro', 'euros');
INSERT INTO ln_diccionario VALUES ('euros', 'euros');
INSERT INTO ln_diccionario VALUES ('uno', '1');
INSERT INTO ln_diccionario VALUES ('dos', '2');
INSERT INTO ln_diccionario VALUES ('tres', '3');
INSERT INTO ln_diccionario VALUES ('cuatro', '4');
INSERT INTO ln_diccionario VALUES ('cinco', '5');
INSERT INTO ln_diccionario VALUES ('piscina', 'Piscina');
INSERT INTO ln_diccionario VALUES ('garage', 'Garage');
INSERT INTO ln_diccionario VALUES ('jardín', 'Jardín');
INSERT INTO ln_diccionario VALUES ('mas', 'mas');
INSERT INTO ln_diccionario VALUES ('más', 'mas');
INSERT INTO ln_diccionario VALUES ('metros', 'metros');
INSERT INTO ln_diccionario VALUES ('barato', 'barato');

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `ln_patrones`
#

CREATE TABLE ln_patrones (
  id tinyint(3) unsigned NOT NULL auto_increment,
  patron varchar(100) NOT NULL default '',
  consultasql varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Patrones para el reconocedor de lenguaje natural';

#
# Volcar la base de datos para la tabla `ln_patrones`
#

INSERT INTO ln_patrones VALUES (1, 'busco tipo', 'select * from viviendas where tipo = \'%1\'');
INSERT INTO ln_patrones VALUES (2, 'busco zona', 'select * from viviendas where zona = \'%1\'');
INSERT INTO ln_patrones VALUES (3, 'busco tipo zona', 'select * from viviendas where tipo = \'%1\' and zona = \'%2\'');
INSERT INTO ln_patrones VALUES (4, 'busco tipo numero dormitorios zona', 'select * from viviendas where tipo = \'%1\' and ndormitorios=\'%2\' and zona=\'%3\'');
INSERT INTO ln_patrones VALUES (5, 'busco tipo zona extras', 'select * from viviendas where tipo = \'%1\' and zona = \'%2\' and extras like \'%%3%\'');
INSERT INTO ln_patrones VALUES (6, 'busco tipo mas numero dormitorios zona', 'select * from viviendas where tipo = \'%1\' and ndormitorios>\'%2\' and zona=\'%3\'');
INSERT INTO ln_patrones VALUES (7, 'busco tipo zona zona', 'select * from viviendas where tipo = \'%1\' and (zona = \'%2\' or zona=\'%3\')');
INSERT INTO ln_patrones VALUES (8, 'busco tipo mas numero metros', 'select * from viviendas where tipo = \'%1\' and tamano>\'%2\'');
INSERT INTO ln_patrones VALUES (9, 'busco tipo barato', 'select * from viviendas where tipo = \'%1\' and precio<\'200000\'');

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `viviendas`
#

CREATE TABLE viviendas (
  id smallint(5) unsigned NOT NULL auto_increment,
  tipo enum('Piso','Adosado','Chalet','Casa') NOT NULL default 'Piso',
  zona enum('Centro','Nervión','Triana','Aljarafe','Macarena') NOT NULL default 'Centro',
  direccion varchar(100) NOT NULL default '',
  ndormitorios enum('1','2','3','4','5') NOT NULL default '3',
  precio decimal(10,0) NOT NULL default '0',
  tamano decimal(10,0) NOT NULL default '0',
  extras set('Piscina','Jardín','Garage') NOT NULL default '',
  foto varchar(50) default NULL,
  observaciones text,
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Viviendas de la inmobiliaria Lindavista';

#
# Volcar la base de datos para la tabla `viviendas`
#

INSERT INTO viviendas VALUES (1, 'Piso', 'Nervión', 'Avda de la Buhaira', '4', '360000', '125', 'Garaje', 'piso1.jpg', 'Aire acondicionado frío/calor, trastero, amueblado, reciente construcción');
INSERT INTO viviendas VALUES (2, 'Chalet', 'Aljarafe', 'Calle del Rosal', '4', '450000', '180', 'Piscina,Jardín,Garaje', 'chalet1.jpg', 'Chalet independiente de una sóla planta en parcela de 1000 metros cuadrados con piscina y cancha de tenis, dentro de una urbanización cerrada y vigilada con club social');
INSERT INTO viviendas VALUES (3, 'Piso', 'Nervión', 'Avda de Kansas City', '2', '215000', '89', 'Garaje', 'apartamento1.jpg', 'Luminoso y bien situado. Reformado recientemente. Oportunidad');
INSERT INTO viviendas VALUES (4, 'Piso', 'Macarena', 'Ronda de los Olmos', '3', '165000', '83', '', 'piso3.jpg', 'Completamente reformado. Soleado. Vistas al río');
INSERT INTO viviendas VALUES (5, 'Adosado', 'Aljarafe', 'Urb. Santa Mónica', '4', '300000', '130', 'Piscina,Jardín,Garaje', 'adosado1.jpg', 'Urbanización de reciente construcción. Zona ajardinada interior con piscina y pistas de paddle-tenis. Amplias facilidades');
INSERT INTO viviendas VALUES (6, 'Casa', 'Centro', 'Pintor Murillo', '2', '150000', '93', '', 'casa1.jpg', 'Edificio reformado en pleno centro histórico de la ciudad. Patio interior cubierto');
INSERT INTO viviendas VALUES (7, 'Piso', 'Nervión', 'Avda Eduardo Dato', '3', '375000', '130', 'Garaje', 'piso2.jpg', 'Amplio trastero');
INSERT INTO viviendas VALUES (8, 'Piso', 'Nervión', 'San Bernardo', '2', '205000', '80', 'Garaje', 'apartamento2.jpg', '');
INSERT INTO viviendas VALUES (9, 'Piso', 'Nervión', 'Enramadilla', '2', '215000', '85', 'Garaje', 'apartamento3.jpg', '');
INSERT INTO viviendas VALUES (10, 'Chalet', 'Aljarafe', 'Calle Pino Verde', '4', '430000', '170', 'Piscina,Jardín,Garaje', 'chalet2.jpg', 'Chalet independiente de una sóla planta en parcela de 500 metros cuadrados con piscina y pista de pádel');
INSERT INTO viviendas VALUES (11, 'Chalet', 'Aljarafe', 'Calle Jacaranda', '4', '425000', '175', 'Piscina,Jardín,Garaje', 'chalet3.jpg', 'Chalet adosado de dos plantas en parcela de 600 metros cuadrados con piscina y pista de pádel');
INSERT INTO viviendas VALUES (12, 'Chalet', 'Aljarafe', 'Calle Mimosa', '3', '405000', '145', 'Piscina,Jardín,Garaje', 'chalet4.jpg', 'Chalet pareado de dos plantas');
INSERT INTO viviendas VALUES (13, 'Chalet', 'Aljarafe', 'Calle Violeta', '3', '400000', '143', 'Piscina,Jardín,Garaje', 'chalet5.jpg', 'Chalet pareado de dos plantas');
INSERT INTO viviendas VALUES (14, 'Piso', 'Nervión', 'Avda de la Buhaira', '3', '385000', '145', 'Garaje', 'duplex1.jpg', 'Dúplex muy luminoso y con vistas');
INSERT INTO viviendas VALUES (15, 'Piso', 'Nervión', 'Avda Eduardo Dato', '4', '455000', '165', 'Garaje', 'duplex2.jpg', 'Ático con vistas espectaculares');

