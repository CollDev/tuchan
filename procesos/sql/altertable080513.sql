ALTER TABLE default_cms_canales ADD estado_migracion_sphinx TINYINT(4);
ALTER TABLE default_cms_canales ADD fecha_migracion_sphinx DATETIME;
ALTER TABLE default_cms_canales ADD fecha_migracion_actualizacion_sphinx DATETIME;

ALTER TABLE default_cms_grupo_maestros ADD horario_transmision_inicio TIME;
ALTER TABLE default_cms_grupo_maestros ADD horario_transmision_fin TIME;
ALTER TABLE default_cms_grupo_maestros ADD estado_migracion_sphinx TINYINT(4);
ALTER TABLE default_cms_grupo_maestros ADD fecha_migracion_sphinx DATETIME;
ALTER TABLE default_cms_grupo_maestros ADD fecha_migracion_actualizacion_sphinx DATETIME;

ALTER TABLE default_cms_videos ADD estado_migracion_sphinx TINYINT(4);
ALTER TABLE default_cms_videos ADD fecha_migracion_sphinx DATETIME;
ALTER TABLE default_cms_videos ADD fecha_migracion_actualizacion_sphinx DATETIME;
ALTER TABLE default_cms_videos ADD rutasplitter VARCHAR(250);
ALTER TABLE default_cms_videos ADD procedencia TINYINT(4);

