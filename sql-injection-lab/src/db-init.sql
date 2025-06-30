CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL
);

INSERT INTO users (username, password) VALUES
('admin', 'admin123'),
('alessio.rossi', 'AleRo2023!'),
('martina.bianchi', 'Marti#789'),
('federico.verdi', 'FedeVerdi88'),
('giulia.conti', 'GiuC@nti22'),
('andrea.moretto', 'Andre_Mor99'),
('chiara.fontana', 'ChFont123'),
('marco.galli', 'MGalli$456'),
('elena.riva', 'Elen4Riva!'),
('simone.ferri', 'Simo_F123'),
('valentina.pace', 'Val_Pace2022'),
('davide.greco', 'D@vGreco33'),
('ilaria.moro', 'IllyM89_'),
('riccardo.pini', 'RickPini!22'),
('francesca.villa', 'FranVilla#1'),
('luca.costa', 'LucaC_777'),
('serena.mattioli', 'SereMat!94'),
('giovanni.lodi', 'GioLodi88'),
('marta.neri', 'MaNeri_543'),
('giorgio.riva', 'GiorRiv2021'),
('sofia.cantoni', 'SofCan#99'),
('paolo.bianchi', 'PBianch!234');
